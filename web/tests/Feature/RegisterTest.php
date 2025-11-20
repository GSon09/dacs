<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success()
    {
        $response = $this->post('/register', [
            'name' => 'Nguyen Van A',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '0123456789',
            'address' => 'Hanoi',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_register_requires_fields()
    {
        $response = $this->post('/register', []);
        $response->assertSessionHasErrors(['name','email','password','phone','address']);
    }

    public function test_register_duplicate_email()
    {
        User::factory()->create(['email' => 'exists@example.com']);
        $response = $this->post('/register', [
            'name'=>'A','email'=>'exists@example.com',
            'password'=>'password123','password_confirmation'=>'password123',
            'phone'=>'0123456789','address'=>'addr'
        ]);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_register_phone_format_invalid()
    {
        $response = $this->post('/register', [
            'name' => 'B',
            'email' => 'phonefail@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '12345',
            'address' => 'Hanoi',
        ]);

        $response->assertSessionHasErrors(['phone']);
    }
}
