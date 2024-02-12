<?php

namespace Modules\Post\tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Modules\Post\app\Models\Post;
use Modules\User\app\Models\User;
use Tests\TestCase;

class PostUnitTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        Auth::login($user);
    }

    public function test_it_requires_category_photo_type_name_gender_age_breed_desc(): void
    {
        $userData = [];
        $response = $this->postJson('api/v1/posts', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id', 'pet_photo', 'pet_type', 'pet_name',
                'pet_gender', 'pet_age', 'pet_breed', 'pet_desc'], 'data');

        $this->assertEquals(['The category id field is required.'], $response['data']['category_id']);
        $this->assertEquals(['The pet photo field is required.'], $response['data']['pet_photo']);
        $this->assertEquals(['The pet type field is required.'], $response['data']['pet_type']);
        $this->assertEquals(['The pet name field is required.'], $response['data']['pet_name']);
        $this->assertEquals(['The pet gender field is required.'], $response['data']['pet_gender']);
        $this->assertEquals(['The pet age field is required.'], $response['data']['pet_age']);
        $this->assertEquals(['The pet breed field is required.'], $response['data']['pet_breed']);
        $this->assertEquals(['The pet desc field is required.'], $response['data']['pet_desc']);
    }

    public function test_validation_rules_are_applied_correctly()
    {
        $invalidData = [
            'category_id' => 'invalid',
            'pet_photo' => 'not-a-photo',
            'pet_type' => '123',
            'pet_name' => '123',
            'pet_gender' => '123',
            'pet_age' => 'invalid-age',
            'pet_breed' => '123',
            'pet_desc' => '<script>alert("xss")</script>',
        ];
        $response = $this->postJson('api/v1/posts', $invalidData);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id', 'pet_photo', 'pet_type', 'pet_name',
                'pet_gender', 'pet_age', 'pet_breed', 'pet_desc'], 'data');

    }

    public function test_category_id_validation()
    {
        $post = Post::factory()->make()->toArray();

        $post['pet_photo'] = UploadedFile::fake()->image('pet_photo.jpg');

        $post['category_id'] = 'non-existent-id';

        $response = $this->postJson('api/v1/posts', $post);

        $response->assertStatus(422);

        $response->assertJson(['data' => ['category_id' => ['The selected category id is invalid.']]]);
    }

    public function test_pet_photo_validation()
    {
        $post = Post::factory()->make()->toArray();
        $post['pet_photo'] = 'invalid-file-type';

        $response = $this->postJson('api/v1/posts', $post);

        $response->assertStatus(422);

        $response->assertJsonPath('data.pet_photo', [
            'The pet photo field must be an image.',
            'The pet photo field must be a file of type: jpeg, png, jpg, gif.',
        ]);
    }

    public function test_pet_type_validation()
    {
        $post = Post::factory()->make()->toArray();
        $post['pet_photo'] = UploadedFile::fake()->image('pet_photo.jpg');
        $post['pet_type'] = '123';

        $response = $this->postJson('api/v1/posts', $post);

        $response->assertStatus(422);

        $response->assertJsonPath('data.pet_type', [
            'The pet type field format is invalid.',
        ]);
    }

    public function test_pet_name_validation()
    {
        $post = Post::factory()->make()->toArray();
        $post['pet_photo'] = UploadedFile::fake()->image('pet_photo.jpg');
        $post['pet_name'] = '123';

        $response = $this->postJson('api/v1/posts', $post);

        $response->assertStatus(422);
        $response->assertJsonPath('data.pet_name', [
            'The pet name field format is invalid.',
        ]);

    }

    public function test_pet_gender_validation()
    {

        $post = Post::factory()->make()->toArray();
        $post['pet_photo'] = UploadedFile::fake()->image('pet_photo.jpg');
        $post['pet_gender'] = '123';

        $response = $this->postJson('api/v1/posts', $post);

        $response->assertStatus(422);
        $response->assertJsonPath('data.pet_gender', [
            'The pet gender field format is invalid.',
        ]);
    }

    public function test_pet_age_validation()
    {
        $post = Post::factory()->make()->toArray();
        $post['pet_photo'] = UploadedFile::fake()->image('pet_photo.jpg');
        $post['pet_age'] = 'invalid-age'; // This triggers the validation errors

        $response = $this->postJson('api/v1/posts', $post);

        $response->assertStatus(422);
        $response->assertJsonPath('data.pet_age', [
            'The pet age field must be an integer.',
            'The pet age field must be between 1 and 2 digits.',
        ]);
    }

    public function test_pet_description_validation()
    {

        $post = Post::factory()->make()->toArray();
        $post['pet_photo'] = UploadedFile::fake()->image('pet_photo.jpg');
        $post['pet_desc'] = '<script>alert("xss")</script>';

        $response = $this->postJson('api/v1/posts', $post);

        $response->assertStatus(422);
        $response->assertJsonPath('data.pet_desc', [
            'The pet desc field format is invalid.',
        ]);
    }

    public function test_authorization()
    {
        Auth::logout();
        $userData = [

        ];

        $response = $this->postJson('api/v1/posts', $userData);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
