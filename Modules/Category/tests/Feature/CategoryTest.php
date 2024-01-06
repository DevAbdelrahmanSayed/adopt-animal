<?php

namespace Modules\Category\tests\Feature;

use Modules\Category\app\Models\Category;
use Modules\Post\app\Models\Post;
use Modules\User\app\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->Post =  Post::factory()->create([
            'user_id'=> $this->user,
            'category_id'=>$this->category

        ]);
    }

    public function test_index_returns_all_categories()
    {
        $this->actingAs($this->user);
        $response = $this->getJson('api/v1/categories'); // Replace with your actual route

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Categories retrieved successfully',
            ]);
    }

    public function test_index_with_no_categories()
    {
        $this->actingAs($this->user);

        Category::query()->delete();

        $response = $this->getJson('api/v1/categories');

        $response->assertStatus(200)
            ->assertJson(['message' => 'No Categories exist']);
    }

    public function test_show_returns_posts_for_category()
    {

        $this->actingAs($this->user);
        $category = Category::first();

        $response = $this->getJson('api/v1/categories/' . $category->id.'/posts');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Posts retrieved successfully']);
    }

    public function test_show_with_non_existent_category()
    {
        $this->actingAs($this->user);

        $nonExistentCategoryId = 999;

        $response = $this->getJson('api/v1/categories/' . $nonExistentCategoryId.'/posts');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Category not found']);
    }

    public function test_show_with_no_posts_in_category()
    {
        $this->actingAs($this->user);
        $category = Category::first();

        $category->posts()->delete();

        $response = $this->getJson('api/v1/categories/' . $category->id.'/posts');

        $response->assertStatus(200)
            ->assertJson(['message' => 'No posts exist for this category']);
    }

}
