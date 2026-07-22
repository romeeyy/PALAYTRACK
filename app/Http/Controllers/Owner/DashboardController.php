<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\InventoryLog;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $totalPalayInventory = InventoryLog::where('stock_category', 'palay')
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as total")
            ->value('total');

        $totalMilledRiceInventory = InventoryLog::where('stock_category', 'milled_rice')
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as total")
            ->value('total');

        $pendingDeliveries = Delivery::where('status', 'pending')->count();
        $completedDeliveries = Delivery::where('status', 'completed')->count();

        $production = Delivery::join('rice_types', 'deliveries.rice_type_id', '=', 'rice_types.id')
            ->select(
                'rice_types.name as rice_type',
                DB::raw('SUM(deliveries.actual_rice) as total')
            )
            ->whereNotNull('deliveries.actual_rice')
            ->whereBetween('deliveries.completed_at', [
                Carbon::today()->startOfMonth(),
                Carbon::today()->endOfMonth(),
            ])
            ->groupBy('rice_types.name')
            ->get();

        $productionLabels = $production->pluck('rice_type');
        $productionData = $production->pluck('total');

        $deliveryLabels = ['Pending', 'Processing', 'Completed', 'Claimed'];
        $monthlyDeliveries = Delivery::whereBetween('created_at', [
            Carbon::today()->startOfMonth(),
            Carbon::today()->endOfMonth(),
        ])->get();

        $deliveryData = [
            $monthlyDeliveries->where('status', 'pending')->count(),
            $monthlyDeliveries->where('status', 'processing')->count(),
            $monthlyDeliveries->where('status', 'completed')->count(),
            $monthlyDeliveries->where('status', 'claimed')->count(),
        ];

        $monthStart = Carbon::today()->startOfMonth();
        $monthEnd = Carbon::today()->endOfMonth();

        $transactions = Transaction::where('payment_status', 'paid')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->get();

        $menudoCount = $transactions->where('milling_type', 'menudo')->count();
        $commercialCount = $transactions->where('milling_type', 'commercial')->count();
        $totalCount = $menudoCount + $commercialCount;

        $menudoPercent = $totalCount > 0 ? round(($menudoCount / $totalCount) * 100) : 0;
        $commercialPercent = $totalCount > 0 ? round(($commercialCount / $totalCount) * 100) : 0;

        $menudoIncome = $transactions->where('milling_type', 'menudo')->sum('total_amount');
        $commercialIncome = $transactions->where('milling_type', 'commercial')->sum('total_amount');
        $monthlyIncome = $transactions->sum('total_amount');

        if ($totalCount === 0) {
            $mostUsed = 'No data';
            $mostUsedPercent = 0;
        } else {
            $mostUsed = $menudoCount >= $commercialCount ? 'Menudo' : 'Commercial';
            $mostUsedPercent = $menudoCount >= $commercialCount ? $menudoPercent : $commercialPercent;
        }

        $revenueTrendLabels = [];
        $menudoRevenueTrend = [];
        $commercialRevenueTrend = [];
        $totalRevenueTrend = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $revenueTrendLabels[] = $date->format('M d');

            $dailyMenudo = Transaction::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->where('milling_type', 'menudo')
                ->sum('total_amount');

            $dailyCommercial = Transaction::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->where('milling_type', 'commercial')
                ->sum('total_amount');

            $menudoRevenueTrend[] = $dailyMenudo;
            $commercialRevenueTrend[] = $dailyCommercial;
            $totalRevenueTrend[] = $dailyMenudo + $dailyCommercial;
        }

        $sevenDayIncome = array_sum($totalRevenueTrend);
        $todayIncome = Transaction::where('payment_status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');
        $monthlyTransactions = $transactions->count();

        return view('owner.dashboard', compact(
            'totalPalayInventory',
            'totalMilledRiceInventory',
            'pendingDeliveries',
            'completedDeliveries',
            'productionLabels',
            'productionData',
            'deliveryLabels',
            'deliveryData',
            'menudoPercent',
            'commercialPercent',
            'menudoIncome',
            'commercialIncome',
            'monthlyIncome',
            'sevenDayIncome',
            'mostUsed',
            'mostUsedPercent',
            'revenueTrendLabels',
            'menudoRevenueTrend',
            'commercialRevenueTrend',
            'totalRevenueTrend',
            'todayIncome',
            'monthlyTransactions'
        ));
    }
}
