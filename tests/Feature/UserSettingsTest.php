<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_settings_are_created_with_default_theme(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->settings);
        $this->assertSame('light', $user->settings->theme);
    }

    public function test_theme_is_shared_with_inertia(): void
    {
        $user = User::factory()->create();
        $user->settings()->update(['theme' => 'dark']);

        $this->actingAs($user)
            ->get(route('student.dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->where('settings.theme', 'dark')
            );
    }

    public function test_authenticated_user_can_update_theme(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('user.settings.theme.update'), ['theme' => 'dark'])
            ->assertRedirect();

        $this->assertSame('dark', $user->settings()->first()->theme);
    }

    public function test_theme_must_be_valid(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('user.settings.theme.update'), ['theme' => 'system'])
            ->assertInvalid('theme');
    }
}
