<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OwnerProfileSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_profile_and_upload_a_valid_photo(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'is_active' => true,
        ]);

        $response = $this->actingAs($owner)->post(route('owner.profile.update'), [
            'name' => '  Updated   Owner  ',
            'email' => 'OWNER@EXAMPLE.COM',
            'profile_photo' => UploadedFile::fake()->image('owner.png', 300, 300),
        ]);

        $response->assertRedirect()->assertSessionHas('success');
        $owner->refresh();

        $this->assertSame('Updated Owner', $owner->name);
        $this->assertSame('owner@example.com', $owner->email);
        $this->assertNotNull($owner->profile_photo_path);
        $this->assertFileExists(public_path($owner->profile_photo_path));

        File::delete(public_path($owner->profile_photo_path));
    }

    public function test_password_change_requires_the_current_password_and_a_strong_new_password(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'password' => 'Current123',
            'is_active' => true,
        ]);

        $this->actingAs($owner)->post(route('owner.profile.password'), [
            'current_password' => 'wrong-password',
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ])->assertSessionHasErrors('current_password', null, 'passwordUpdate');

        $this->actingAs($owner)->post(route('owner.profile.password'), [
            'current_password' => 'Current123',
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ])->assertSessionHas('password_success');

        $this->assertTrue(Hash::check('NewPassword123', $owner->fresh()->password));
    }
}
