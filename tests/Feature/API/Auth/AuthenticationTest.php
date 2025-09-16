<?php

namespace Feature\API\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_credentials()
    {
        $response = $this->postJson(
            uri: route('auth.register'),
            data: [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'test123123123',
                'password_confirmation' => 'test123123123',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'expires_in',
            ]);
    }
}
