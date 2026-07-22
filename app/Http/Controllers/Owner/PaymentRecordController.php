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
            'date' => 'nullable|date',
            'milling_type' => 'nullable|in:all,menudo,commercial',
            'staff_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'staff')),
            ],
        ]);

        $date = $request->date ?? Carbon::today()->toDateString();
        $millingType = $request->milling_type ?? 'all';
        $staffId = $request->staff_id ?? 'all';

        $query = Transaction::with(['delivery', 'user'])
            ->where('payment_status', 'paid')
            ->whereRaw('DATE(COALESCE(paid_at, created_at)) = ?', [$date]);

        if ($millingType !== 'all') {
            $query->where('milling_type', $millingType);
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
            'date',
            'millingType',
            'staffId',
            'staffUsers'
        ));
    }
}
