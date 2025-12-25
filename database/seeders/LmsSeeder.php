<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use App\Models\DataGuru;
use App\Models\DataSiswa;
use App\Models\Course;
use App\Models\Tugas;
use App\Models\MateriPembelajaran;
use App\Models\RekapAbsensi;
use App\Models\PengumpulanTugas;
use Illuminate\Database\Seeder;

class LmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tahun Ajaran
        $tahunAjaran = TahunAjaran::factory()->create();

        // 2. Kelas
        $kelas = Kelas::factory(6)->create();

        // 3. Mata Pelajaran
        $mataPelajaran = MataPelajaran::factory(10)->create();

        // 4. Admin User - hanya buat jika belum ada
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => bcrypt('admin123'),
                'role' => 'admin'
            ]
        );

         $siswa = User::firstOrCreate(
            ['username' => 'siswa'],
            [
                'password' => bcrypt('siswa123'),
                'role' => 'siswa'
            ]
        );

        // Create DataSiswa for the default 'siswa' user if it doesn't exist
        if (!$siswa->dataSiswa) {
            DataSiswa::factory()->create(['id_user' => $siswa->id_user]);
        }

         $guru = User::firstOrCreate(
            ['username' => 'guru'],
            [
                'password' => bcrypt('guru123'),
                'role' => 'guru'
            ]
        );

        // Create DataGuru for the default 'guru' user if it doesn't exist
        if (!$guru->dataGuru) {
            DataGuru::factory()->create(['id_user' => $guru->id_user]);
        }

        // 5. Guru Users and DataGuru - hanya buat jika belum cukup
        $existingGuruCount = User::where('role', 'guru')->count();
        $guruUsers = collect();
        if ($existingGuruCount < 5) {
            $newGuruCount = 5 - $existingGuruCount;
            $guruUsers = User::factory($newGuruCount)->create(['role' => 'guru']);
            $guruUsers->each(function ($user) {
                // Check if DataGuru already exists for this user to avoid duplicates
                if (!$user->dataGuru) {
                    DataGuru::factory()->create(['id_user' => $user->id_user]);
                }
            });
        }

        // 6. Siswa Users and DataSiswa - hanya buat jika belum cukup
        $existingSiswaCount = User::where('role', 'siswa')->count();
        $siswaUsers = collect();
        if ($existingSiswaCount < 30) {
            $newSiswaCount = 30 - $existingSiswaCount;
            $siswaUsers = User::factory($newSiswaCount)->create(['role' => 'siswa']);
            $siswaUsers->each(function ($user) {
                // Check if DataSiswa already exists for this user to avoid duplicates
                if (!$user->dataSiswa) {
                    DataSiswa::factory()->create(['id_user' => $user->id_user]);
                }
            });
        }

        // 6.5 Ensure all existing users have corresponding data records
        // This handles the case where users were created outside the seeder
        $allGuruUsers = User::where('role', 'guru')->get();
        foreach ($allGuruUsers as $guruUser) {
            if (!$guruUser->dataGuru) {
                DataGuru::factory()->create(['id_user' => $guruUser->id_user]);
            }
        }

        $allSiswaUsers = User::where('role', 'siswa')->get();
        foreach ($allSiswaUsers as $siswaUser) {
            if (!$siswaUser->dataSiswa) {
                DataSiswa::factory()->create(['id_user' => $siswaUser->id_user]);
            }
        }

        // 7. Courses
        $guruIds = DataGuru::pluck('id_guru');
        $kelasIds = $kelas->pluck('id_kelas');
        $mataPelajaranIds = $mataPelajaran->pluck('id_mapel');

        $courses = collect();
        foreach ($guruIds as $guruId) {
            foreach ($kelasIds as $kelasId) {
                foreach ($mataPelajaranIds as $mapelId) {
                    // Hanya buat beberapa kombinasi untuk menghindari terlalu banyak data
                    if (rand(1, 3) === 1) { // 1 dari 3 kemungkinan
                        $courses->push(Course::factory()->create([
                            'id_guru' => $guruId,
                            'id_kelas' => $kelasId,
                            'id_mapel' => $mapelId,
                            'id_TA' => $tahunAjaran->id_TA,
                        ]));
                    }
                }
            }
        }

        // 8. Enroll siswa ke course
        $siswaIds = DataSiswa::pluck('id_siswa');
        $courses->each(function ($course) use ($siswaIds) {
            // Ambil sebagian siswa acak untuk di enroll ke setiap course
            $randomSiswaIds = $siswaIds->random(rand(5, 15))->toArray();
            $course->siswa()->attach($randomSiswaIds, ['enrolled_at' => now()]);
        });

        // 9. Tugas
        $courses->each(function ($course) {
            // Buat materi pembelajaran untuk course ini
            $materi = MateriPembelajaran::factory()->create([
                'id_course' => $course->id_course
            ]);

            // Buat beberapa tugas untuk setiap course
            Tugas::factory(rand(2, 5))->create([
                'id_course' => $course->id_course,
                'id_materi' => $materi->id_materi,
            ]);
        });

        // 10. Rekap Absensi
        $siswaIds = DataSiswa::pluck('id_siswa');
        $kelasIds = $kelas->pluck('id_kelas');
        $mataPelajaranIds = $mataPelajaran->pluck('id_mapel');
        $guruIds = DataGuru::pluck('id_guru');

        foreach ($siswaIds as $siswaId) {
            foreach (range(1, 5) as $i) { // Buat beberapa entri absensi per siswa
                RekapAbsensi::factory()->create([
                    'id_siswa' => $siswaId,
                    'id_kelas' => $kelasIds->random(),
                    'id_guru' => $guruIds->random(),
                    'id_mapel' => $mataPelajaranIds->random(),
                ]);
            }
        }

        // 11. Pengumpulan Tugas
        $tugasIds = Tugas::pluck('id_tugas');
        $siswaIds = DataSiswa::pluck('id_siswa');

        foreach ($tugasIds as $tugasId) {
            // Ambil sebagian siswa untuk mengumpulkan tugas
            $randomSiswaIds = $siswaIds->random(rand(10, 20))->toArray();
            foreach ($randomSiswaIds as $siswaId) {
                PengumpulanTugas::factory()->create([
                    'id_siswa' => $siswaId,
                    'id_tugas' => $tugasId,
                ]);
            }
        }
    }
}
