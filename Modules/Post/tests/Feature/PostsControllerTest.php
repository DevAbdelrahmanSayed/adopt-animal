<?php

namespace Modules\Post\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        $user = User::factory()->create();
        Auth::login($user);
    }

    public function test_can_retrieve_posts()
    {
        Post::factory()->count(5)->create();

        $response = $this->get('api/v1/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'category',
                        'pet_photo',
                        'pet_type',
                        'pet_name',
                        'pet_gender',
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


    public function test_can_update_post()
    {
        // Create a post with all necessary fields using the factory
        $post = Post::factory()->create();

        $newData = [
            'category_id' => \Modules\Category\app\Models\Category::factory()->create()->id, // Create a new category for the update
            'pet_photo' => UploadedFile::fake()->image('updated_photo.jpg'), // Create a fake updated photo
            'pet_type' => 'Updated Pet Type',
            'pet_name' => 'Updated Pet Name',
            'pet_gender' => 'Updated Pet Color',
            'pet_age' => 5, // Example age
            'pet_breed' => 'Updated Pet Breed',
        ];

        $response = $this->put("api/v1/posts/{$post->id}", $newData);

        $response->assertStatus(200)
            ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'user_id',
                'category',
                'pet_photo',
                'pet_type',
                'pet_name',
                'pet_gender',
                'pet_age',
                'pet_breed',
                'contact_number',
                'country',
                'address',
                'created_at',
                'updated_at',
            ],
        ]);
    }

}
