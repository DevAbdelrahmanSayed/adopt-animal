<?php

namespace Modules\Post\database\seeders;

use Database\Factories\PostFactory;
use Illuminate\Database\Seeder;

class PostDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PostFactory::new()->count(5)->create();
    }
}
