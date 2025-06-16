<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterFormSub extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name'              => 'testuser',
            'email'                 => 'testuser@mail.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'name' => 'testuser',
            'email'    => 'testuser@mail.com',
        ]);
        $this->assertAuthenticated();
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        // Passwords do not match
        $response = $this->post('/register', [
            'name'              => 'testuser',
            'email'                 => 'testuser@mail.com',
            'password'              => 'password123',
            'password_confirmation' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();

        // Email already taken
        User::factory()->create(['email' => 'testuser@mail.com']);
        $response = $this->post('/register', [
            'name'              => 'anotheruser',
            'email'                 => 'testuser@mail.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();

        // Missing name
        $response = $this->post('/register', [
            'name'              => '',
            'email'                 => 'newuser@mail.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();

        // Invalid email
        $response = $this->post('/register', [
            'name'              => 'testuser',
            'email'                 => 'not-an-email',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}