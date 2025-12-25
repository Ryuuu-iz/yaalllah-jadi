<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->dataSiswa;

        if (!$siswa) {
            return redirect('/profile/complete')->with('error', 'Please complete your student profile to continue.');
        }

        // Get enrolled courses
        $courses = $siswa->courses()
                        ->with(['mataPelajaran', 'kelas', 'guru'])
                        ->get();

        // Statistics
        $totalCourses = $courses->count();

        return view('siswa.dashboard', compact('courses', 'totalCourses'));
    }
}