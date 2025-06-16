<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginFormSub extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials()
    {
        $user_email = 'customer1@mail.com';
        $user_password = '12345678';
        $user = User::factory()->create([
            'email' => $user_email,
            'password' => $user_password, // plain password if using 'hashed' cast
        ]);

        $response = $this->post('/login', [
            'email' => $user_email,
            'password' => $user_password,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        // Wrong password
        $response = $this->post('/login', [
            'email' => 'customer1@mail.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();

        // Wrong email
        $response = $this->post('/login', [
            'email' => 'wrong@mail.com',
            'password' => '12345678',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();

        // Both email and password wrong
        $response = $this->post('/login', [
            'email' => 'wrong@mail.com',
            'password' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();

        // Empty email
        $response = $this->post('/login', [
            'email' => '',
            'password' => '12345678',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();

        // Empty password
        $response = $this->post('/login', [
            'email' => 'customer1@mail.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}