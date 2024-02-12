<?php

namespace Modules\Search\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Category\app\Models\Category;
use Modules\Post\app\Models\Post;
use Modules\User\app\Models\User;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $category = Category::factory()->create();

        Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'pet_photo' => 'labrador_photo.jpg',
            'pet_type' => 'Dog',
            'pet_desc' => 'Friendly and energetic',
            'pet_name' => 'Buddy',
            'pet_gender' => 'Male',
            'pet_age' => '2',
            'pet_breed' => 'Labrador',
        ]);

        Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'pet_photo' => 'siamese_photo.jpg',
            'pet_type' => 'Cat',
            'pet_desc' => 'Quiet and affectionate',
            'pet_name' => 'Whiskers',
            'pet_gender' => 'Female',
            'pet_age' => '3',
            'pet_breed' => 'Siamese',
        ]);
        // Create more posts as needed for diverse search scenarios
    }

    public function test_search_finds_relevant_posts_by_breed()
    {
        $this->actingAs($this->user);
        $searchTerm = 'Labrador';

        $response = $this->getJson('api/v1/search?text='.$searchTerm);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Search results',
            ])
            ->assertJsonFragment([
                'pet_breed' => 'Labrador',
                'pet_name' => 'Buddy',
            ]);

        // Check if the correct number of posts are returned
        $responseData = $response->json('data');
        $this->assertCount(1, $responseData);
    }

    public function test_search_finds_relevant_posts_by_type()
    {
        $this->actingAs($this->user);
        $searchTerm = 'Cat';

        $response = $this->getJson('api/v1/search?text='.$searchTerm);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Search results',
            ])
            ->assertJsonFragment([
                'pet_breed' => 'Siamese',
                'pet_name' => 'Whiskers',
            ]);

        // Check if the correct number of posts are returned
        $responseData = $response->json('data');
        $this->assertCount(1, $responseData);
    }

    public function test_search_finds_relevant_posts_by_name()
    {
        $this->actingAs($this->user);
        $searchTerm = 'Whiskers';

        $response = $this->getJson('api/v1/search?text='.$searchTerm);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Search results',
            ])
            ->assertJsonFragment([
                'pet_breed' => 'Siamese',
                'pet_name' => 'Whiskers',
            ]);

        // Check if the correct number of posts are returned
        $responseData = $response->json('data');
        $this->assertCount(1, $responseData);
    }

    // Additional tests for other search terms and scenarios
}
