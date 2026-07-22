<?php

use App\Exports\ReportsExport;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\PaymentRecordController;
use App\Http\Controllers\Owner\StaffAccountController;
use App\Http\Controllers\Owner\DeliveryRecordingController;
use App\Http\Controllers\Owner\SettingsController;
use App\Http\Controllers\Owner\ProfileController;
use App\Http\Controllers\Staff\PosController;
use App\Http\Controllers\Staff\TransactionController;
use App\Models\Delivery;
use App\Models\InventoryLog;
use App\Models\RiceType;
use App\Models\Setting;
use App\Models\User;
use App\Services\SmsService;
use App\Services\DeliveryInventoryService;
use App\Services\DailySalesReportService;
use App\Services\RiceTypeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Staff\DeliveryController;
use App\Models\MillingFeeHistory;
use Carbon\Carbon;
use App\Http\Controllers\Owner\ClientController;
use App\Models\Transaction;
use App\Models\RiceTypeRecoveryRateHistory;

if (!function_exists('autoNotifyDeliveryCompleted')) {
function autoNotifyDeliveryCompleted($delivery)
{
    try {
        logger('AUTO SMS FUNCTION CALLED for delivery ID: ' . $delivery->id);

        $smsEnabled = Setting::getValue('sms_enabled', '0');
        logger('SMS ENABLED VALUE: ' . $smsEnabled);

        if ($smsEnabled !== '1') {
            logger('SMS disabled');
            return;
        }

        $alreadyLogged = $delivery->notifications()
            ->where('method', 'text')
            ->exists();

        if ($alreadyLogged) {
            logger('SMS already logged for delivery ID: ' . $delivery->id);
            return;
        }

        $message = "Hello {$delivery->client_name}, your milled rice is now ready for pickup at JK Diez Rice Mill. Please present your claim stub upon claiming. Thank you!";

        $smsService = app(SmsService::class);
        $result = $smsService->send($delivery->contact_number, $message);

        logger('SEMAPHORE RESULT:', $result);

        $semaphoreStatus = strtolower($result['status'] ?? 'failed');

        if ($semaphoreStatus === 'pending' || $semaphoreStatus === 'queued' || $semaphoreStatus === 'sent') {
            $notificationStatus = 'sent';
        } else {
            $notificationStatus = 'failed';
        }

        $delivery->notifications()->create([
            'method' => 'text',
            'notification_status' => $notificationStatus,
            'remarks' => $notificationStatus === 'failed'
                ? 'Message Failed'
                : $message,
            'notified_at' => $notificationStatus === 'sent' ? now() : null,
        ]);
        logger('Notification log created for delivery ID: ' . $delivery->id);
    } catch (\Throwable $e) {
        logger('AUTO SMS ERROR: ' . $e->getMessage());

        $delivery->notifications()->create([
            'method' => 'text',
            'notification_status' => 'failed',
            'remarks' => 'Message Failed',
            'notified_at' => null,
        ]);
    }
}
}

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }

    $request->session()->regenerate();

    $user = Auth::user();

    if (!$user->is_active) {
        Auth::logout();

        return back()->withErrors([
            'email' => 'This account is inactive.',
        ]);
    }

    if ($user->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }

    return redirect()->route('staff.dashboard');
})->name('login.submit');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| OWNER ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('owner')->group(function () {


    Route::get('/profile', [ProfileController::class, 'edit'])->name('owner.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('owner.profile.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('owner.dashboard');

    Route::post('/sms-settings', [SettingsController::class, 'updateSms'])->name('owner.sms-settings');
    Route::get('/settings', [SettingsController::class, 'index'])->name('owner.settings');

    Route::post('/milling-fee-settings', function (Request $request) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $request->validate([
            'menudo_fee' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:100'],
            'commercial_fee' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:100'],
        ]);

        // 🔍 Get current values (OLD)
        $oldMenudo = Setting::getValue('menudo_fee', 0);
        $oldCommercial = Setting::getValue('commercial_fee', 0);

        $newMenudo = $request->menudo_fee;
        $newCommercial = $request->commercial_fee;

        $userId = Auth::id();

        // 🧠 If Menudo changed → log history
        DB::transaction(function () use ($oldMenudo, $oldCommercial, $newMenudo, $newCommercial, $userId) {
        if (number_format((float) $oldMenudo, 2, '.', '') !== number_format((float) $newMenudo, 2, '.', '')) {
            MillingFeeHistory::create([
                'milling_type' => 'menudo',
                'old_fee' => $oldMenudo,
                'new_fee' => $newMenudo,
                'changed_by' => $userId,
                'changed_at' => now(),
            ]);
        }

        // 🧠 If Commercial changed → log history
        if (number_format((float) $oldCommercial, 2, '.', '') !== number_format((float) $newCommercial, 2, '.', '')) {
            MillingFeeHistory::create([
                'milling_type' => 'commercial',
                'old_fee' => $oldCommercial,
                'new_fee' => $newCommercial,
                'changed_by' => $userId,
                'changed_at' => now(),
            ]);
        }

        // 💾 Update settings (NEW values)
        Setting::setValue('menudo_fee', $newMenudo);
        Setting::setValue('commercial_fee', $newCommercial);
        });

        return redirect()->back()->with('success', 'Milling fees updated. New rates apply to future deliveries only.');
    })->name('owner.milling-fee-settings');
    Route::get('/milling-fee-history', function (Request $request) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $request->validate(['milling_type' => ['nullable', 'in:all,menudo,commercial']]);
        $type = $request->milling_type ?? 'all';

        $query = MillingFeeHistory::with('user')->latest('changed_at');

        if ($type !== 'all') {
            $query->where('milling_type', $type);
        }

        $histories = $query->paginate(10)->withQueryString();

        return view('owner.milling-fee-history', compact('histories', 'type'));
    })->name('owner.milling-fee-history');

    Route::get('/recovery-rate-history', function () {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $histories = RiceTypeRecoveryRateHistory::with('riceType')
            ->latest('changed_at')
            ->paginate(15);

        return view('owner.recovery-rate-history', compact('histories'));
    })->name('owner.recovery-rate-history');

    Route::get('/payment-records', [PaymentRecordController::class, 'index'])
        ->name('owner.payment-records');

    Route::get('/receipt/{delivery}', function ($delivery) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $delivery = Delivery::with(['riceType', 'transaction'])->findOrFail($delivery);
        $transaction = $delivery->transaction;

        if (!$transaction) {
            return redirect()->back()->withErrors([
                'receipt' => 'No transaction found for this delivery.'
            ]);
        }

        return view('owner.receipt', compact('delivery', 'transaction'));
    })->name('owner.receipt');

    Route::get('/deliveries', function (Request $request) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,processing,completed,claimed'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'view' => ['nullable', 'in:active,history'],
        ]);

        $selectedView = $validated['view'] ?? 'active';
        $activeCount = Delivery::whereIn('status', ['pending', 'processing', 'completed'])->count();
        $claimedCount = Delivery::where('status', 'claimed')->count();
        $query = Delivery::query()->with(['riceType', 'staff']);

        if ($selectedView === 'history') {
            $query->where('status', 'claimed');
        } else {
            $query->whereIn('status', ['pending', 'processing', 'completed']);
        }

        if (!empty($validated['search'])) {
            $search = trim($validated['search']);
            $query->where(function ($deliveryQuery) use ($search) {
                $deliveryQuery
                    ->where('client_name', 'like', "%{$search}%")
                    ->orWhere('delivery_id', 'like', "%{$search}%");
            });
        }

        if (!empty($validated['status'])
            && (($selectedView === 'history' && $validated['status'] === 'claimed')
                || ($selectedView === 'active' && $validated['status'] !== 'claimed'))) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['date'])) {
            $query->whereDate('delivered_at', $validated['date']);
        }

        // Active work is shown first in FCFS order. Finished records are newest first.
        $deliveries = $query
            ->orderByRaw("CASE WHEN status IN ('pending', 'processing') THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN status IN ('pending', 'processing') THEN delivered_at END ASC")
            ->orderByRaw("CASE WHEN status IN ('pending', 'processing') THEN queue_number END ASC")
            ->orderByDesc('delivered_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
        return view('owner.deliveries', compact(
            'deliveries', 'selectedView', 'activeCount', 'claimedCount'
        ));
    })->name('owner.deliveries');

    Route::get('/delivery-details/{id}', function ($id) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $delivery = Delivery::with(['riceType', 'staff', 'notifications'])->findOrFail($id);
        return view('owner.delivery-details', compact('delivery'));
    })->name('owner.delivery-details');

    Route::get('/claim-stub/{id}', function ($id) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $delivery = Delivery::with('riceType')->findOrFail($id);
        return view('owner.claim-stub', compact('delivery'));
    })->name('owner.claim-stub');

    Route::get('/record-delivery', function () {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $riceTypes = RiceType::where('status', 'active')->latest()->get();
        return view('owner.record-delivery', compact('riceTypes'));
    })->name('owner.record-delivery');
    Route::post('/record-delivery', [DeliveryRecordingController::class, 'store'])
        ->name('owner.record-delivery.store');

    Route::get('/inventory', function () {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $totalPalay = InventoryLog::where('stock_category', 'palay')
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as total")
            ->value('total');

        $totalMilledRice = InventoryLog::where('stock_category', 'milled_rice')
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as total")
            ->value('total');

        $palayByRiceType = InventoryLog::query()
            ->join('deliveries', 'inventory_logs.delivery_id', '=', 'deliveries.id')
            ->join('rice_types', 'deliveries.rice_type_id', '=', 'rice_types.id')
            ->where('inventory_logs.stock_category', 'palay')
            ->select(
                'rice_types.name as rice_type_name',
                DB::raw("COALESCE(SUM(CASE WHEN inventory_logs.type = 'in' THEN inventory_logs.quantity ELSE -inventory_logs.quantity END), 0) as total_weight")
            )
            ->groupBy('rice_types.name')
            ->get();

        $milledByRiceType = InventoryLog::query()
            ->join('deliveries', 'inventory_logs.delivery_id', '=', 'deliveries.id')
            ->join('rice_types', 'deliveries.rice_type_id', '=', 'rice_types.id')
            ->where('inventory_logs.stock_category', 'milled_rice')
            ->select(
                'rice_types.name as rice_type_name',
                DB::raw("COALESCE(SUM(CASE WHEN inventory_logs.type = 'in' THEN inventory_logs.quantity ELSE -inventory_logs.quantity END), 0) as total_weight")
            )
            ->groupBy('rice_types.name')
            ->get();

        $allRiceTypes = RiceType::select('name')->get();

        $combinedInventory = $allRiceTypes->map(function ($riceType) use ($palayByRiceType, $milledByRiceType) {
            $palay = $palayByRiceType->firstWhere('rice_type_name', $riceType->name);
            $milled = $milledByRiceType->firstWhere('rice_type_name', $riceType->name);

            $palayWeight = $palay ? (float) $palay->total_weight : 0;
            $milledWeight = $milled ? (float) $milled->total_weight : 0;

            return [
                'rice_type_name' => $riceType->name,
                'palay_weight' => $palayWeight,
                'milled_weight' => $milledWeight,
                'total_weight' => $palayWeight + $milledWeight,
            ];
        });

        return view('owner.inventory', compact(
            'totalPalay',
            'totalMilledRice',
            'palayByRiceType',
            'milledByRiceType',
            'combinedInventory'
        ));
    })->name('owner.inventory');

    Route::get('/reports', function (Request $request, DailySalesReportService $reportService) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $request->validate([
            'date' => 'nullable|date',
            'staff_id' => 'nullable',
        ]);

        $date = $request->date ?: now()->toDateString();
        $staffId = $request->staff_id ?: 'all';

        $report = $reportService->generate($date, $staffId);

        $staffUsers = User::where('role', 'staff')->orderBy('name')->get();

        return view('owner.reports', array_merge($report, compact(
            'date',
            'staffId',
            'staffUsers'
        )));
    })->name('owner.reports');

    Route::get('/rice-types', function () {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $riceTypes = RiceType::latest()->get();
        return view('owner.rice-types', compact('riceTypes'));
    })->name('owner.rice-types');

    Route::get('/add-rice-type', function () {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        return view('owner.add-rice-type');
    })->name('owner.add-rice-type');

    Route::post('/add-rice-type', function (Request $request, RiceTypeService $riceTypeService) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rice_types,name',
            'recovery_rate' => 'required|numeric|min:0.01|max:100',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $riceTypeService->create($validated);

        return redirect('/owner/rice-types')->with('success', 'Rice type added successfully.');
    })->name('owner.add-rice-type.store');

    Route::get('/edit-rice-type/{id}', function ($id) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $riceType = RiceType::findOrFail($id);
        return view('owner.edit-rice-type', compact('riceType'));
    })->name('owner.edit-rice-type');

    Route::post('/edit-rice-type/{id}', function (Request $request, $id, RiceTypeService $riceTypeService) {
        if (!Auth::check() || Auth::user()->role !== 'owner') {
            return redirect()->route('login');
        }

        $riceType = RiceType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rice_types,name,' . $riceType->id,
            'recovery_rate' => 'required|numeric|min:0.01|max:100',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $riceTypeService->update($riceType, $validated, Auth::user());

        return redirect('/owner/rice-types')->with('success', 'Rice type updated successfully.');
    })->name('owner.edit-rice-type.update');
    Route::get('/clients', [ClientController::class, 'index'])
        ->name('owner.clients');

    Route::post('/clients/{client}/update-type', [ClientController::class, 'updateType'])
        ->name('owner.clients.update-type');
    Route::get('/clients/history', [ClientController::class, 'history'])->name('owner.clients.history');
    Route::post('/clients/{client}/update', [ClientController::class, 'update'])->name('owner.clients.update');

    Route::get('/staff-accounts', [StaffAccountController::class, 'index'])->name('owner.staff-accounts');
    Route::get('/staff-accounts/create', [StaffAccountController::class, 'create'])->name('owner.staff-accounts.create');
    Route::post('/staff-accounts', [StaffAccountController::class, 'store'])->name('owner.staff-accounts.store');
    Route::get('/staff-accounts/history', [StaffAccountController::class, 'history'])->name('owner.staff-accounts.history');
    Route::get('/staff-accounts/{id}/edit', [StaffAccountController::class, 'edit'])->name('owner.staff-accounts.edit');
    Route::post('/staff-accounts/{id}/update', [StaffAccountController::class, 'update'])->name('owner.staff-accounts.update');
    Route::post('/staff-accounts/{id}/toggle-status', [StaffAccountController::class, 'toggleStatus'])->name('owner.staff-accounts.toggle-status');
});

Route::get('/reports', function (Request $request) {
    if (!Auth::check() || Auth::user()->role !== 'owner') {
        return redirect()->route('login');
    }

    $from = $request->start_date ?: now()->startOfMonth()->toDateString();
    $to = $request->end_date ?: now()->toDateString();
    $monthly = $request->monthly_summary ?: '';
    $riceTypeId = $request->rice_type_id ?: '';
    $status = $request->status ?: '';

    if ($monthly === 'last_month') {
        $from = now()->subMonth()->startOfMonth()->toDateString();
        $to = now()->subMonth()->endOfMonth()->toDateString();
    } elseif ($monthly === 'this_month') {
        $from = now()->startOfMonth()->toDateString();
        $to = now()->toDateString();
    } elseif ($monthly === 'quarterly') {
        $from = now()->firstOfQuarter()->toDateString();
        $to = now()->toDateString();
    } elseif ($monthly === 'yearly') {
        $from = now()->startOfYear()->toDateString();
        $to = now()->toDateString();
    }

    $deliveriesQuery = Delivery::with('riceType')
        ->whereDate('delivered_at', '>=', $from)
        ->whereDate('delivered_at', '<=', $to);

    if (!empty($riceTypeId)) {
        $deliveriesQuery->where('rice_type_id', $riceTypeId);
    }

    if (!empty($status)) {
        $deliveriesQuery->where('status', $status);
    }

    $deliveries = (clone $deliveriesQuery)
        ->latest('delivered_at')
        ->get();

    $totalPalayReceived = (clone $deliveriesQuery)->sum('palay_weight');
    $totalRiceProduced = (clone $deliveriesQuery)->whereNotNull('actual_rice')->sum('actual_rice');
    $completedTransactions = (clone $deliveriesQuery)->where('status', 'completed')->count();
    $claimedTransactions = (clone $deliveriesQuery)->where('status', 'claimed')->count();

    $statusCounts = [
        'pending' => (clone $deliveriesQuery)->where('status', 'pending')->count(),
        'processing' => (clone $deliveriesQuery)->where('status', 'processing')->count(),
        'completed' => (clone $deliveriesQuery)->where('status', 'completed')->count(),
        'claimed' => (clone $deliveriesQuery)->where('status', 'claimed')->count(),
    ];

    $riceTypeDistribution = (clone $deliveriesQuery)
        ->join('rice_types', 'deliveries.rice_type_id', '=', 'rice_types.id')
        ->select(
            'rice_types.name as rice_type_name',
            DB::raw('SUM(deliveries.palay_weight) as total_weight')
        )
        ->groupBy('rice_types.name')
        ->get();

    $riceTypeLabels = $riceTypeDistribution->pluck('rice_type_name');
    $riceTypeData = $riceTypeDistribution->pluck('total_weight');

    $statusLabels = ['Pending', 'Processing', 'Completed', 'Claimed'];
    $statusData = [
        $statusCounts['pending'],
        $statusCounts['processing'],
        $statusCounts['completed'],
        $statusCounts['claimed'],
    ];

    $riceTypes = RiceType::where('status', 'active')->orderBy('name')->get();

    return view('owner.reports', compact(
        'from',
        'to',
        'monthly',
        'riceTypeId',
        'status',
        'deliveries',
        'totalPalayReceived',
        'totalRiceProduced',
        'completedTransactions',
        'claimedTransactions',
        'riceTypeLabels',
        'riceTypeData',
        'statusLabels',
        'statusData',
        'riceTypes'
    ));
})->name('reports');

Route::get('/reports/export/pdf', function (Request $request, DailySalesReportService $reportService) {
    if (!Auth::check() || Auth::user()->role !== 'owner') {
        return redirect()->route('login');
    }

    $request->validate(['date' => 'nullable|date', 'staff_id' => 'nullable']);
    $date = $request->date ?: now()->toDateString();
    $staffId = $request->staff_id ?: 'all';
    $report = $reportService->generate($date, $staffId);
    $preparedBy = $staffId === 'all'
        ? 'All Staff'
        : (User::find((int) $staffId)?->name ?? 'Staff');

    return Pdf::loadView('owner.daily-sales-report-pdf', array_merge($report, compact('preparedBy')))
        ->setPaper('a4', 'landscape')
        ->download('palaytrack-daily-sales-' . $date . '.pdf');
})->name('owner.reports.export.pdf');

Route::get('/reports/export/excel', function (Request $request) {
    if (!Auth::check() || Auth::user()->role !== 'owner') {
        return redirect()->route('login');
    }

    $request->validate(['date' => 'nullable|date', 'staff_id' => 'nullable']);
    $date = $request->date ?: now()->toDateString();
    $staffId = $request->staff_id ?: 'all';

    return Excel::download(
        new ReportsExport($date, $staffId),
        'palaytrack-daily-sales-' . $date . '.xlsx'
    );
})->name('owner.reports.export.excel');

/*
|--------------------------------------------------------------------------
| STAFF ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('staff')->group(function () {
    Route::get('/dashboard', function () {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        // Keep the status cards consistent with the Owner dashboard:
        // current statuses of deliveries recorded within the current month.
        $monthlyStatusQuery = Delivery::whereBetween('created_at', [
            Carbon::today()->startOfMonth(),
            Carbon::today()->endOfMonth(),
        ]);

        $pendingCount = (clone $monthlyStatusQuery)->where('status', 'pending')->count();
        $processingCount = (clone $monthlyStatusQuery)->where('status', 'processing')->count();
        $completedCount = (clone $monthlyStatusQuery)->where('status', 'completed')->count();
        $claimedCount = (clone $monthlyStatusQuery)->where('status', 'claimed')->count();

        $trendLabels = [];
        $trendCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $trendLabels[] = $date->format('M d');

            $trendCounts[] = Delivery::whereDate('created_at', $date)->count();
        }

        $todayDeliveries = Delivery::with('riceType')
            ->whereDate('delivered_at', now()->toDateString())
            ->latest()
            ->get();

        $recentCompleted = Delivery::with('riceType')
            ->whereIn('status', ['completed', 'claimed'])
            ->latest()
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'pendingCount',
            'processingCount',
            'completedCount',
            'claimedCount',
            'todayDeliveries',
            'recentCompleted',
            'trendLabels',
            'trendCounts'
        ));
    })->name('staff.dashboard');

    Route::get('/deliveries', function (Request $request) {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $query = Delivery::with(['riceType', 'staff'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deliveries = $query->get();

        return view('staff.deliveries', compact('deliveries'));
    })->name('staff.deliveries');

    Route::get('/delivery-details/{id}', function ($id) {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $delivery = Delivery::with(['riceType', 'notifications', 'transaction', 'staff'])->findOrFail($id);
        return view('staff.delivery-details', compact('delivery'));
    })->name('staff.delivery-details');

    Route::post('/delivery-status/{id}', function (Request $request, $id) {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,claimed'
        ]);

        $delivery = Delivery::findOrFail($id);
        $newStatus = $request->status;
        $currentStatus = $delivery->status;

        if ($newStatus === 'processing' && $currentStatus !== 'pending') {
            return redirect()->back()->withErrors([
                'status' => 'Only pending deliveries can be moved to processing.'
            ]);
        }

        if ($newStatus === 'completed') {
            if ($currentStatus !== 'processing') {
                return redirect()->back()->withErrors([
                    'status' => 'Only processing deliveries can be marked completed.'
                ]);
            }

            if (is_null($delivery->actual_rice)) {
                return redirect()->back()->withErrors([
                    'status' => 'Enter actual rice before marking as completed.'
                ]);
            }
        }

        if ($newStatus === 'claimed' && $currentStatus !== 'completed') {
            return redirect()->back()->withErrors([
                'status' => 'Only completed deliveries can be marked as claimed.'
            ]);
        }

        if ($newStatus === 'completed') {
            app(DeliveryInventoryService::class)->complete($delivery, (float) $delivery->actual_rice);
            autoNotifyDeliveryCompleted($delivery);
        } elseif ($newStatus === 'claimed') {
            app(DeliveryInventoryService::class)->claim($delivery);
        } else {
            $delivery->status = $newStatus;
            $delivery->save();
        }

        return redirect()->back()->with('success', 'Status updated successfully.');
    })->name('staff.delivery-status');

    Route::post('/actual-rice/{id}', function (Request $request, $id) {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $delivery = Delivery::findOrFail($id);

        $request->validate([
            'actual_rice' => 'required|numeric|min:0.01'
        ]);

        if ($delivery->status !== 'processing') {
            return redirect()->back()->withErrors([
                'actual_rice' => 'Actual rice can only be entered when delivery is processing.'
            ]);
        }

        if ($request->actual_rice > $delivery->palay_weight) {
            return redirect()->back()->withErrors([
                'actual_rice' => 'Actual rice cannot exceed palay weight.'
            ]);
        }

        $delivery = app(DeliveryInventoryService::class)
            ->complete($delivery, (float) $request->actual_rice);

        autoNotifyDeliveryCompleted($delivery);

        return redirect()->back()->with('success', 'Actual rice saved, delivery marked as completed, and inventory updated.');
    })->name('staff.actual-rice');

    Route::post('/delivery-notification/{id}', function (Request $request, $id) {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $request->validate([
            'method' => 'required|in:call,text,in_person,sms',
            'notification_status' => 'required|in:sent,reached,failed',
            'remarks' => 'nullable|string',
            'notified_at' => 'required|date',
        ]);

        $delivery = Delivery::findOrFail($id);

        $delivery->notifications()->create([
            'method' => $request->method,
            'notification_status' => $request->notification_status,
            'remarks' => $request->remarks,
            'notified_at' => $request->notified_at,
        ]);

        return redirect()->back()->with('success', 'Notification logged successfully.');
    })->name('staff.delivery-notification');

    Route::post('/claim-delivery/{id}', function ($id) {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $delivery = Delivery::findOrFail($id);
        app(DeliveryInventoryService::class)->claim($delivery);

        return redirect()->back()->with('success', 'Delivery marked as claimed successfully.');
    })->name('staff.claim-delivery');


    Route::get('/record-delivery', [DeliveryController::class, 'create'])->name('staff.record-delivery');
    Route::post('/record-delivery', [DeliveryController::class, 'store'])->name('staff.record-delivery.store');
    Route::get('/inventory', function () {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $totalPalay = InventoryLog::where('stock_category', 'palay')
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as total")
            ->value('total');

        $totalMilledRice = InventoryLog::where('stock_category', 'milled_rice')
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as total")
            ->value('total');

        $palayByRiceType = InventoryLog::query()
            ->join('deliveries', 'inventory_logs.delivery_id', '=', 'deliveries.id')
            ->join('rice_types', 'deliveries.rice_type_id', '=', 'rice_types.id')
            ->where('inventory_logs.stock_category', 'palay')
            ->select(
                'rice_types.name as rice_type_name',
                DB::raw("SUM(CASE WHEN inventory_logs.type = 'in' THEN inventory_logs.quantity ELSE -inventory_logs.quantity END) as total_weight")
            )
            ->groupBy('rice_types.name')
            ->get();

        $milledByRiceType = InventoryLog::query()
            ->join('deliveries', 'inventory_logs.delivery_id', '=', 'deliveries.id')
            ->join('rice_types', 'deliveries.rice_type_id', '=', 'rice_types.id')
            ->where('inventory_logs.stock_category', 'milled_rice')
            ->select(
                'rice_types.name as rice_type_name',
                DB::raw("SUM(CASE WHEN inventory_logs.type = 'in' THEN inventory_logs.quantity ELSE -inventory_logs.quantity END) as total_weight")
            )
            ->groupBy('rice_types.name')
            ->get();

        $inventoryLogs = InventoryLog::with(['delivery.riceType'])
            ->latest()
            ->take(10)
            ->get();

        return view('staff.inventory', compact(
            'totalPalay',
            'totalMilledRice',
            'palayByRiceType',
            'milledByRiceType',
            'inventoryLogs'
        ));
    })->name('staff.inventory');
    Route::get('/reports', function (Request $request, DailySalesReportService $reportService) {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $request->validate(['date' => 'nullable|date']);

        $date = $request->date ?: now()->toDateString();
        $staffId = Auth::id();

        return view('staff.reports', $reportService->generate($date, $staffId));
    })->name('staff.reports');
    Route::get('/claim-stub/{id}', function ($id) {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $delivery = Delivery::with('riceType')->findOrFail($id);
        return view('staff.claim-stub', compact('delivery'));
    })->name('staff.claim-stub');

    Route::get('/pos/{delivery}', [PosController::class, 'create'])->name('staff.pos.create');
    Route::post('/pos/{delivery}', [PosController::class, 'store'])->name('staff.pos.store');
    Route::get('/receipt/{delivery}', [PosController::class, 'receipt'])->name('staff.receipt');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('staff.transactions');

    Route::post('/resend-sms/{id}', [DeliveryController::class, 'resendSms'])
        ->name('staff.resend-sms');
});
