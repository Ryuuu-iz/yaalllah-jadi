<?php

namespace App\Services;

use App\Models\DataSiswa;
use App\Models\DataGuru;
use App\Models\Course;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getAdminDashboardStats()
    {
        // Gabungkan semua count query menjadi satu operasi database
        $counts = DB::select("
            SELECT
                (SELECT COUNT(*) FROM data_guru) as total_guru,
                (SELECT COUNT(*) FROM data_siswa) as total_siswa,
                (SELECT COUNT(*) FROM course) as total_courses,
                (SELECT COUNT(*) FROM mata_pelajaran) as total_subjects,
                (SELECT COUNT(*) FROM kelas) as total_classes
        ");

        $result = $counts[0];
        return [
            'totalGuru' => $result->total_guru,
            'totalSiswa' => $result->total_siswa,
            'totalCourses' => $result->total_courses,
            'totalSubjects' => $result->total_subjects,
            'totalClasses' => $result->total_classes,
        ];
    }

    public function getActiveAcademicYear()
    {
        $activeAcademicYear = TahunAjaran::where('is_active', true)->first();
        return $activeAcademicYear ? $activeAcademicYear->tahun_ajaran : '2025 - 2026';
    }
}