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
            'name'=>'tester',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
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

    public function test_it_requires_name_username_email_password(): void
    {
        $userData = [];
        $response = $this->postJson('api/v1/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name','username', 'email', 'password'], 'data');
        // Check specific error messages for 'name'
        $this->assertEquals(['The name field is required.'], $response['data']['name']);
        // Check specific error messages for 'username'
        $this->assertEquals(['The username field is required.'], $response['data']['username']);

        // Check specific error messages for 'email'
        $this->assertEquals(['The email field is required.'], $response['data']['email']);

        // Check specific error messages for 'password'
        $this->assertEquals(['The password field is required.'], $response['data']['password']);
    }

    public function test_it_requires_unique_username_email(): void
    {
        $userData = [
            'name'=>'tester',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
        ];
        $response = $this->postJson('api/v1/register', $userData);
        $response->assertStatus(201);
        // Second registration attempt with the same data
        $response = $this->postJson('api/v1/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'email'], 'data');

        // Check specific error messages for 'username'
        $this->assertEquals(['The username has already been taken.'], $response['data']['username']);

        // Check specific error messages for 'email'
        $this->assertEquals(['The email has already been taken.'], $response['data']['email']);

    }
    public function test_it_requires_strong_password(): void
    {
        $userData = [
            'name'=>'tester',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'weak',
        ];

        $response = $this->postJson('api/v1/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'], 'data');

        // Check specific error messages for 'password'
        $this->assertEquals(['The password field must be at least 8 characters.'], $response['data']['password']);
    }







}
