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

        // php artisan db:seed --class=RolesTableSeeder
        $this->call(RolesTableSeeder::class);

        // php artisan db:seed --class=DepartmentsTableSeeder
        $this->call(DepartmentsTableSeeder::class);

        // php artisan db:seed --class=DivisionsTableSeeder
        $this->call(DivisionsTableSeeder::class);

        // php artisan db:seed --class=LocationsTableSeeder
        $this->call(LocationsTableSeeder::class);

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

        // Users for roles 1 to 16
        $roles = [
            1 => ['Ketua Umum', 'ketua.umum@example.com'],
            2 => ['Bendahara', 'bendahara@example.com'],
            3 => ['Human Resource', 'hr@example.com'],
            4 => ['Sekretaris', 'sekretaris@example.com'],
            5 => ['Ketua Departemen Mechanic', 'mechanic@example.com'],
            6 => ['Ketua Departemen Electric', 'electric@example.com'],
            7 => ['Ketua Departemen Management', 'management@example.com'],
            8 => ['Ketua Divisi Composite', 'composite@example.com'],
            9 => ['Ketua Divisi Vehicle Dynamic', 'vehicle.dynamic@example.com'],
            10 => ['Ketua Divisi Metal Work', 'metal.work@example.com'],
            11 => ['Ketua Divisi Low Voltage', 'low.voltage@example.com'],
            12 => ['Ketua Divisi High Voltage', 'high.voltage@example.com'],
            13 => ['Ketua Divisi Programming', 'programming@example.com'],
            14 => ['Ketua Divisi Administrasi', 'administrasi@example.com'],
            15 => ['Ketua Divisi Public Relation', 'public.relation@example.com'],
            16 => ['Ketua Divisi Content Creator', 'content.creator@example.com'],
        ];

        foreach ($roles as $roleId => [$name, $email]) {
            User::factory()->withRoleAndDivision($roleId, null, $name, $email)->create();
        }

        // Users for Staff role (role_id 17) with 5 users per division
        $divisions = range(1, 9); // Division IDs from 1 to 9

        foreach ($divisions as $divisionId) {
            foreach (range(1, 5) as $i) {
                User::factory()->withRoleAndDivision(17, $divisionId, "Staff {$divisionId} {$i}", "staff.{$divisionId}.{$i}@example.com")->create();
            }
        }
    }
}
