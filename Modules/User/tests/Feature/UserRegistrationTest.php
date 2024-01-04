<?php

namespace Modules\User\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_registers_a_new_user(): void
    {
        $userData = [
            'name_'=>'tester',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'Lkjhgfdsad12#',
            'country' => 'istanbul',
            'address' => 'uskudar',
            'contact_number' => '5070145054',
        ];
        $response = $this->postJson('api/v1/register', $userData);
        $response->assertStatus(201)
            ->assertJsonStructure([
                "status",
                "message",
                'data' => [
                    'user_id',
                    'name',
                    'username',
                    'email',
                    'country',
                    'address',
                    'contact_number',
                    'token',
                    'created_at',
                ],
            ]);
        $this->assertArrayHasKey('token',$response['data']);
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }

}
