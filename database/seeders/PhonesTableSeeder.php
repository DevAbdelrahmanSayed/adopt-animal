<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Modules\User\app\Models\Phone;

class PhonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('/home/abed/Downloads/CountryCodes.json');
        $countries = json_decode($json);

        foreach ($countries as $country) {
            Phone::create([
                'name' => $country->name,
                'dial_code' => $country->dial_code,
                'iso_code' => $country->code,
            ]);
        }
    }
}
