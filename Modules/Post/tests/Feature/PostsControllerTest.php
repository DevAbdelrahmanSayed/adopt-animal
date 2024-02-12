<?php

namespace Modules\Post\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Category\app\Models\Category;
use Modules\Post\app\Models\Post;
use Modules\User\app\Models\User;
use Tests\TestCase;

class PostsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    public function test_can_retrieve_posts()
    {
        Post::factory()->create();

        $response = $this->getJson('api/v1/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'category',
                        'pet_photo',
                        'owner_name',
                        'pet_favorite',
                        'pet_type',
                        'pet_name',
                        'pet_gender',
                        'pet_age',
                        'pet_breed',
                        'pet_desc',
                        'contact_number',
                        'country',
                        'address',
                        'created_at',

                    ],
                ],
            ]);

    }

    public function test_can_create_post()
    {
        $category = Category::factory()->create();

        $postData = [
            'category_id' => $category->id,
            'pet_photo' => UploadedFile::fake()->image('pet_photo.jpg'),
            'pet_type' => 'Dog',
            'pet_name' => 'Buddy',
            'pet_gender' => 'Male',
            'pet_age' => 3,
            'pet_breed' => 'Labrador',
            'pet_desc' => 'description',
        ];
        $response = $this->postJson('api/v1/posts', $postData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'post_id',
                ],
            ]);

        $this->assertDatabaseHas('posts', ['pet_name' => 'Buddy']);
    }

    public function test_Destroy_Post()
    {

        $post = Post::factory()->create();

        Storage::fake('public');

        $fakeFile = UploadedFile::fake()->image('fake_image.jpg');
        $post->pet_photo = $fakeFile;
        $post->save();

        $response = $this->deleteJson("/api/v1/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Post deleted successfully']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);

        Storage::disk('public')->assertMissing('pet_photos/'.$fakeFile->hashName());
    }

    public function test_Show_Post()
    {

        $post = Post::factory()->create();

        $response = $this->getJson("/api/v1/posts/{$post->id}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'category',
                'owner_name',
                'pet_photo',
                'pet_favorite',
                'pet_type',
                'pet_name',
                'pet_gender',
                'pet_age',
                'pet_breed',
                'pet_desc',
                'contact_number',
                'country',
                'address',
                'created_at',
            ],
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'category_id' => $post->category_id,
        ]);
    }

    public function test_Show_User_Posts()
    {

        $posts = Post::factory()->count(5)->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/v1/user/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'category',
                        'pet_photo',
                        'owner_name',
                        'pet_favorite',
                        'pet_type',
                        'pet_name',
                        'pet_gender',
                        'pet_age',
                        'pet_breed',
                        'pet_desc',
                        'contact_number',
                        'country',
                        'address',
                        'created_at',
                    ],
                ],
            ]);
    }
}
