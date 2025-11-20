<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success_with_email()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'phone' => '0912345678',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_nonexistent_account_shows_message()
    {
        $response = $this->post('/login', [
            'login' => 'notfound@example.com',
            'password' => 'whatever',
        ]);

        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }

    public function test_login_wrong_password_shows_message()
    {
        $user = User::factory()->create([
            'email' => 'u2@example.com',
            'phone' => '0912345679',
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->post('/login', [
            'login' => 'u2@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_login_requires_fields()
    {
        $response = $this->post('/login', []);
        $response->assertSessionHasErrors(['login','password']);
    }
}
