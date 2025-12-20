<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<MataPelajaran>
 */
class MataPelajaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_mapel' => fake()->randomElement([
                'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika',
                'Kimia', 'Biologi', 'Sejarah', 'Geografi', 'Ekonomi',
                'Sosiologi', 'Seni Budaya', 'PJOK', 'Agama Islam', 'PKn'
            ]),
        ];
    }
}
