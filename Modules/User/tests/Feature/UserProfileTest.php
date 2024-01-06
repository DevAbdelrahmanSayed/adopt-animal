<?php

namespace Modules\User\tests\Feature;

use Modules\User\app\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileTest extends TestCase
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
    public function test_user_profile_can_be_retrieved(): void
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->getJson('api/v1/profiles');


        $response->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                'data' => [
                    'user_id',
                    'name',
                    'username',
                    'email',
                    'contact_number',
                    'country',
                    'address',
                    'created_at',

                ],
            ]);
        $this->assertEquals('User data retrieved successfully', $response['message']);
    }
    public function test_password_can_be_reset_with_username(): void
    {

        $response = $this->postJson('api/v1/password/reset', [
            'username_email' => 'validusername',
            'password' => 'ValidPass12346!'
        ]);
        $response->assertStatus(201);

        $this->assertEquals('Password updated Successfully', $response['message']);
    }
    public function test_password_can_be_reset_with_email(): void
    {

        $response = $this->postJson('api/v1/password/reset', [
            'username_email' => 'validemail@example.com',
            'password' => 'ValidPass12346!'
        ]);
        $response->assertStatus(201);

        $this->assertEquals('Password updated Successfully', $response['message']);
    }

}
