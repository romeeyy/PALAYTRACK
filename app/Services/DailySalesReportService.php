<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DailySalesReportService
{
    public function generate(string $fromDate, int|string|null $staffId = null, ?string $toDate = null): array
    {
        $toDate ??= $fromDate;
        $baseQuery = $this->baseQuery($fromDate, $toDate, $staffId);

        $groupedSales = (clone $baseQuery)
            ->select(
                'deliveries.client_id',
                'deliveries.client_name',
                'transactions.milling_type',
                'transactions.user_id as cashier_id',
                'users.name as staff_name'
            )
            ->selectRaw('COUNT(transactions.id) as transaction_count')
            ->selectRaw('SUM(transactions.palay_weight_kg) as total_palay_weight')
            ->selectRaw('SUM(transactions.subtotal) as subtotal')
            ->selectRaw('SUM(transactions.other_charges) as other_charges')
            ->selectRaw('SUM(transactions.discount) as discount')
            ->selectRaw('SUM(transactions.total_amount) as total_amount')
            ->selectRaw('COALESCE(deliveries.client_id, -deliveries.id) as client_group_id')
            ->groupBy(
                'client_group_id',
                'deliveries.client_id',
                'deliveries.client_name',
                'transactions.milling_type',
                'transactions.user_id',
                'users.name'
            )
            ->orderBy('deliveries.client_name')
            ->orderBy('transactions.milling_type')
            ->get();

        $paymentBreakdown = (clone $baseQuery)
            ->selectRaw("LOWER(COALESCE(transactions.payment_method, 'unknown')) as payment_method")
            ->selectRaw('COUNT(transactions.id) as transaction_count')
            ->selectRaw('SUM(transactions.total_amount) as total_amount')
            ->groupBy('payment_method')
            ->orderBy('payment_method')
            ->get();

        return [
            // Keep `date` for older report/export views that still expect it.
            'date' => $fromDate,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'groupedSales' => $groupedSales,
            'paymentBreakdown' => $paymentBreakdown,
            'totalIncome' => (float) (clone $baseQuery)->sum('transactions.total_amount'),
            'menudoIncome' => (float) (clone $baseQuery)
                ->where('transactions.milling_type', 'menudo')->sum('transactions.total_amount'),
            'commercialIncome' => (float) (clone $baseQuery)
                ->where('transactions.milling_type', 'commercial')->sum('transactions.total_amount'),
            'totalTransactions' => (clone $baseQuery)->count('transactions.id'),
            'totalPalayWeight' => (float) (clone $baseQuery)->sum('transactions.palay_weight_kg'),
            'subtotal' => (float) (clone $baseQuery)->sum('transactions.subtotal'),
            'otherCharges' => (float) (clone $baseQuery)->sum('transactions.other_charges'),
            'discounts' => (float) (clone $baseQuery)->sum('transactions.discount'),
            'cashIncome' => $this->paymentTotal($paymentBreakdown, 'cash'),
            'digitalIncome' => (float) $paymentBreakdown
                ->whereIn('payment_method', ['gcash', 'maya'])
                ->sum('total_amount'),
        ];
    }

    private function baseQuery(string $fromDate, string $toDate, int|string|null $staffId): Builder
    {
        $query = Transaction::query()
            ->join('deliveries', 'transactions.delivery_id', '=', 'deliveries.id')
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->where('transactions.payment_status', 'paid')
            ->whereRaw(
                'DATE(COALESCE(transactions.paid_at, transactions.created_at)) BETWEEN ? AND ?',
                [$fromDate, $toDate]
            );

        if ($staffId !== null && $staffId !== '' && $staffId !== 'all') {
            $query->where('transactions.user_id', (int) $staffId);
        }

        return $query;
    }

    private function paymentTotal(Collection $breakdown, string $method): float
    {
        return (float) ($breakdown->firstWhere('payment_method', $method)->total_amount ?? 0);
    }
}
