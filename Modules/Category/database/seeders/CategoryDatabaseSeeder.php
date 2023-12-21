<?php

namespace Modules\Category\database\seeders;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryFactory::new()->count(5)->create();
    }
}
