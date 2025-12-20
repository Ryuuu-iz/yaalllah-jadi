<?php

namespace Database\Factories;

use App\Models\MateriPembelajaran;
use App\Models\Course;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<MateriPembelajaran>
 */
class MateriPembelajaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_materi' => fake()->sentence(3),
            'desk_materi' => fake()->paragraph(),
            'id_TA' => TahunAjaran::first()?->id_TA ?: TahunAjaran::factory()->create()->id_TA,
            'id_course' => Course::first()?->id_course ?: Course::factory()->create()->id_course,
        ];
    }
}
