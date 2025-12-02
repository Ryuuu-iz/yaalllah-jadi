<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\RekapAbsensi;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->dataGuru;
        
        // Statistik
        $totalCourses = Course::where('id_guru', $guru->id_guru)->count();
        $totalTugas = Tugas::whereHas('course', function($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })->count();
        
        $totalPengumpulan = PengumpulanTugas::whereHas('tugas.course', function($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })->count();
        
        $pengumpulanBelumDinilai = PengumpulanTugas::whereHas('tugas.course', function($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })->whereNull('nilai')->count();
        
        // Course terbaru
        $recentCourses = Course::where('id_guru', $guru->id_guru)
            ->with(['mataPelajaran', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Tugas dengan deadline terdekat
        $upcomingTugas = Tugas::whereHas('course', function($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })
        ->where('deadline', '>=', now())
        ->orderBy('deadline', 'asc')
        ->take(5)
        ->get();
        
        return view('guru.dashboard', compact(
            'totalCourses',
            'totalTugas',
            'totalPengumpulan',
            'pengumpulanBelumDinilai',
            'recentCourses',
            'upcomingTugas'
        ));
    }
}