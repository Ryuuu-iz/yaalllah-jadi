<?php

namespace Database\Factories;

use App\Models\DataSiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<DataSiswa>
 */
class DataSiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nisn' => fake()->unique()->numerify('##########'),
            'id_user' => User::where('role', 'siswa')->first()?->id_user ?: User::factory()->create(['role' => 'siswa'])->id_user,
        ];
    }
}
