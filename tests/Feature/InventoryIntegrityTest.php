<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\InventoryLog;
use App\Models\RiceType;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventoryIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_unlinked_stock_is_included_in_both_inventory_breakdowns(): void
    {
        foreach (['palay', 'milled_rice'] as $category) {
            foreach (['in' => 100, 'out' => 30] as $type => $quantity) {
                InventoryLog::create([
                    'stock_category' => $category, 'type' => $type,
                    'quantity' => $quantity, 'logged_at' => now(),
                ]);
            }
        }

        foreach (['owner', 'staff'] as $role) {
            $response = $this->actingAs(User::factory()->create(['role' => $role, 'is_active' => true]))
                ->get(route($role . '.inventory'));
            $response->assertOk()->assertSee('Unknown / Unlinked');
            foreach (['palayByRiceType', 'milledByRiceType'] as $key) {
                $response->assertViewHas($key, fn ($rows) => (float) $rows->sum('total_weight') === 70.0);
            }
            $response->assertViewHas('totalPalay', fn ($value) => (float) $value === 70.0)
                ->assertViewHas('totalMilledRice', fn ($value) => (float) $value === 70.0);
            if ($role === 'owner') {
                $response->assertViewHas('combinedInventory', fn ($rows) =>
                    (float) $rows->sum('total_weight') === 140.0);
            }
        }
    }

    public function test_database_blocks_deleting_a_delivery_with_inventory_history(): void
    {
        $riceType = RiceType::create(['name' => 'White', 'recovery_rate' => 65, 'status' => 'active']);
        $delivery = Delivery::create([
            'delivery_id' => 'DEL-SAFE01', 'queue_number' => 1,
            'client_name' => 'Test', 'contact_number' => '09123456789',
            'rice_type_id' => $riceType->id, 'sacks' => 2,
            'palay_weight' => 100, 'recovery_rate' => 65, 'estimated_rice' => 65,
            'status' => 'pending', 'delivered_at' => now(),
        ]);
        $delivery->inventoryLogs()->create([
            'stock_category' => 'palay', 'type' => 'in', 'quantity' => 100, 'logged_at' => now(),
        ]);

        try {
            DB::table('deliveries')->where('id', $delivery->id)->delete();
            $this->fail('Direct deletion must be rejected by the database.');
        } catch (QueryException $exception) {
            $this->assertDatabaseHas('deliveries', ['id' => $delivery->id]);
            $this->assertDatabaseHas('inventory_logs', ['delivery_id' => $delivery->id]);
        }
    }
}
