<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MillingFeeHistory;
use App\Models\Setting;
use App\Models\SystemSettingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    private function ownerOnly(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'owner', 403);
    }

    public function index()
    {
        $this->ownerOnly();
        return view('owner.settings', [
            'smsEnabled' => Setting::getValue('sms_enabled', '0'),
            'menudoFee' => Setting::getValue('menudo_fee', '2.00'),
            'commercialFee' => Setting::getValue('commercial_fee', '1.50'),
            'feeHistories' => MillingFeeHistory::latest('changed_at')->take(5)->get(),
            'latestSmsHistory' => SystemSettingHistory::with('user')->where('key', 'sms_enabled')->latest('changed_at')->first(),
        ]);
    }

    public function updateSms(Request $request)
    {
        $this->ownerOnly();
        $validated = $request->validate(['sms_enabled' => ['required', 'in:0,1']]);
        $old = (string) Setting::getValue('sms_enabled', '0');
        $new = (string) $validated['sms_enabled'];

        if ($old !== $new) {
            DB::transaction(function () use ($old, $new): void {
                Setting::setValue('sms_enabled', $new);
                SystemSettingHistory::create([
                    'key' => 'sms_enabled', 'old_value' => $old, 'new_value' => $new,
                    'changed_by' => Auth::id(), 'changed_at' => now(),
                ]);
            });
        }

        return back()->with('success', $old === $new ? 'SMS setting is already up to date.' : 'SMS notification setting updated successfully.');
    }

    public function updateFees(Request $request)
    {
        $this->ownerOnly();
        $validated = $request->validate([
            'menudo_fee' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:100'],
            'commercial_fee' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:100'],
        ]);
        $old = [
            'menudo' => number_format((float) Setting::getValue('menudo_fee', 2), 2, '.', ''),
            'commercial' => number_format((float) Setting::getValue('commercial_fee', 1.5), 2, '.', ''),
        ];
        $new = [
            'menudo' => number_format((float) $validated['menudo_fee'], 2, '.', ''),
            'commercial' => number_format((float) $validated['commercial_fee'], 2, '.', ''),
        ];

        DB::transaction(function () use ($old, $new): void {
            foreach (['menudo', 'commercial'] as $type) {
                if ($old[$type] === $new[$type]) continue;
                MillingFeeHistory::create([
                    'milling_type' => $type, 'old_fee' => $old[$type], 'new_fee' => $new[$type],
                    'changed_by' => Auth::id(), 'changed_at' => now(),
                ]);
                Setting::setValue($type . '_fee', $new[$type]);
            }
        });

        $changed = $old !== $new;
        return back()->with('success', $changed ? 'Milling fees updated. New rates apply to future deliveries only.' : 'Milling fees are already up to date.');
    }

    public function feeHistory(Request $request)
    {
        $this->ownerOnly();
        $validated = $request->validate(['milling_type' => ['nullable', 'in:all,menudo,commercial']]);
        $type = $validated['milling_type'] ?? 'all';
        $query = MillingFeeHistory::with('user')->latest('changed_at');
        if ($type !== 'all') $query->where('milling_type', $type);
        $histories = $query->paginate(10)->withQueryString();
        return view('owner.milling-fee-history', compact('histories', 'type'));
    }
}
