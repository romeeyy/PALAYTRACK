<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\RiceType;
use App\Models\User;
use App\Services\ClientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ClientSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_and_normalized_contact_define_client_identity(): void
    {
        $service = app(ClientService::class);
        $geo = $service->resolve('  geo ', '+639123456789');
        $sameGeo = $service->resolve('Geo', '639123456789');
        $otherPerson = $service->resolve('Sandae Landungan', '09123456789');

        $this->assertSame($geo->id, $sameGeo->id);
        $this->assertNotSame($geo->id, $otherPerson->id);
        $this->assertSame('09123456789', $geo->contact_number);
        $this->assertDatabaseCount('clients', 2);
    }

    public function test_owner_delivery_creates_and_links_client_with_a_classification_snapshot(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $riceType = RiceType::create(['name' => 'White', 'recovery_rate' => 70, 'status' => 'active']);

        $this->actingAs($owner)->post(route('owner.record-delivery.store'), [
            'client_name' => 'Geo', 'contact_number' => '+639123456789',
            'rice_type_id' => $riceType->id, 'sacks' => 2, 'palay_weight' => 200,
        ])->assertRedirect();

        $client = Client::where('name', 'Geo')->firstOrFail();
        $this->assertDatabaseHas('deliveries', [
            'client_id' => $client->id, 'staff_id' => $owner->id,
            'contact_number' => '09123456789', 'client_type_at_delivery' => 'regular',
        ]);
    }

    public function test_later_classification_change_does_not_reprice_existing_delivery(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        $riceType = RiceType::create(['name' => 'White', 'recovery_rate' => 70, 'status' => 'active']);

        $this->actingAs($owner)->post(route('owner.record-delivery.store'), [
            'client_name' => 'Snapshot Client', 'contact_number' => '09111111111',
            'rice_type_id' => $riceType->id, 'sacks' => 2, 'palay_weight' => 200,
        ]);
        $client = Client::where('name', 'Snapshot Client')->firstOrFail();
        $delivery = $client->deliveries()->firstOrFail();
        $delivery->update(['status' => 'completed']);
        $delivery->notifications()->create([
            'method' => 'call', 'source' => 'manual',
            'notification_status' => 'reached', 'notified_at' => now(),
        ]);

        $this->actingAs($owner)->post(route('owner.clients.update-type', $client), [
            'client_type' => 'commercial',
        ])->assertSessionHas('success');

        $this->actingAs($staff)->post(route('staff.pos.store', $delivery), [
            'payment_method' => 'cash', 'amount_received' => 400,
        ])->assertRedirect('/staff/receipt/' . $delivery->id);

        $this->assertDatabaseHas('transactions', [
            'delivery_id' => $delivery->id, 'milling_type' => 'menudo',
        ]);
        $this->assertDatabaseHas('client_histories', [
            'client_id' => $client->id, 'field' => 'client_type',
            'old_value' => 'regular', 'new_value' => 'commercial',
        ]);
    }

    public function test_owner_profile_correction_is_audited_and_old_delivery_snapshot_is_preserved(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $client = Client::create(['name' => 'Old Name', 'contact_number' => '09222222222']);

        $this->actingAs($owner)->post(route('owner.clients.update', $client), [
            'name' => 'New Name', 'contact_number' => '+639222222222',
        ])->assertRedirect(route('owner.clients'));

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'New Name', 'contact_number' => '09222222222']);
        $this->assertDatabaseHas('client_histories', ['client_id' => $client->id, 'field' => 'name', 'old_value' => 'Old Name', 'new_value' => 'New Name']);
        $this->assertFalse(Schema::hasColumn('clients', 'address'));
    }
}
