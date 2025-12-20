<?php

namespace Database\Factories;

use App\Models\RekapAbsensi;
use App\Models\DataSiswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\DataGuru;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<RekapAbsensi>
 */
class RekapAbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanggal' => now()->subDays(fake()->numberBetween(0, 30)),
            'deadline' => now()->addDays(fake()->numberBetween(1, 7)),
            'is_open' => fake()->boolean(),
            'status_absensi' => fake()->randomElement(['hadir', 'izin', 'sakit', 'alpha']),
            'keterangan' => fake()->optional()->sentence(),
            'id_siswa' => DataSiswa::first()?->id_siswa ?: DataSiswa::factory()->create()->id_siswa,
            'id_kelas' => Kelas::first()?->id_kelas ?: Kelas::factory()->create()->id_kelas,
            'id_guru' => DataGuru::first()?->id_guru ?: DataGuru::factory()->create()->id_guru,
            'id_mapel' => MataPelajaran::first()?->id_mapel ?: MataPelajaran::factory()->create()->id_mapel,
        ];
    }
}
