<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PaymentRecordController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $request->validate([
            'search' => 'nullable|string|max:100',
            'date' => 'nullable|date',
            'milling_type' => 'nullable|in:all,menudo,commercial',
            'payment_method' => 'nullable|in:all,cash,gcash,maya',
            'staff_id' => [
                'nullable',
                Rule::when(
                    $request->input('staff_id') !== 'all',
                    Rule::exists('users', 'id')
                        ->where(fn ($query) => $query->where('role', 'staff'))
                ),
            ],
        ]);

        $search = trim((string) $request->search);
        $date = $request->date ?? Carbon::today()->toDateString();
        $millingType = $request->milling_type ?? 'all';
        $paymentMethod = $request->payment_method ?? 'all';
        $staffId = $request->staff_id ?? 'all';

        $query = Transaction::with(['delivery', 'user'])
            ->where('payment_status', 'paid')
            ->whereRaw('DATE(COALESCE(paid_at, created_at)) = ?', [$date]);

        if ($search !== '') {
            $receiptNumber = preg_replace('/\D/', '', $search);
            $query->where(function ($transactionQuery) use ($search, $receiptNumber) {
                $transactionQuery->whereHas('delivery', function ($deliveryQuery) use ($search) {
                    $deliveryQuery
                        ->where('client_name', 'like', "%{$search}%")
                        ->orWhere('delivery_id', 'like', "%{$search}%");
                });

                if ($receiptNumber !== '') {
                    $transactionQuery->orWhere('id', (int) $receiptNumber);
                }
            });
        }

        if ($millingType !== 'all') {
            $query->where('milling_type', $millingType);
        }

        if ($paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        if ($staffId !== 'all') {
            $query->where('user_id', (int) $staffId);
        }

        $transactions = $query->orderByDesc('paid_at')->orderByDesc('id')->get();

        $totalRecords = $transactions->count();
        $staffUsers = User::where('role', 'staff')->orderBy('name')->get();

        return view('owner.payment-records', compact(
            'transactions',
            'totalRecords',
            'search',
            'date',
            'millingType',
            'paymentMethod',
            'staffId',
            'staffUsers'
        ));
    }
}
