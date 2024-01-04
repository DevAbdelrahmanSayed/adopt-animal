<?php

namespace Modules\User\tests\Unit;

use Database\Factories\UserFactory;
use Modules\User\app\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginUnitTest extends TestCase
{

    use RefreshDatabase;
    public function test_login_with_short_password()
    {
        $user = UserFactory::new()->create();

        $loginData = [
            'username_email' => $user->email,
            'password' => 'short',
        ];
        $response = $this->postJson('api/v1/login',$loginData);
        $response->assertStatus(422)
            ->assertJson([
                "status" => 422,
                "message" => 'Validation Errors',
                'data' => [
                    'password' => [
                        "The password field must be at least 8 characters."
                    ],
                ],
            ]);
    }

    public function test_login_with_username()
    {
        // Create a test user
        $user = User::create([
            'name_' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // Hash the password
            'country' => 'Test Country',
            'address' => 'Test Address',
            'contact_number' => '1234567890',
        ]);

        // Data for login
        $loginData = [
            'username_email' => $user->username,
            'password' => 'password123', // Use the same password used for creating the user
        ];

        // Attempt to log in
        $response = $this->postJson('api/v1/login', $loginData);

        // Optional: Dump the response for debugging
        $response->dump();

        // Assert the login was successful
        $response->assertStatus(200);
    }
}
