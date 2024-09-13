<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('123123123'),
            'remember_token' => Str::random(10),
            'role_id' => 17, // Default to Staff
            'division_id' => null,
            'status' => 'active',
            'member_id' => strtoupper(Str::random(10)),
        ];
    }

    /**
     * Define specific roles and divisions.
     */
    public function withRoleAndDivision(int $roleId, ?int $divisionId = null, string $name = null, string $email = null): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name ?? $this->faker->name,
            'email' => $email ?? $this->faker->unique()->safeEmail,
            'role_id' => $roleId,
            'division_id' => $divisionId,
        ]);
    }
}

/*
'password' => static::$password ??= Hash::make('123123123'),
-- Users for roles with role_id 1-16 (1 user each)
INSERT INTO `users` (`name`, `email`, `password`, `role_id`, `status`) VALUES
('Ketua Umum', 'ketua.umum@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 1, 'active'),
('Bendahara', 'bendahara@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 2, 'active'),
('Human Resource', 'hr@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 3, 'active'),
('Sekretaris', 'sekretaris@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 4, 'active'),
('Ketua Departemen Mechanic', 'mechanic@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 5, 'active'),
('Ketua Departemen Electric', 'electric@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 6, 'active'),
('Ketua Departemen Management', 'management@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 7, 'active'),
('Ketua Divisi Composite', 'composite@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 8, 'active'),
('Ketua Divisi Vehicle Dynamic', 'vehicle.dynamic@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 9, 'active'),
('Ketua Divisi Metal Work', 'metal.work@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 10, 'active'),
('Ketua Divisi Low Voltage', 'low.voltage@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 11, 'active'),
('Ketua Divisi High Voltage', 'high.voltage@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 12, 'active'),
('Ketua Divisi Programming', 'programming@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 13, 'active'),
('Ketua Divisi Administrasi', 'administrasi@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 14, 'active'),
('Ketua Divisi Public Relation', 'public.relation@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 15, 'active'),
('Ketua Divisi Content Creator', 'content.creator@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 16, 'active');

-- Users for role_id 17 (Staff) with 5 users per division
INSERT INTO `users` (`name`, `email`, `password`, `role_id`, `division_id`, `status`) VALUES
('Staff Composite 1', 'staff.composite1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 1, 'active'),
('Staff Composite 2', 'staff.composite2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 1, 'active'),
('Staff Composite 3', 'staff.composite3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 1, 'active'),
('Staff Composite 4', 'staff.composite4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 1, 'active'),
('Staff Composite 5', 'staff.composite5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 1, 'active'),

('Staff Vehicle Dynamic 1', 'staff.vehicle.dynamic1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 2, 'active'),
('Staff Vehicle Dynamic 2', 'staff.vehicle.dynamic2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 2, 'active'),
('Staff Vehicle Dynamic 3', 'staff.vehicle.dynamic3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 2, 'active'),
('Staff Vehicle Dynamic 4', 'staff.vehicle.dynamic4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 2, 'active'),
('Staff Vehicle Dynamic 5', 'staff.vehicle.dynamic5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 2, 'active'),

('Staff Metal Work 1', 'staff.metal.work1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 3, 'active'),
('Staff Metal Work 2', 'staff.metal.work2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 3, 'active'),
('Staff Metal Work 3', 'staff.metal.work3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 3, 'active'),
('Staff Metal Work 4', 'staff.metal.work4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 3, 'active'),
('Staff Metal Work 5', 'staff.metal.work5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 3, 'active'),

('Staff Low Voltage 1', 'staff.low.voltage1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 4, 'active'),
('Staff Low Voltage 2', 'staff.low.voltage2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 4, 'active'),
('Staff Low Voltage 3', 'staff.low.voltage3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 4, 'active'),
('Staff Low Voltage 4', 'staff.low.voltage4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 4, 'active'),
('Staff Low Voltage 5', 'staff.low.voltage5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 4, 'active'),

('Staff High Voltage 1', 'staff.high.voltage1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 5, 'active'),
('Staff High Voltage 2', 'staff.high.voltage2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 5, 'active'),
('Staff High Voltage 3', 'staff.high.voltage3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 5, 'active'),
('Staff High Voltage 4', 'staff.high.voltage4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 5, 'active'),
('Staff High Voltage 5', 'staff.high.voltage5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 5, 'active'),

('Staff Programming 1', 'staff.programming1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 6, 'active'),
('Staff Programming 2', 'staff.programming2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 6, 'active'),
('Staff Programming 3', 'staff.programming3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 6, 'active'),
('Staff Programming 4', 'staff.programming4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 6, 'active'),
('Staff Programming 5', 'staff.programming5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 6, 'active'),

('Staff Administrasi 1', 'staff.administrasi1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 7, 'active'),
('Staff Administrasi 2', 'staff.administrasi2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 7, 'active'),
('Staff Administrasi 3', 'staff.administrasi3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 7, 'active'),
('Staff Administrasi 4', 'staff.administrasi4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 7, 'active'),
('Staff Administrasi 5', 'staff.administrasi5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 7, 'active'),

('Staff Public Relation 1', 'staff.public.relation1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 8, 'active'),
('Staff Public Relation 2', 'staff.public.relation2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 8, 'active'),
('Staff Public Relation 3', 'staff.public.relation3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 8, 'active'),
('Staff Public Relation 4', 'staff.public.relation4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 8, 'active'),
('Staff Public Relation 5', 'staff.public.relation5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 8, 'active'),

('Staff Content Creator 1', 'staff.content.creator1@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 9, 'active'),
('Staff Content Creator 2', 'staff.content.creator2@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 9, 'active'),
('Staff Content Creator 3', 'staff.content.creator3@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 9, 'active'),
('Staff Content Creator 4', 'staff.content.creator4@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 9, 'active'),
('Staff Content Creator 5', 'staff.content.creator5@example.com', '$2y$12$X7IykLcAo7fyzZI.uubzTuqYMOOc/k7gbXx0gLi/0BZYTiYdXauAy', 17, 9, 'active');

*/