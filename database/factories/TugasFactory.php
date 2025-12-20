<?php

namespace Database\Factories;

use App\Models\Tugas;
use App\Models\Course;
use App\Models\MateriPembelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Tugas>
 */
class TugasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_tugas' => fake()->sentence(4),
            'desk_tugas' => fake()->paragraph(),
            'file_tugas' => null,
            'tgl_upload' => now(),
            'deadline' => now()->addDays(fake()->numberBetween(7, 30)),
            'id_course' => Course::first()?->id_tugas ?: Course::factory()->create()->id_course,
            'id_materi' => MateriPembelajaran::first()?->id_materi ?: MateriPembelajaran::factory()->create()->id_materi,
        ];
    }
}
