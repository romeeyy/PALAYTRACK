<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNotification;
use App\Models\RiceType;
use App\Services\SmsService;
use App\Services\DeliveryRecordingService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DeliveryController extends Controller
{
    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $riceTypes = RiceType::where('status', 'active')->latest()->get();

        return view('staff.record-delivery', compact('riceTypes'));
    }

    public function store(Request $request, DeliveryRecordingService $recordingService)
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'contact_number' => [
                'required',
                'regex:/^(09|\+639|639)\d{9}$/'
            ],
            'rice_type_id' => 'required|exists:rice_types,id',
            'sacks' => 'required|numeric|min:0.5|multiple_of:0.5',
            'palay_weight' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:1000',
        ], [
            'contact_number.regex' => 'Enter a valid Philippine mobile number (e.g., 09123456789).',
            'sacks.multiple_of' => 'Number of sacks must be a whole or half sack (example: 2 or 2.5).',
            'sacks.min' => 'Number of sacks must be at least half a sack (0.5).',
        ]);
        $delivery = $recordingService->record($validated, Auth::id());

        return redirect()->route('staff.claim-stub', $delivery->id)
            ->with('success', 'Delivery recorded successfully.');
    }

    public function resendSms(int $id, SmsService $smsService)
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $notification = DeliveryNotification::with('delivery')->findOrFail($id);

        if ($notification->notification_status !== 'failed' || $notification->method !== 'text') {
            return back()->with('error', 'Only failed SMS notifications can be resent.');
        }

        if ((string) Setting::getValue('sms_enabled', '0') !== '1') {
            return back()->with('error', 'Enable automatic SMS in Settings before resending.');
        }

        if ($notification->delivery->status !== 'completed') {
            return back()->with('error', 'SMS can only be resent while the delivery is completed and ready for pickup.');
        }

        $delivery = $notification->delivery;

        $message = "Your rice is ready for pickup at JK Diez Rice Mill. Please present your claim stub upon claiming. Thank you!";

        $smsResult = $smsService->send($delivery->contact_number, $message);

        $smsStatus = strtolower($smsResult['status'] ?? 'failed');
        $sendSucceeded = ($smsResult['success'] ?? false)
            && !in_array($smsStatus, ['failed', 'error'], true);

        if ($sendSucceeded) {
            $notification->update([
                'notification_status' => 'sent',
                'notified_at' => now(),
                'remarks' => $smsStatus === 'simulated'
                    ? 'SMS resend simulated successfully (no live message was sent).'
                    : 'SMS resent successfully via Semaphore',
            ]);

            return back()->with('success', 'SMS resent successfully.');
        }

        $notification->update([
            'notification_status' => 'failed',
            'notified_at' => null,
            'remarks' => 'Message Failed',
        ]);

        return back()->with('error', 'SMS resend failed.');
    }
}
