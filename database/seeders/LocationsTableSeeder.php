<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            'name' => 'UASC EV UII',
            'latitude' => -7.686263,
            'longitude' => 110.409984,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
