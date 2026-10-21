<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_google_login_button(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('Masuk dengan Akun Google');
        $response->assertSee(route('auth.google'));
    }

    public function test_google_redirect_warns_when_credentials_not_configured(): void
    {
        Config::set('services.google.client_id', null);
        Config::set('services.google.client_secret', null);

        $response = $this->get(route('auth.google'));

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error');
    }

    public function test_google_login_creates_new_user_if_not_exists(): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google-123456');
        $socialiteUser->shouldReceive('getName')->andReturn('Budi Santoso');
        $socialiteUser->shouldReceive('getEmail')->andReturn('budi@example.com');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar.jpg');
        $socialiteUser->token = 'dummy-token';
        $socialiteUser->refreshToken = 'dummy-refresh-token';

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'google_id' => 'google-123456',
        ]);
    }

    public function test_google_callback_links_existing_user_by_email(): void
    {
        $existing = User::where('email', 'admin@imersa.test')->first() ?? User::factory()->create([
            'email' => 'admin@imersa.test',
            'google_id' => null,
            'role' => 'admin',
        ]);
        $existing->update(['google_id' => null, 'role' => 'admin']);

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google-999999');
        $socialiteUser->shouldReceive('getName')->andReturn('Admin PT Imersa');
        $socialiteUser->shouldReceive('getEmail')->andReturn('admin@imersa.test');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/admin.jpg');
        $socialiteUser->token = 'token-123';
        $socialiteUser->refreshToken = null;

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($existing);

        $existing->refresh();
        $this->assertEquals('google-999999', $existing->google_id);
    }

    public function test_authenticated_user_can_view_profile_and_google_linking_card(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('admin.profile'));

        $response->assertStatus(200);
        $response->assertSee('Tautkan Akun Google');
        $response->assertSee('Belum Tertaut');
    }

    public function test_authenticated_user_cannot_unlink_google_if_no_password_set(): void
    {
        $user = User::factory()->create([
            'google_id' => 'google-111',
            'password' => null,
        ]);

        $response = $this->actingAs($user)->post(route('auth.google.unlink'));

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('error');

        $user->refresh();
        $this->assertNotNull($user->google_id);
    }

    public function test_authenticated_user_can_unlink_google_if_password_exists(): void
    {
        $user = User::factory()->create([
            'google_id' => 'google-222',
            'password' => Hash::make('Secret123!'),
        ]);

        $response = $this->actingAs($user)->post(route('auth.google.unlink'));

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNull($user->google_id);
    }

    public function test_google_simulator_page_renders_with_presets_and_form(): void
    {
        $response = $this->get(route('auth.google.simulator'));

        $response->assertStatus(200);
        $response->assertSee('Mode Simulasi Google');
        $response->assertSee('admin@imersa.test');
        $response->assertSee('dimas.pratama@gmail.com');
    }

    public function test_google_simulator_can_log_in_user_and_create_if_new(): void
    {
        $response = $this->post(route('auth.google.simulator.post'), [
            'name' => 'Budi Santoso',
            'email' => 'budi.google@gmail.com',
            'origin' => 'user',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'budi.google@gmail.com',
            'role' => 'customer',
        ]);
    }

    public function test_google_simulator_can_link_account_to_authenticated_user(): void
    {
        $user = User::factory()->create([
            'google_id' => null,
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['google_oauth_action' => 'link'])
            ->post(route('auth.google.simulator.post'), [
                'name' => $user->name,
                'email' => 'linked.google@gmail.com',
            ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->google_id);
    }
}
