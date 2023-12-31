<?php

namespace Modules\User\tests\Feature;

use Modules\User\app\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_be_login_with_email(): void
    {
        $user = User::create([
            'name'=>'tester',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $loginData = [
            'username_email' => $user->email,
            'password' => 'password',
        ];

        // Attempt to login with valid credentials
        $response = $this->postJson('api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                'data' => [
                    'id',
                    'username',
                    'email',
                    'token',
                ],
            ]);
    }
    public function test_can_be_login_with_username(): void
    {
        $user = User::create([
            'name'=>'tester',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $loginData = [
            'username_email' => $user->username,
            'password' => 'password', // Use plain text password
        ];

        // Attempt to login with valid credentials
        $response = $this->postJson('api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                'data' => [
                    'id',
                    'username',
                    'email',
                    'token',
                ],
            ]);
    }//Lkjhgfdsa276!
    public function test_login_with_invalid_credentials() :void
    {
        $loginData = [
            'username_email' => 'nonexistent@example.com',
            'password' => 'invalid_password',
        ];

        // Attempt to login with valid credentials
        $response = $this->postJson('api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJson([
                "status"=>200,
                "message"=>'user credentials do not work',
                'data' => [],
            ]);
    }
    public function test_login_with_missing_login_identifier()
    {
        $loginData = [

            'password' => 'invalid_password',
        ];

        // Attempt to login with valid credentials
        $response = $this->postJson('api/v1/login', $loginData);

        $response->assertStatus(422)
            ->assertJson([
                "status" => 422,
                "message" => 'Validation Errors',
                'data' => [
                    'username_email' => [
                        "The username email field is required."
                    ],
                ],
            ]);


    }
    public function test_login_with_missing_password()
    {
        $loginData = [
            'username_email' => 'test@example.com',
        ];
        // Attempt to login with valid credentials
        $response = $this->postJson('api/v1/login', $loginData);

        $response->assertStatus(422)
            ->assertJson([
                "status" => 422,
                "message" => 'Validation Errors',
                'data' => [
                    'password' => [
                        "The password field is required."
                    ],
                ],
            ]);
    }
}
