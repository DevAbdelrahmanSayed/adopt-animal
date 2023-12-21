<?php

namespace Modules\User\tests\Unit;

use Modules\User\app\Models\user;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginUnitTest extends TestCase
{

    use RefreshDatabase;
    public function test_login_with_short_password()
    {
        $user = User::create([
            'name'=>'tester',
            'username' => 'testuse32r',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
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

}
