<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // php artisan db:seed --class=RolesTableSeeder
        $this->call(RolesTableSeeder::class);

        // php artisan db:seed --class=DepartmentsTableSeeder
        $this->call(DepartmentsTableSeeder::class);

        // php artisan db:seed --class=DivisionsTableSeeder
        $this->call(DivisionsTableSeeder::class);

        User::factory()->create([
            'name' => 'test',
            'email' => 'test@test',
            'role_id' => '17',
            'division_id' => '6',
        ]);
        
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin',
            'role_id' => '3',
            'division_id' => null,
        ]);
    }
}
