<?php

namespace Tests\Feature;

use App\Models\StaffAccountHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffAccountSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_owner_can_manage_staff_and_creation_is_normalized_and_audited(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);

        $this->actingAs($staff)->get(route('owner.staff-accounts'))->assertForbidden();

        $this->actingAs($owner)->post(route('owner.staff-accounts.store'), [
            'name' => '  Juan   Cashier  ',
            'email' => '  JUAN@EXAMPLE.COM ',
            'password' => 'Staff1234',
            'password_confirmation' => 'Staff1234',
        ])->assertRedirect(route('owner.staff-accounts'));

        $created = User::where('email', 'juan@example.com')->firstOrFail();
        $this->assertSame('Juan Cashier', $created->name);
        $this->assertTrue(Hash::check('Staff1234', $created->password));
        $this->assertDatabaseHas('staff_account_histories', [
            'staff_id' => $created->id,
            'owner_id' => $owner->id,
            'action' => 'created',
        ]);
    }

    public function test_weak_password_and_duplicate_email_are_rejected(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        User::factory()->create(['email' => 'used@example.com']);

        $this->actingAs($owner)->post(route('owner.staff-accounts.store'), [
            'name' => 'Weak Staff',
            'email' => 'new@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])->assertSessionHasErrors('password');

        $this->actingAs($owner)->post(route('owner.staff-accounts.store'), [
            'name' => 'Duplicate Staff',
            'email' => 'USED@EXAMPLE.COM',
            'password' => 'Strong123',
            'password_confirmation' => 'Strong123',
        ])->assertSessionHasErrors('email');
    }

    public function test_blank_password_keeps_existing_password_and_profile_changes_are_audited(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $staff = User::factory()->create([
            'role' => 'staff', 'is_active' => true, 'password' => 'Original123',
        ]);
        $oldName = $staff->name;

        $this->actingAs($owner)->post(route('owner.staff-accounts.update', $staff->id), [
            'name' => 'Updated Name',
            'email' => $staff->email,
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('owner.staff-accounts'));

        $staff->refresh();
        $this->assertTrue(Hash::check('Original123', $staff->password));
        $this->assertDatabaseHas('staff_account_histories', [
            'staff_id' => $staff->id,
            'field' => 'name',
            'old_value' => $oldName,
            'new_value' => 'Updated Name',
        ]);
        $this->assertSame(0, StaffAccountHistory::where('field', 'password')->count());
    }

    public function test_deactivated_logged_in_staff_is_logged_out_on_the_next_request(): void
    {
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => false]);

        $this->actingAs($staff)
            ->get(route('staff.dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_owner_cannot_be_modified_through_staff_account_routes(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);
        $otherOwner = User::factory()->create(['role' => 'owner', 'is_active' => true]);

        $this->actingAs($owner)
            ->get(route('owner.staff-accounts.edit', $otherOwner->id))
            ->assertNotFound();
    }
}
