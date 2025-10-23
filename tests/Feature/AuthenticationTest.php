<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertAuthenticated();
    }

    /** @test */
    public function registration_requires_valid_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function users_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function users_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'WrongPassword',
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function authenticated_users_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }
}
