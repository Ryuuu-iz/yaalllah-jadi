<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kelas' => fake()->randomElement(['X-A', 'X-B', 'XI-A', 'XI-B', 'XII-A', 'XII-B', 'XII-C']),
            'tingkatan' => fake()->randomElement([10, 11, 12]),
        ];
    }
}
