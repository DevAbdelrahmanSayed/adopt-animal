<?php

namespace Modules\Post\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Modules\Post\app\Models\Post;
use Modules\User\app\Models\User;
use Tests\TestCase;

class PostsControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $user= User::factory()->create();
        Auth::login($user);
    }

        public function test_can_retrieve_posts()
    {

        // Create some test posts
        Post::factory()->count(5)->create();

        $response = $this->get('api/v1/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'category',
                        'photo',
                        'pet_type',
                        'pet_name',
                        'pet_color',
                        'pet_age',
                        'pet_breed',
                        'contact_number',
                        'country',
                        'address',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }
}
