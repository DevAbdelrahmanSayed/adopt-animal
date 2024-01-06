<?php

namespace Modules\Favorite\tests\Feature;

use Modules\Post\app\Models\Post;
use Modules\User\app\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user and posts
        $this->user = User::factory()->create();
        Post::factory()->count(5)->create();
    }

    public function test_index_retrieves_favorites_successfully()
    {

        $this->actingAs($this->user);

        $response = $this->getJson('api/v1/favorites');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Favorites retrieved successfully']);
    }

    public function test_add_favorite_successfully()
    {

        $this->actingAs($this->user);
        $post = Post::first();

        $response = $this->postJson('api/v1/posts/'. $post->id .'/favorite');
        $response->assertStatus(201)
            ->assertJson(['message' => 'Favorite added successfully']);
    }

    public function test_add_favorite_already_favorited()
    {
        $this->actingAs($this->user);
        $post = Post::first();
        $this->user->favorites()->attach($post);

        $response = $this->postJson('api/v1/posts/'. $post->id .'/favorite');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Error: Post already favorited']);
    }

    public function test_remove_favorite_successfully()
    {
        $this->actingAs($this->user);
        $post = Post::first();
        $this->user->favorites()->attach($post);

        $response = $this->deleteJson('api/v1/posts/'. $post->id .'/favorite');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Favorite deleted successfully']);
    }
}

