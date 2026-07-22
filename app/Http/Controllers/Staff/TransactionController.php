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
            'date' => 'nullable|date',
            'milling_type' => 'nullable|in:all,menudo,commercial',
        ]);

        $date = $request->date ?? Carbon::today()->toDateString();
        $millingType = $request->milling_type ?? 'all';

        $query = Transaction::with('delivery')
            ->where('payment_status', 'paid')
            ->where('user_id', Auth::id())
            ->whereRaw('DATE(COALESCE(paid_at, created_at)) = ?', [$date]);

        if ($millingType !== 'all') {
            $query->where('milling_type', $millingType);
        }

        $transactions = $query->orderByDesc('paid_at')->orderByDesc('id')->get();

        return view('staff.transactions', compact(
            'transactions',
            'date',
            'millingType'
        ));
    }
}
