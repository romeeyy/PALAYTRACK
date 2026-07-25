<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppearanceSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_and_staff_can_save_their_own_theme_preference(): void
    {
        foreach (['owner', 'staff'] as $role) {
            $user = User::factory()->create([
                'role' => $role,
                'is_active' => true,
                'theme_preference' => 'classic',
            ]);

            $this->actingAs($user)
                ->get(route('appearance.edit'))
                ->assertOk()
                ->assertSee('Color Theme');

            $this->actingAs($user)
                ->post(route('appearance.update'), [
                    'theme_preference' => 'forest',
                    'display_mode' => 'dark',
                ])
                ->assertRedirect()
                ->assertSessionHas('success');

            $this->assertSame('forest', $user->fresh()->theme_preference);
            $this->assertSame('dark', $user->fresh()->display_mode);
        }
    }

    public function test_unknown_themes_and_guests_are_rejected(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'is_active' => true]);

        $this->actingAs($owner)
            ->post(route('appearance.update'), [
                'theme_preference' => 'rainbow',
                'display_mode' => 'light',
            ])
            ->assertSessionHasErrors('theme_preference');

        auth()->logout();
        $this->get(route('appearance.edit'))->assertForbidden();
    }
}
