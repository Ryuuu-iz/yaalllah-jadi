<?php

namespace Database\Factories;

use App\Models\PengumpulanTugas;
use App\Models\DataSiswa;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<PengumpulanTugas>
 */
class PengumpulanTugasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tglPengumpulan = now()->subDays(fake()->numberBetween(0, 14));

        return [
            'tgl_pengumpulan' => $tglPengumpulan,
            'file_pengumpulan' => null,
            'nilai' => fake()->optional(0.7)->numberBetween(60, 100), // 70% chance of having a grade
            'status' => $tglPengumpulan->gt(Tugas::first()?->deadline ?: now()) ? 'terlambat' : 'tepat_waktu',
            'id_siswa' => DataSiswa::first()?->id_siswa ?: DataSiswa::factory()->create()->id_siswa,
            'id_tugas' => Tugas::first()?->id_tugas ?: Tugas::factory()->create()->id_tugas,
        ];
    }
}
