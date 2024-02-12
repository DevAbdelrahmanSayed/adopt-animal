<?php

namespace Modules\User\tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_it_requires_name_username_email_country_address_contactNumber_password(): void
    {
        $userData = [];
        $response = $this->postJson('api/v1/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name_', 'username', 'email', 'country', 'address', 'contact_number', 'password'], 'data');

        $this->assertEquals(['The name  field is required.'], $response['data']['name_']);
        $this->assertEquals(['The username field is required.'], $response['data']['username']);
        $this->assertEquals(['The email field is required.'], $response['data']['email']);
        $this->assertEquals(['The password field is required.'], $response['data']['password']);
        $this->assertEquals(['The contact number field is required.'], $response['data']['contact_number']);
        $this->assertEquals(['The address field is required.'], $response['data']['address']);
        $this->assertEquals(['The country field is required.'], $response['data']['country']);
    }

    public function test_it_requires_unique_username_email_contact_number(): void
    {
        $userData = [
            'name_' => 'tester',
            'username' => 'testuser',
            'email' => 'test@gmail.com',
            'password' => 'Lkjhgfdsad12#',
            'country' => 'istanbul',
            'address' => 'uskudar',
            'contact_number' => '5070145054',
        ];
        $response = $this->postJson('api/v1/register', $userData);

        $response->assertStatus(201);

        // Second registration attempt with the same data
        $response = $this->postJson('api/v1/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'email'], 'data');

        $this->assertEquals(['The username has already been taken.'], $response['data']['username']);

        // Check specific error messages for 'email'
        $this->assertEquals(['The email has already been taken.'], $response['data']['email']);

        $this->assertEquals(['The contact number has already been taken.'], $response['data']['contact_number']);

    }

    public function test_validation_for_special_characters_in_name_username_email_country_address_contactNumber_password(): void
    {
        $userData = [
            'name_' => 'tester<',
            'username' => 'tester</',
            'email' => 'test/@gmail.com',
            'password' => 'Lkjhgfds2#<scr/',
            'country' => 'istanbul/?',
            'address' => 'uskudar/?>',
            'contact_number' => '5070145054/',
        ];
        $response = $this->postJson('api/v1/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name_', 'username', 'email', 'country', 'address', 'contact_number', 'password'], 'data');

        $this->assertEquals(['The name  field format is invalid.'], $response['data']['name_']);
        $this->assertEquals(['The username field format is invalid.'], $response['data']['username']);
        $this->assertEquals(['The email field format is invalid.'], $response['data']['email']);
        $this->assertEquals(['The password field format is invalid.'], $response['data']['password']);
        $this->assertEquals(['The contact number field format is invalid.'], $response['data']['contact_number']);
        $this->assertEquals(['The address field format is invalid.'], $response['data']['address']);
        $this->assertEquals(['The country field format is invalid.'], $response['data']['country']);
    }

    public function test_password_minimum_length()
    {
        $response = $this->registerWithPassword('Ab1!');
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'], 'data');
        $this->assertEquals(['The password field must be at least 8 characters.'], $response['data']['password']);
    }

    public function test_password_mixed_case()
    {
        $response = $this->registerWithPassword('abcdefghi1#');
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'], 'data');
        $this->assertEquals(['The password field must contain at least one uppercase and one lowercase letter.'], $response['data']['password']);
    }

    public function test_password_includes_number()
    {
        $response = $this->registerWithPassword('Abcdefgh#');
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'], 'data');
        $this->assertEquals(['The password field must contain at least one number.'], $response['data']['password']);
    }

    public function test_password_includes_symbol()
    {
        $response = $this->registerWithPassword('Abcdefgh1');
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'], 'data');
        $this->assertEquals(['The password field must contain at least one symbol.'], $response['data']['password']);
    }

    private function registerWithPassword($password)
    {
        return $this->postJson('api/v1/register', [
            'name_' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => $password,
            'country' => 'Test Country',
            'address' => 'Test Address',
            'contact_number' => '1234567890',
        ]);
    }
}
