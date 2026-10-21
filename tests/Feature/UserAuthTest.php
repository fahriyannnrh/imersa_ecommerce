<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_visiting_home_or_products_is_redirected_to_login(): void
    {
        // 1. Visiting root website
        $response = $this->get('/');
        $response->assertRedirect('/login');

        // 2. Visiting products catalog
        $response2 = $this->get('/products');
        $response2->assertRedirect('/login');
    }

    public function test_user_login_page_renders_with_google_button_and_matching_ui(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('PT Imersa');
        $response->assertSee('Login Pengguna');
        $response->assertSee('Masuk dengan Akun Google');
        $response->assertSee(route('auth.google', ['origin' => 'user']));
        $response->assertSee('Email Pengguna');
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee(route('register'));
    }

    public function test_user_register_page_renders_with_google_button(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Daftar dengan Akun Google');
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Konfirmasi Password');
        $response->assertSee(route('login'));
    }

    public function test_user_can_register_successfully_and_is_redirected_to_home(): void
    {
        $response = $this->post('/register', [
            'name' => 'Dimas Customer',
            'email' => 'dimas@gmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'name' => 'Dimas Customer',
            'email' => 'dimas@gmail.com',
            'role' => 'customer',
        ]);
    }

    public function test_user_registration_validates_required_fields_and_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('Secret123!'),
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@gmail.com',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('Secret123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@gmail.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_catalog_and_homepage(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);

        $response2 = $this->actingAs($user)->get('/products');
        $response2->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/admin/dashboard');
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
