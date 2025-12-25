<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\DataGuru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * Display a listing of all teachers
     */
    public function index()
    {
        $teachers = DataGuru::with(['user', 'courses.mataPelajaran', 'courses.kelas'])
                           ->withCount('courses')
                           ->orderBy('nama')
                           ->get();
        
        return view('siswa.teachers.index', compact('teachers'));
    }

    /**
     * Display the specified teacher and their courses
     */
    public function show($id)
    {
        $teacher = DataGuru::with([
            'user',
            'courses.mataPelajaran',
            'courses.kelas',
            'courses.siswa'
        ])->findOrFail($id);

        $siswa = auth()->user()->dataSiswa;

        if (!$siswa) {
            return redirect('/profile/complete')->with('error', 'Please complete your student profile to continue.');
        }

        // Get enrolled and available courses
        $enrolledCourseIds = $siswa->courses->pluck('id_course')->toArray();

        $courses = $teacher->courses->map(function($course) use ($enrolledCourseIds) {
            $course->is_enrolled = in_array($course->id_course, $enrolledCourseIds);
            return $course;
        });

        return view('siswa.teachers.show', compact('teacher', 'courses'));
    }
}