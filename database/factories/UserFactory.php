<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = \Modules\User\app\Models\User::class;

    public function definition(): array
    {
        return [
            'name_' => $this->faker->name,
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'country' => $this->faker->country,
            'address' => $this->faker->address,
            'contact_number' => $this->faker->phoneNumber,

        ];
    }
}
