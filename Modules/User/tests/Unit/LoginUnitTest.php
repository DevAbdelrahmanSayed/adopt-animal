<?php

namespace Modules\User\tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\app\Models\User;
use Tests\TestCase;

class LoginUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'name_' => 'abed',
            'email' => 'validemail@gmail.com',
            'username' => 'validusername',
            'password' => 'ValidPass123!',
            'country' => 'istanbul',
            'address' => 'uskudar',
            'contact_number' => '5070145054',
        ]);
    }

    public function test_Invalid_Login_With_Incorrect_Email()
    {
        $response = $this->postJson('api/v1/login', [
            'username_email' => 'wrongemail@gmail.com',
            'password' => 'ValidPass123!',
        ]);

        $response->assertStatus(422);

        $this->assertEquals('The email or password you entered is incorrect.', $response['message']);
    }

    public function test_Invalid_Login_With_Incorrect_Username()
    {
        $response = $this->postJson('api/v1/login', [
            'username_email' => 'wrongUsername',
            'password' => 'ValidPass123!',
        ]);

        $response->assertStatus(422);

        $this->assertEquals('The email or password you entered is incorrect.', $response['message']);
    }

    public function test_Invalid_Login_With_Incorrect_Password()
    {
        $response = $this->postJson('api/v1/login', [
            'username_email' => 'validemail@gmail.com',
            'password' => 'WrongPass123!',
        ]);

        $response->assertStatus(422);

        $this->assertEquals('The email or password you entered is incorrect.', $response['message']);
    }

    public function test_login_with_missing_login_identifier()
    {
        $loginData = [

            'password' => 'invalid_password',
        ];

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
            'username_email' => 'test@gmail.com',
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
