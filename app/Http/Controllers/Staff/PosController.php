<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class PosController extends Controller
{
    public function create(Delivery $delivery)
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        if ($delivery->status !== 'completed') {
            return redirect()->back()->withErrors([
                'payment' => 'Payment is only available for completed deliveries.'
            ]);
        }


        if (!$delivery->hasSuccessfulNotification()) {
            return redirect()->back()->withErrors([
                'payment' => 'Notify the farmer successfully before recording payment.'
            ]);
        }

        if ($delivery->transaction) {
            return redirect('/staff/receipt/' . $delivery->id);
        }

        $palayWeight = (float) $delivery->palay_weight;

        $menudoFee = (float) Setting::getValue('menudo_fee', '2.00');
        $commercialFee = (float) Setting::getValue('commercial_fee', '1.50');
        $millingType = $delivery->milling_type_at_delivery
            ?? ((($delivery->client_type_at_delivery ?? optional($delivery->client)->client_type ?? 'regular') === 'commercial' || $palayWeight >= 500) ? 'commercial' : 'menudo');
        $millingFeePerKg = (float) ($delivery->milling_fee_per_kg_at_delivery
            ?? ($millingType === 'commercial' ? $commercialFee : $menudoFee));

        return view('staff.pos', compact(
            'delivery',
            'palayWeight',
            'millingFeePerKg',
            'menudoFee',
            'commercialFee',
            'millingType'
        ));
    }

    public function store(Request $request, Delivery $delivery)
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $request->validate([
            'other_charges' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,maya',
            'amount_received' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'reference_number' => [
                Rule::requiredIf(fn () => $request->payment_method !== 'cash'),
                'nullable',
                Rule::unique('transactions', 'reference_number'),
            ],
            'payment_proof' => [
                Rule::requiredIf(fn () => $request->payment_method !== 'cash'),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->payment_method === 'gcash') {
            $request->validate([
                'reference_number' => ['required', 'digits:13'],
            ]);
        }

        if ($request->payment_method === 'maya') {
            $request->validate([
                'reference_number' => ['required', 'digits_between:6,20'],
            ]);
        }

        $otherCharges = (float) ($request->other_charges ?? 0);
        $discount = (float) ($request->discount ?? 0);
        $amountReceived = (float) $request->amount_received;

        if (($otherCharges > 0 || $discount > 0) && blank($request->notes)) {
            throw ValidationException::withMessages([
                'notes' => 'Enter a reason whenever other charges or a discount is applied.',
            ]);
        }

        $paymentProofPath = null;

        if ($request->payment_method !== 'cash' && $request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        try {
            DB::transaction(function () use ($request, $delivery, $otherCharges, $discount, $amountReceived, $paymentProofPath): void {
                $delivery = Delivery::query()
                    ->with('client')
                    ->lockForUpdate()
                    ->findOrFail($delivery->id);

                if ($delivery->status !== 'completed') {
                    throw ValidationException::withMessages([
                        'payment' => 'Payment is only available for completed deliveries.',
                    ]);
                }


                if (!$delivery->hasSuccessfulNotification()) {
                    throw ValidationException::withMessages([
                        'payment' => 'Notify the farmer successfully before recording payment.',
                    ]);
                }

                if (Transaction::where('delivery_id', $delivery->id)->exists()) {
                    throw ValidationException::withMessages([
                        'payment' => 'This delivery already has a recorded transaction.',
                    ]);
                }

                $palayWeight = (float) $delivery->palay_weight;
                $menudoFee = (float) Setting::getValue('menudo_fee', '2.00');
                $commercialFee = (float) Setting::getValue('commercial_fee', '1.50');
                $millingType = $delivery->milling_type_at_delivery
                    ?? ((($delivery->client_type_at_delivery ?? $delivery->client->client_type ?? 'regular') === 'commercial' || $palayWeight >= 500) ? 'commercial' : 'menudo');
                $feePerKg = (float) ($delivery->milling_fee_per_kg_at_delivery
                    ?? ($millingType === 'commercial' ? $commercialFee : $menudoFee));
                $subtotal = $palayWeight * $feePerKg;

                if ($discount > $subtotal + $otherCharges) {
                    throw ValidationException::withMessages([
                        'discount' => 'Discount cannot exceed the subtotal plus other charges.',
                    ]);
                }

                $subtotal = round($subtotal, 2);
                $totalAmount = round(($subtotal + $otherCharges) - $discount, 2);
                $isDigital = $request->payment_method !== 'cash';

                if ($isDigital && round($amountReceived, 2) !== round($totalAmount, 2)) {
                    throw ValidationException::withMessages([
                        'amount_received' => 'GCash and Maya payments must equal the exact total amount.',
                    ]);
                }

                if (!$isDigital && $amountReceived < $totalAmount) {
                    throw ValidationException::withMessages([
                        'amount_received' => 'Amount received is less than the total amount.',
                    ]);
                }

                Transaction::create([
                    'delivery_id' => $delivery->id,
                    'user_id' => Auth::id(),
                    'milling_type' => $millingType,
                    'milling_fee_per_kg' => $feePerKg,
                    'palay_weight_kg' => $palayWeight,
                    'subtotal' => $subtotal,
                    'other_charges' => $otherCharges,
                    'discount' => $discount,
                    'total_amount' => $totalAmount,
                    'payment_method' => $request->payment_method,
                    'amount_received' => $amountReceived,
                    'change_amount' => $isDigital ? 0 : round($amountReceived - $totalAmount, 2),
                    'reference_number' => $isDigital ? $request->reference_number : null,
                    'payment_proof_path' => $isDigital ? $paymentProofPath : null,
                    'payment_status' => 'paid',
                    'paid_at' => Carbon::now(),
                    'notes' => $request->notes,
                ]);
            });
        } catch (Throwable $exception) {
            if ($paymentProofPath) {
                Storage::disk('public')->delete($paymentProofPath);
            }

            if (!$exception instanceof QueryException) {
                throw $exception;
            }

            if (($exception->errorInfo[0] ?? null) === '23000') {
                throw ValidationException::withMessages([
                    'payment' => 'This delivery or digital reference already has a recorded transaction.',
                ]);
            }

            throw $exception;
        }

        return redirect('/staff/receipt/' . $delivery->id)
            ->with('success', 'Payment recorded successfully.');
    }

    public function receipt(Delivery $delivery)
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $transaction = $delivery->transaction;

        if (!$transaction) {
            return redirect()->back()->withErrors([
                'receipt' => 'No transaction found for this delivery.'
            ]);
        }

        if ((int) $transaction->user_id !== (int) Auth::id()) {
            abort(403, 'You can only view receipts for payments you processed.');
        }

        return view('staff.receipt', compact(
            'delivery',
            'transaction'
        ));
    }
}
