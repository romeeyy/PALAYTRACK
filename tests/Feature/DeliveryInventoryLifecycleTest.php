<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\InventoryLog;
use App\Models\RiceType;
use App\Models\Transaction;
use App\Services\DeliveryInventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryInventoryLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_follows_delivery_milling_and_paid_claim_lifecycle(): void
    {
        $riceType = RiceType::create([
            'name' => 'Test White',
            'recovery_rate' => 70,
            'status' => 'active',
        ]);

        $delivery = Delivery::create([
            'delivery_id' => 'DEL-TEST01',
            'queue_number' => 1,
            'client_name' => 'Test Client',
            'contact_number' => '09123456789',
            'rice_type_id' => $riceType->id,
            'sacks' => 10,
            'palay_weight' => 600,
            'recovery_rate' => 70,
            'estimated_rice' => 420,
            'status' => 'processing',
            'delivered_at' => now(),
        ]);

        $delivery->inventoryLogs()->create([
            'stock_category' => 'palay',
            'type' => 'in',
            'quantity' => 600,
            'remarks' => 'Palay added from recorded delivery',
            'logged_at' => now(),
        ]);

        $service = app(DeliveryInventoryService::class);
        $service->complete($delivery, 420);

        $this->assertSame(0.0, $this->balance('palay'));
        $this->assertSame(420.0, $this->balance('milled_rice'));
        $this->assertNotNull($delivery->fresh()->completed_at);

        Transaction::create([
            'delivery_id' => $delivery->id,
            'milling_type' => 'menudo',
            'total_amount' => 1200,
            'amount_received' => 1200,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $delivery->notifications()->create([
            'method' => 'call',
            'source' => 'manual',
            'notification_status' => 'reached',
            'notified_at' => now(),
        ]);

        $service->claim($delivery->fresh());
        $service->claim($delivery->fresh());

        $this->assertSame(0.0, $this->balance('milled_rice'));
        $this->assertSame(1, InventoryLog::where('stock_category', 'milled_rice')
            ->where('type', 'out')->count());
        $this->assertSame('claimed', $delivery->fresh()->status);
        $this->assertNotNull($delivery->fresh()->claimed_at);
    }

    private function balance(string $category): float
    {
        return (float) InventoryLog::where('stock_category', $category)
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as total")
            ->value('total');
    }
}
