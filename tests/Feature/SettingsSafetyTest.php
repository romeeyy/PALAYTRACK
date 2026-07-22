<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_fee_values_are_validated_updated_and_audited_without_duplicate_history(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);

        $this->actingAs($owner)->post(route('owner.milling-fee-settings'), [
            'menudo_fee' => 0, 'commercial_fee' => 1.50,
        ])->assertSessionHasErrors('menudo_fee');

        $this->actingAs($owner)->post(route('owner.milling-fee-settings'), [
            'menudo_fee' => 3.25, 'commercial_fee' => 2.25,
        ])->assertSessionHasNoErrors();

        $this->assertSame('3.25', Setting::getValue('menudo_fee'));
        $this->assertSame('2.25', Setting::getValue('commercial_fee'));
        $this->assertDatabaseCount('milling_fee_histories', 2);

        $this->actingAs($owner)->post(route('owner.milling-fee-settings'), [
            'menudo_fee' => 3.25, 'commercial_fee' => 2.25,
        ]);
        $this->assertDatabaseCount('milling_fee_histories', 2);
    }

    public function test_existing_delivery_keeps_old_fee_while_new_delivery_gets_new_fee(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $rice = RiceType::create(['name' => 'White', 'recovery_rate' => 70, 'status' => 'active']);
        Setting::setValue('menudo_fee', '2.00');

        $this->actingAs($owner)->post(route('owner.record-delivery.store'), $this->deliveryPayload($rice, 'Old Rate', '09111111111'));
        $oldDelivery = Delivery::where('client_name', 'Old Rate')->firstOrFail();
        $this->assertEquals(2.00, $oldDelivery->milling_fee_per_kg_at_delivery);

        $this->actingAs($owner)->post(route('owner.milling-fee-settings'), [
            'menudo_fee' => 3.00, 'commercial_fee' => 1.50,
        ]);
        $oldDelivery->update(['status' => 'completed']);
        $this->actingAs($staff)->post(route('staff.pos.store', $oldDelivery), [
            'payment_method' => 'cash', 'amount_received' => 400,
        ]);
        $this->assertDatabaseHas('transactions', [
            'delivery_id' => $oldDelivery->id, 'milling_fee_per_kg' => 2.00, 'total_amount' => 400.00,
        ]);

        $this->actingAs($owner)->post(route('owner.record-delivery.store'), $this->deliveryPayload($rice, 'New Rate', '09222222222'));
        $newDelivery = Delivery::where('client_name', 'New Rate')->firstOrFail();
        $this->assertEquals(3.00, $newDelivery->milling_fee_per_kg_at_delivery);
    }

    public function test_sms_changes_are_validated_and_audited(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        Setting::setValue('sms_enabled', '0');

        $this->actingAs($owner)->post(route('owner.sms-settings'), ['sms_enabled' => 'yes'])
            ->assertSessionHasErrors('sms_enabled');
        $this->actingAs($owner)->post(route('owner.sms-settings'), ['sms_enabled' => '1'])
            ->assertSessionHasNoErrors();

        $this->assertSame('1', Setting::getValue('sms_enabled'));
        $this->assertDatabaseHas('system_setting_histories', [
            'key' => 'sms_enabled', 'old_value' => '0', 'new_value' => '1', 'changed_by' => $owner->id,
        ]);
    }

    public function test_owner_profile_is_normalized_and_duplicate_email_is_rejected(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        User::factory()->create(['email' => 'used@example.com']);

        $this->actingAs($owner)->post(route('owner.profile.update'), [
            'name' => '  Rice   Mill Owner ', 'email' => ' OWNER@EXAMPLE.COM ',
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['id' => $owner->id, 'name' => 'Rice Mill Owner', 'email' => 'owner@example.com']);

        $this->actingAs($owner)->post(route('owner.profile.update'), [
            'name' => 'Owner', 'email' => 'USED@EXAMPLE.COM',
        ])->assertSessionHasErrors('email');
    }

    private function deliveryPayload(RiceType $rice, string $name, string $contact): array
    {
        return ['client_name' => $name, 'contact_number' => $contact, 'rice_type_id' => $rice->id, 'sacks' => 2, 'palay_weight' => 200];
    }
}
