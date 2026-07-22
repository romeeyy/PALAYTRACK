<?php

namespace Tests\Feature;

use App\Models\Delivery;
use App\Models\RiceType;
use App\Models\User;
use App\Services\RiceTypeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RiceTypeSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_recovery_rate_change_is_audited_without_changing_existing_delivery_snapshot(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $riceType = RiceType::create([
            'name' => 'White',
            'recovery_rate' => 70,
            'status' => 'active',
        ]);

        $delivery = Delivery::create([
            'delivery_id' => 'DEL-RICE-TYPE',
            'queue_number' => 1,
            'client_name' => 'Client',
            'contact_number' => '09123456789',
            'rice_type_id' => $riceType->id,
            'sacks' => 5,
            'palay_weight' => 500,
            'recovery_rate' => 70,
            'estimated_rice' => 350,
            'status' => 'pending',
            'delivered_at' => now(),
        ]);

        app(RiceTypeService::class)->update($riceType, [
            'name' => 'White',
            'recovery_rate' => 68,
            'status' => 'active',
            'description' => null,
        ], $owner);

        $this->assertSame(70.0, (float) $delivery->fresh()->recovery_rate);
        $this->assertDatabaseHas('rice_type_recovery_rate_histories', [
            'rice_type_id' => $riceType->id,
            'old_rate' => 70,
            'new_rate' => 68,
            'changed_by' => $owner->id,
        ]);
    }

    public function test_rice_type_api_mutations_require_owner_and_deletion_is_disabled(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $owner = User::factory()->create(['role' => 'owner']);
        $riceType = RiceType::create([
            'name' => 'Red',
            'recovery_rate' => 65,
            'status' => 'active',
        ]);

        $this->actingAs($staff)->putJson('/api/rice-types/' . $riceType->id, [
            'name' => 'Red',
            'recovery_rate' => 60,
            'status' => 'active',
        ])->assertForbidden();

        $this->actingAs($owner)->deleteJson('/api/rice-types/' . $riceType->id)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('rice_type');

        $this->assertDatabaseHas('rice_types', ['id' => $riceType->id]);
    }

    public function test_unused_rice_type_milling_fee_column_is_removed(): void
    {
        $this->assertFalse(Schema::hasColumn('rice_types', 'milling_fee_per_kg'));
    }
}
