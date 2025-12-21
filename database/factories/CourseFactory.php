<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\DataGuru;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(3),
            'deskripsi' => fake()->paragraph(),
            'tgl_upload' => now(),
            'id_mapel' => MataPelajaran::first()?->id_mapel ?: MataPelajaran::factory()->create()->id_mapel,
            'id_kelas' => Kelas::first()?->id_kelas ?: Kelas::factory()->create()->id_kelas,
            'id_TA' => TahunAjaran::first()?->id_TA ?: TahunAjaran::factory()->create()->id_TA,
            'id_guru' => DataGuru::first()?->id_guru ?: DataGuru::factory()->create()->id_guru,
        ];
    }
}
