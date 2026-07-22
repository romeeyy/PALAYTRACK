<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\DeliveryNotification;
use App\Models\RiceType;
use App\Models\Setting;
use App\Services\SmsService;
use App\Services\DeliveryInventoryService;
use App\Services\DeliveryRecordingService;
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

    public function updateStatus(Request $request, Delivery $delivery)
    {
        if (!Auth::check() || Auth::user()->role !== 'staff') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed',
        ]);

        $oldStatus = $delivery->status;

        if ($validated['status'] === 'completed') {
            app(DeliveryInventoryService::class)
                ->complete($delivery, (float) $delivery->actual_rice);
        } else {
            $delivery->update(['status' => $validated['status']]);
        }

        if ($oldStatus !== 'completed' && $validated['status'] === 'completed') {
            $smsEnabled = Setting::getValue('sms_enabled', '0');

            if ($smsEnabled === '1') {
                $smsService = new SmsService();

                $message = "Your rice is ready for pickup at JK Diez Rice Mill. Please present your claim stub upon claiming. Thank you!";

                $smsResult = $smsService->send($delivery->contact_number, $message);

                if ($smsResult['success'] ?? false) {
                    DeliveryNotification::create([
                        'delivery_id' => $delivery->id,
                        'method' => 'text',
                        'notification_status' => 'sent',
                        'notified_at' => now(),
                        'remarks' => 'Automatic SMS via Semaphore',
                    ]);

                    return back()->with('success', 'Delivery completed and SMS sent successfully.');
                }

                DeliveryNotification::create([
                    'delivery_id' => $delivery->id,
                    'method' => 'text',
                    'notification_status' => 'failed',
                    'notified_at' => null,
                    'remarks' => 'Message Failed',
                ]);

                return back()->with('warning', 'Delivery completed, but SMS failed to send.');
            }
        }

        return back()->with('success', 'Delivery status updated successfully.');
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

        $delivery = $notification->delivery;

        $message = "Your rice is ready for pickup at JK Diez Rice Mill. Please present your claim stub upon claiming. Thank you!";

        $smsResult = $smsService->send($delivery->contact_number, $message);

        if ($smsResult['success'] ?? false) {
            $notification->update([
                'notification_status' => 'sent',
                'notified_at' => now(),
                'remarks' => 'SMS resent successfully via Semaphore',
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
