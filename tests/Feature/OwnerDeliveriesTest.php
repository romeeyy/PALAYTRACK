<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OwnerDeliveriesTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $staff;
    private RiceType $riceType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $this->staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $this->riceType = RiceType::create([
            'name' => 'White', 'recovery_rate' => 70, 'status' => 'active',
        ]);
    }

    public function test_owner_can_filter_deliveries_by_search_status_and_date(): void
    {
        $this->delivery('DEL-MATCH1', 'Geo', 'pending', '2026-07-22 08:00:00', 1);
        $this->delivery('DEL-OTHER1', 'Sheila', 'completed', '2026-07-21 08:00:00', 2);

        $response = $this->actingAs($this->owner)->get(route('owner.deliveries', [
            'search' => 'Geo',
            'status' => 'pending',
            'date' => '2026-07-22',
        ]));

        $response->assertOk()
            ->assertSee('DEL-MATCH1')
            ->assertDontSee('DEL-OTHER1');
    }

    public function test_owner_delivery_list_prioritizes_next_action_then_uses_fcfs(): void
    {
        $this->delivery('DEL-DONE01', 'Finished', 'completed', '2026-07-22 10:00:00', 4);
        $this->delivery('DEL-QUEUE2', 'Second', 'processing', '2026-07-22 09:00:00', 3);
        $this->delivery('DEL-QUEUE1', 'First', 'pending', '2026-07-22 08:00:00', 2);

        $this->actingAs($this->owner)
            ->get(route('owner.deliveries'))
            ->assertOk()
            ->assertSeeInOrder(['DEL-QUEUE2', 'DEL-QUEUE1', 'DEL-DONE01'])
            ->assertSee($this->staff->name)
            ->assertSee('Staff');
    }

    public function test_owner_delivery_list_rejects_unknown_status_filters(): void
    {
        $this->actingAs($this->owner)
            ->get(route('owner.deliveries', ['status' => 'deleted']))
            ->assertSessionHasErrors('status');
    }

    public function test_claimed_deliveries_are_separated_from_the_default_active_queue(): void
    {
        $this->delivery('DEL-ACTIVE', 'Active Client', 'pending', '2026-07-22 08:00:00', 1);
        $this->delivery('DEL-CLAIMD', 'Claimed Client', 'claimed', '2026-07-22 09:00:00', 2);

        $this->actingAs($this->owner)
            ->get(route('owner.deliveries'))
            ->assertOk()
            ->assertSee('DEL-ACTIVE')
            ->assertDontSee('DEL-CLAIMD');

        $this->actingAs($this->owner)
            ->get(route('owner.deliveries', ['view' => 'history']))
            ->assertOk()
            ->assertSee('DEL-CLAIMD')
            ->assertDontSee('DEL-ACTIVE');
    }

    public function test_queue_number_increments_during_the_day_and_resets_the_next_day(): void
    {
        Carbon::setTestNow('2026-07-22 08:00:00');
        $this->recordDelivery('First Client', '09111111111');

        Carbon::setTestNow('2026-07-22 09:00:00');
        $this->recordDelivery('Second Client', '09222222222');

        Carbon::setTestNow('2026-07-23 07:00:00');
        $this->recordDelivery('Next Day Client', '09333333333');
        Carbon::setTestNow();

        $this->assertSame([1, 2], Delivery::whereDate('queue_date', '2026-07-22')->orderBy('queue_number')->pluck('queue_number')->all());
        $this->assertSame([1], Delivery::whereDate('queue_date', '2026-07-23')->pluck('queue_number')->all());
    }

    public function test_delivery_accepts_half_sacks_but_rejects_arbitrary_decimals(): void
    {
        $payload = [
            'client_name' => 'Half Sack Client',
            'contact_number' => '09444444444',
            'rice_type_id' => $this->riceType->id,
            'palay_weight' => 75.5,
        ];

        $this->actingAs($this->owner)
            ->post(route('owner.record-delivery.store'), $payload + ['sacks' => 2.98])
            ->assertSessionHasErrors('sacks');

        $this->assertDatabaseCount('deliveries', 0);

        $this->actingAs($this->owner)
            ->post(route('owner.record-delivery.store'), $payload + ['sacks' => 2.5])
            ->assertRedirect();

        $this->assertDatabaseHas('deliveries', ['client_name' => 'Half Sack Client', 'sacks' => 2.5]);
    }

    private function recordDelivery(string $clientName, string $contact): void
    {
        $this->actingAs($this->owner)->post(route('owner.record-delivery.store'), [
            'client_name' => $clientName,
            'contact_number' => $contact,
            'rice_type_id' => $this->riceType->id,
            'sacks' => 2,
            'palay_weight' => 100,
        ])->assertRedirect();
    }

    private function delivery(
        string $deliveryId,
        string $clientName,
        string $status,
        string $deliveredAt,
        int $queue
    ): Delivery {
        return Delivery::create([
            'delivery_id' => $deliveryId,
            'staff_id' => $this->staff->id,
            'queue_number' => $queue,
            'client_name' => $clientName,
            'contact_number' => '09123456789',
            'rice_type_id' => $this->riceType->id,
            'sacks' => 2,
            'palay_weight' => 100,
            'recovery_rate' => 70,
            'estimated_rice' => 70,
            'status' => $status,
            'delivered_at' => $deliveredAt,
        ]);
    }
}
