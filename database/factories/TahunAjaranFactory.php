<?php

namespace Database\Factories;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TahunAjaran>
 */
class TahunAjaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;

        return [
            'semester' => fake()->randomElement(['Ganjil', 'Genap']),
            'status' => fake()->randomElement(['aktif', 'tidak_aktif']),
        ];
    }

    /**
     * Indicate that the tahun ajaran is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
