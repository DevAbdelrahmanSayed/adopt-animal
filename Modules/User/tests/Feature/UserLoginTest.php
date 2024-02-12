<?php

namespace Modules\User\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\app\Models\User;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'name_' => 'abed',
            'email' => 'validemail@example.com',
            'username' => 'validusername',
            'password' => 'ValidPass123!',
            'country' => 'istanbul',
            'address' => 'uskudar',
            'contact_number' => '5070145054',
        ]);
    }

    public function test_can_be_login(): void
    {
        $loginData = [
            'username_email' => 'validusername',
            'password' => 'ValidPass123!', // Use plain text password
        ];

        // Attempt to login with valid credentials
        $response = $this->postJson('api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user_id',
                    'username',
                    'email',
                    'token',
                ],
            ]);
    }

    public function test_can_be_login_with_email(): void
    {

        $loginData = [
            'username_email' => 'validemail@example.com',
            'password' => 'ValidPass123!',
        ];
        $response = $this->postJson('api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user_id',
                    'name',
                    'username',
                    'email',
                    'token',
                ],
            ]);
    }

    public function test_can_be_login_with_username(): void
    {
        $loginData = [
            'username_email' => 'validusername',
            'password' => 'ValidPass123!', // Use plain text password
        ];

        // Attempt to login with valid credentials
        $response = $this->postJson('api/v1/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user_id',
                    'username',
                    'email',
                    'token',
                ],
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
                'status' => 422,
                'message' => 'Validation Errors',
                'data' => [
                    'username_email' => [
                        'The username email field is required.',
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
                'status' => 422,
                'message' => 'Validation Errors',
                'data' => [
                    'password' => [
                        'The password field is required.',
                    ],
                ],
            ]);
    }
}
