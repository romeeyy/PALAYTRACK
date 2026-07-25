<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffDeliveriesTest extends TestCase
{
    use RefreshDatabase;

    private User $staff;
    private RiceType $riceType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $this->riceType = RiceType::create([
            'name' => 'White', 'recovery_rate' => 70, 'status' => 'active',
        ]);
    }

    public function test_staff_list_separates_active_queue_from_claimed_history_and_uses_fcfs(): void
    {
        $first = $this->delivery('DEL-FIRST1', 'First Client', 'pending', '2026-07-21 08:00:00', 1);
        $second = $this->delivery('DEL-SECOND', 'Second Client', 'processing', '2026-07-22 08:00:00', 1);
        $this->delivery('DEL-CLAIMD', 'Claimed Client', 'claimed', '2026-07-20 08:00:00', 1);

        $this->actingAs($this->staff)
            ->get(route('staff.deliveries'))
            ->assertOk()
            ->assertSeeInOrder([$first->delivery_id, $second->delivery_id])
            ->assertDontSee('DEL-CLAIMD');

        $this->actingAs($this->staff)
            ->get(route('staff.deliveries', ['view' => 'history']))
            ->assertOk()
            ->assertSee('DEL-CLAIMD')
            ->assertDontSee($first->delivery_id);
    }

    public function test_staff_filters_are_validated_and_search_date_and_status_work_together(): void
    {
        $this->delivery('DEL-MATCH1', 'Geo', 'pending', '2026-07-22 08:00:00', 1);
        $this->delivery('DEL-OTHER1', 'Other Client', 'completed', '2026-07-21 08:00:00', 1);

        $this->actingAs($this->staff)
            ->get(route('staff.deliveries', [
                'search' => 'Geo', 'date' => '2026-07-22', 'status' => 'pending',
            ]))
            ->assertOk()
            ->assertSee('DEL-MATCH1')
            ->assertDontSee('DEL-OTHER1');

        $this->actingAs($this->staff)
            ->get(route('staff.deliveries', ['status' => 'deleted']))
            ->assertSessionHasErrors('status');
    }

    public function test_delivery_workflow_only_moves_forward_and_completion_updates_inventory(): void
    {
        $delivery = $this->delivery('DEL-FLOW01', 'Flow Client', 'pending', '2026-07-22 08:00:00', 1);
        $delivery->inventoryLogs()->create([
            'stock_category' => 'palay', 'type' => 'in', 'quantity' => 100,
            'remarks' => 'Initial delivery', 'logged_at' => now(),
        ]);

        $this->actingAs($this->staff)
            ->post(route('staff.delivery-status', $delivery->id), ['status' => 'processing'])
            ->assertSessionHas('success');
        $this->assertSame('processing', $delivery->fresh()->status);

        $this->actingAs($this->staff)
            ->post(route('staff.actual-rice', $delivery->id), ['actual_rice' => 70])
            ->assertSessionHas('success');
        $this->assertSame('completed', $delivery->fresh()->status);

        $this->actingAs($this->staff)
            ->post(route('staff.delivery-status', $delivery->id), ['status' => 'pending'])
            ->assertSessionHasErrors('status');
        $this->assertSame('completed', $delivery->fresh()->status);
        $this->assertDatabaseHas('inventory_logs', [
            'delivery_id' => $delivery->id, 'stock_category' => 'milled_rice',
            'type' => 'in', 'quantity' => 70,
        ]);
    }

    private function delivery(
        string $deliveryId,
        string $clientName,
        string $status,
        string $date,
        int $queue
    ): Delivery {
        return Delivery::create([
            'delivery_id' => $deliveryId,
            'staff_id' => $this->staff->id,
            'queue_number' => $queue,
            'queue_date' => substr($date, 0, 10),
            'client_name' => $clientName,
            'contact_number' => '09123456789',
            'rice_type_id' => $this->riceType->id,
            'sacks' => 1,
            'palay_weight' => 100,
            'recovery_rate' => 70,
            'estimated_rice' => 70,
            'actual_rice' => in_array($status, ['completed', 'claimed'], true) ? 70 : null,
            'status' => $status,
            'delivered_at' => $date,
        ]);
    }
}
