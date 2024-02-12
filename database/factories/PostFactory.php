<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Post\app\Models\Post::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => \Modules\User\app\Models\User::factory(),
            'category_id' => \Modules\Category\app\Models\Category::factory(),
            'pet_photo' => $this->faker->imageUrl(),
            'pet_type' => $this->faker->word,
            'pet_name' => $this->faker->word,
            'pet_gender' => $this->faker->colorName,
            'pet_age' => $this->faker->randomNumber(2),
            'pet_breed' => $this->faker->word,
            'pet_desc' => $this->faker->word,

        ];
    }
}
