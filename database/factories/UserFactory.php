<?php

namespace Database\Factories;

use App\Models\User
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->userName(),
            'password' => Hash::make('password'),
            'role' => fake()->randomElement(['admin', 'guru', 'siswa']),
            'foto_profile' => null,
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a teacher.
     */
    public function guru(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'guru',
        ]);
    }

    /**
     * Indicate that the user is a student.
     */
    public function siswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'siswa',
        ]);
    }
}
