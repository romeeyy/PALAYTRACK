<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $request->validate([
            'search' => 'nullable|string|max:100',
            'date' => 'nullable|date_format:Y-m-d',
            'milling_type' => 'nullable|in:all,menudo,commercial',
            'payment_method' => 'nullable|in:all,cash,gcash,maya',
        ]);

        $search = trim((string) $request->search);
        $date = $request->date ?? Carbon::today()->toDateString();
        $millingType = $request->milling_type ?? 'all';
        $paymentMethod = $request->payment_method ?? 'all';

        $query = Transaction::with('delivery')
            ->where('payment_status', 'paid')
            ->where('user_id', Auth::id())
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

        $transactions = $query
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('staff.transactions', compact(
            'transactions',
            'search',
            'date',
            'millingType',
            'paymentMethod'
        ));
    }
}
