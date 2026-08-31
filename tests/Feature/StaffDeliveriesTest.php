<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\Setting;
use App\Models\User;
use App\Services\SmsService;
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
            ->withSession(['delivery_completion_tokens' => [$delivery->id => 'valid-completion-token']])
            ->post(route('staff.actual-rice', $delivery->id), [
                'actual_rice' => 70,
                'completion_token' => 'valid-completion-token',
            ])
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

    public function test_invalid_or_replayed_completion_request_cannot_complete_delivery(): void
    {
        $delivery = $this->delivery('DEL-GUARD1', 'Guarded Client', 'processing', '2026-07-22 08:00:00', 1);
        $delivery->inventoryLogs()->create([
            'stock_category' => 'palay', 'type' => 'in', 'quantity' => 100,
            'remarks' => 'Initial delivery', 'logged_at' => now(),
        ]);

        $this->actingAs($this->staff)
            ->withSession(['delivery_completion_tokens' => [$delivery->id => 'valid-completion-token']])
            ->post(route('staff.actual-rice', $delivery->id), [
                'actual_rice' => 0,
                'completion_token' => 'valid-completion-token',
            ])
            ->assertSessionHasErrors('actual_rice');

        $this->assertSame('processing', $delivery->fresh()->status);
        $this->assertNull($delivery->fresh()->actual_rice);
        $this->assertDatabaseMissing('inventory_logs', [
            'delivery_id' => $delivery->id,
            'stock_category' => 'milled_rice',
        ]);

        $this->actingAs($this->staff)
            ->post(route('staff.actual-rice', $delivery->id), [
                'actual_rice' => 70,
                'completion_token' => 'stale-or-replayed-token',
            ])
            ->assertSessionHasErrors('actual_rice');

        $this->assertSame('processing', $delivery->fresh()->status);
        $this->assertNull($delivery->fresh()->actual_rice);
    }

    public function test_resending_failed_sms_preserves_every_notification_attempt(): void
    {
        $delivery = $this->delivery('DEL-SMS001', 'SMS Client', 'completed', '2026-07-22 08:00:00', 1);
        $failedAttempt = $delivery->notifications()->create([
            'method' => 'text',
            'notification_status' => 'failed',
            'source' => 'automatic',
            'remarks' => 'Message Failed',
            'notified_at' => null,
        ]);
        Setting::setValue('sms_enabled', '1');

        $smsService = $this->mock(SmsService::class);
        $smsService->shouldReceive('send')
            ->once()
            ->with($delivery->contact_number, \Mockery::type('string'))
            ->andReturn(['success' => true, 'status' => 'sent']);

        $this->actingAs($this->staff)
            ->post(route('staff.resend-sms', $failedAttempt->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('delivery_notifications', [
            'id' => $failedAttempt->id,
            'delivery_id' => $delivery->id,
            'method' => 'text',
            'notification_status' => 'failed',
            'remarks' => 'Message Failed',
        ]);
        $this->assertDatabaseHas('delivery_notifications', [
            'delivery_id' => $delivery->id,
            'method' => 'text',
            'notification_status' => 'sent',
        ]);
        $this->assertSame(2, $delivery->notifications()->count());
    }

    public function test_manual_failed_notification_is_retried_by_logging_a_new_manual_attempt(): void
    {
        $delivery = $this->delivery('DEL-MAN001', 'Manual Client', 'completed', '2026-07-22 08:00:00', 1);
        Setting::setValue('sms_enabled', '0');

        $this->actingAs($this->staff)
            ->post(route('staff.delivery-notification', $delivery->id), [
                'method' => 'text',
                'notification_status' => 'failed',
                'notified_at' => now()->subMinute()->format('Y-m-d H:i:s'),
                'remarks' => 'No signal',
            ])
            ->assertSessionHas('success');

        $this->actingAs($this->staff)
            ->post(route('staff.delivery-notification', $delivery->id), [
                'method' => 'call',
                'notification_status' => 'reached',
                'notified_at' => now()->format('Y-m-d H:i:s'),
                'remarks' => 'Reached by phone',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('delivery_notifications', [
            'delivery_id' => $delivery->id,
            'source' => 'manual',
            'notification_status' => 'failed',
        ]);
        $this->assertDatabaseHas('delivery_notifications', [
            'delivery_id' => $delivery->id,
            'source' => 'manual',
            'notification_status' => 'reached',
        ]);
        $this->assertSame('completed', $delivery->fresh()->status);
        $this->assertSame(2, $delivery->notifications()->count());
    }

    public function test_successful_manual_notification_hides_form_and_blocks_duplicates(): void
    {
        $delivery = $this->delivery('DEL-MAN002', 'Notified Client', 'completed', '2026-07-22 08:00:00', 1);
        Setting::setValue('sms_enabled', '0');

        $payload = [
            'method' => 'call',
            'notification_status' => 'reached',
            'notified_at' => now()->format('Y-m-d H:i:s'),
            'remarks' => 'Farmer reached',
        ];

        $this->actingAs($this->staff)
            ->post(route('staff.delivery-notification', $delivery->id), $payload)
            ->assertSessionHas('success');

        $this->actingAs($this->staff)
            ->get(route('staff.delivery-details', $delivery->id))
            ->assertOk()
            ->assertDontSee('Save Notification');

        $this->actingAs($this->staff)
            ->post(route('staff.delivery-notification', $delivery->id), $payload)
            ->assertSessionHas('warning');

        $this->assertSame(1, $delivery->notifications()->count());
    }

    public function test_automatic_resend_is_blocked_when_sms_is_disabled(): void
    {
        $delivery = $this->delivery('DEL-SMSOFF', 'Disabled SMS Client', 'completed', '2026-07-22 08:00:00', 1);
        $failedAttempt = $delivery->notifications()->create([
            'method' => 'text',
            'notification_status' => 'failed',
            'source' => 'automatic',
            'remarks' => 'Message Failed',
        ]);
        Setting::setValue('sms_enabled', '0');

        $smsService = $this->mock(SmsService::class);
        $smsService->shouldNotReceive('send');

        $this->actingAs($this->staff)
            ->post(route('staff.resend-sms', $failedAttempt->id))
            ->assertSessionHas('error');

        $this->assertSame(1, $delivery->notifications()->count());
        $this->assertSame('completed', $delivery->fresh()->status);
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
