<?php

namespace App\Services;

use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeliveryInventoryService
{
    public function complete(Delivery $delivery, float $actualRice): Delivery
    {
        return DB::transaction(function () use ($delivery, $actualRice): Delivery {
            $delivery = Delivery::query()->lockForUpdate()->findOrFail($delivery->id);

            if ($actualRice <= 0 || $actualRice > (float) $delivery->palay_weight) {
                throw ValidationException::withMessages([
                    'actual_rice' => 'Actual rice must be greater than zero and cannot exceed palay weight.',
                ]);
            }

            $milledIn = $delivery->inventoryLogs()
                ->where('stock_category', 'milled_rice')->where('type', 'in')->exists();
            $palayOut = $delivery->inventoryLogs()
                ->where('stock_category', 'palay')->where('type', 'out')->exists();

            if ($delivery->status === 'completed' && $milledIn && $palayOut) {
                return $delivery;
            }

            if ($delivery->status !== 'processing') {
                throw ValidationException::withMessages([
                    'actual_rice' => 'Only processing deliveries can be completed.',
                ]);
            }

            if ($milledIn || $palayOut) {
                throw ValidationException::withMessages([
                    'inventory' => 'Incomplete inventory movement detected. Please contact the owner.',
                ]);
            }

            if (!$delivery->inventoryLogs()
                ->where('stock_category', 'palay')->where('type', 'in')->exists()) {
                throw ValidationException::withMessages([
                    'inventory' => 'The original palay stock entry is missing.',
                ]);
            }

            $delivery->actual_rice = $actualRice;
            $delivery->status = 'completed';
            $delivery->save();

            $delivery->inventoryLogs()->create([
                'stock_category' => 'milled_rice',
                'type' => 'in',
                'quantity' => $actualRice,
                'remarks' => 'Added from completed delivery',
                'logged_at' => now(),
            ]);

            $delivery->inventoryLogs()->create([
                'stock_category' => 'palay',
                'type' => 'out',
                'quantity' => $delivery->palay_weight,
                'remarks' => 'Palay used during milling',
                'logged_at' => now(),
            ]);

            return $delivery->refresh();
        });
    }

    public function claim(Delivery $delivery): Delivery
    {
        return DB::transaction(function () use ($delivery): Delivery {
            $delivery = Delivery::query()
                ->with('transaction')
                ->lockForUpdate()
                ->findOrFail($delivery->id);

            $milledOut = $delivery->inventoryLogs()
                ->where('stock_category', 'milled_rice')->where('type', 'out')->exists();

            if ($delivery->status === 'claimed' && $milledOut) {
                return $delivery;
            }

            if ($delivery->status !== 'completed') {
                throw ValidationException::withMessages([
                    'claim' => 'Only completed deliveries can be marked as claimed.',
                ]);
            }

            if (!$delivery->transaction || $delivery->transaction->payment_status !== 'paid') {
                throw ValidationException::withMessages([
                    'claim' => 'Full payment is required before releasing the milled rice.',
                ]);
            }

            if (!$delivery->inventoryLogs()
                ->where('stock_category', 'milled_rice')->where('type', 'in')->exists()) {
                throw ValidationException::withMessages([
                    'inventory' => 'The completed milled-rice stock entry is missing.',
                ]);
            }

            if ($milledOut) {
                throw ValidationException::withMessages([
                    'inventory' => 'Milled rice was already released for this delivery.',
                ]);
            }

            $delivery->inventoryLogs()->create([
                'stock_category' => 'milled_rice',
                'type' => 'out',
                'quantity' => $delivery->actual_rice,
                'remarks' => 'Milled rice released to customer',
                'logged_at' => now(),
            ]);

            $delivery->status = 'claimed';
            $delivery->save();

            return $delivery->refresh();
        });
    }
}
