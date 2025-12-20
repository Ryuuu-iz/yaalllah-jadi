<?php

namespace Database\Factories;

use App\Models\DataGuru;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<DataGuru>
 */
class DataGuruFactory extends Factory
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
            'nip' => fake()->unique()->numerify('######'),
            'id_user' => User::where('role', 'guru')->first()?->id_user ?: User::factory()->create(['role' => 'guru'])->id_user,
        ];
    }
}
