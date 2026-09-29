<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StaffDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_cards_show_live_work_and_claims_from_the_current_month(): void
    {
        Carbon::setTestNow('2026-07-22 12:00:00');
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $riceType = RiceType::create(['name' => 'White', 'recovery_rate' => 70, 'status' => 'active']);

        $this->delivery($staff, $riceType, 'DEL-OLD001', 'claimed', '2026-06-20 09:00:00', 1);
        $this->delivery($staff, $riceType, 'DEL-JULY01', 'claimed', '2026-07-21 09:00:00', 1);
        $this->delivery($staff, $riceType, 'DEL-OLDPEN', 'pending', '2026-06-22 09:00:00', 2);
        $this->delivery($staff, $riceType, 'DEL-JULY02', 'pending', '2026-07-22 09:00:00', 1);

        $response = $this->actingAs($staff)->get(route('staff.dashboard'));

        $response->assertOk()
            ->assertViewHas('claimedCount', 1)
            ->assertViewHas('pendingCount', 2)
            ->assertViewHas('processingCount', 0)
            ->assertViewHas('completedCount', 0)
            ->assertSee('Pending Now')
            ->assertSee('Processing Now')
            ->assertSee('Ready for Claim Now')
            ->assertSee('Claimed This Month');

        Carbon::setTestNow();
    }

    private function delivery(
        User $staff,
        RiceType $riceType,
        string $deliveryId,
        string $status,
        string $date,
        int $queue
    ): void {
        Delivery::forceCreate([
            'delivery_id' => $deliveryId,
            'staff_id' => $staff->id,
            'queue_number' => $queue,
            'queue_date' => substr($date, 0, 10),
            'client_name' => 'Test Client',
            'contact_number' => '09123456789',
            'rice_type_id' => $riceType->id,
            'sacks' => 1,
            'palay_weight' => 100,
            'recovery_rate' => 70,
            'estimated_rice' => 70,
            'actual_rice' => $status === 'claimed' ? 70 : null,
            'status' => $status,
            'claimed_at' => $status === 'claimed' ? $date : null,
            'delivered_at' => $date,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
