<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\User\app\Models\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('/home/abed/Downloads/countries.json');
        $countries = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON Decode Error: ' . json_last_error_msg());
        }

        foreach ($countries as $country) {
            Country::create([
                'name' => $country->name,
                'code' => $country->code,
            ]);
        }
    }
}
