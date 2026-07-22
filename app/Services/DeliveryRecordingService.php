<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeliveryRecordingService
{
    public function __construct(private ClientService $clients) {}

    public function record(array $data, int $userId): Delivery
    {
        return DB::transaction(function () use ($data, $userId): Delivery {
            $recordedAt = now();
            $queueDate = $recordedAt->toDateString();

            DB::table('delivery_queue_counters')->insertOrIgnore([
                'queue_date' => $queueDate,
                'last_number' => 0,
                'created_at' => $recordedAt,
                'updated_at' => $recordedAt,
            ]);

            $counter = DB::table('delivery_queue_counters')
                ->where('queue_date', $queueDate)
                ->lockForUpdate()
                ->first();
            $queueNumber = ((int) $counter->last_number) + 1;

            DB::table('delivery_queue_counters')
                ->where('queue_date', $queueDate)
                ->update(['last_number' => $queueNumber, 'updated_at' => $recordedAt]);

            $riceType = RiceType::where('status', 'active')->findOrFail($data['rice_type_id']);
            $client = $this->clients->resolve($data['client_name'], $data['contact_number']);
            $name = $this->clients->normalizeName($data['client_name']);
            $contact = $this->clients->normalizeContact($data['contact_number']);
            $recoveryRate = (float) $riceType->recovery_rate;
            $millingType = ($client->client_type === 'commercial' || (float) $data['palay_weight'] >= 500)
                ? 'commercial' : 'menudo';
            $feePerKg = (float) Setting::getValue($millingType . '_fee', $millingType === 'commercial' ? 1.50 : 2.00);

            $delivery = Delivery::create([
                'staff_id' => $userId,
                'delivery_id' => 'DEL-' . strtoupper(Str::random(6)),
                'queue_number' => $queueNumber,
                'queue_date' => $queueDate,
                'client_id' => $client->id,
                'client_name' => $name,
                'contact_number' => $contact,
                'client_type_at_delivery' => $client->client_type,
                'milling_type_at_delivery' => $millingType,
                'milling_fee_per_kg_at_delivery' => $feePerKg,
                'rice_type_id' => $riceType->id,
                'sacks' => $data['sacks'],
                'palay_weight' => $data['palay_weight'],
                'recovery_rate' => $recoveryRate,
                'estimated_rice' => $data['palay_weight'] * ($recoveryRate / 100),
                'actual_rice' => null,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'delivered_at' => $recordedAt,
            ]);

            $delivery->inventoryLogs()->create([
                'stock_category' => 'palay', 'type' => 'in',
                'quantity' => $delivery->palay_weight,
                'remarks' => 'Palay added from recorded delivery', 'logged_at' => now(),
            ]);

            return $delivery;
        });
    }
}
