<?php

namespace Modules\User\tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\app\Models\User;
use Tests\TestCase;

class ProfileUnitTest extends TestCase
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

    public function test_Invalid_reset_password_With_Incorrect_Email()
    {
        $response = $this->postJson('api/v1/password/reset', [
            'username_email' => 'wrongemail@example.com',
            'password' => 'ValidPass12346!',
        ]);

        $response->assertStatus(422);

        $this->assertEquals('Username or email is invalid', $response['message']);
    }

    public function test_Invalid_reset_password_With_Incorrect_Username()
    {
        $response = $this->postJson('api/v1/password/reset', [
            'username_email' => 'wrongUsername',
            'password' => 'ValidPass12346!',
        ]);

        $response->assertStatus(422);

        $this->assertEquals('Username or email is invalid', $response['message']);
    }
}
