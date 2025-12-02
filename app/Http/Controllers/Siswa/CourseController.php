<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->dataSiswa;
        $courses = $siswa->courses()
                        ->with(['mataPelajaran', 'kelas', 'guru'])
                        ->paginate(10);
        
        return view('siswa.courses.index', compact('courses'));
    }

    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'enrollment_key' => 'required|string|exists:course,enrollment_key',
        ]);

        $course = Course::where('enrollment_key', $validated['enrollment_key'])->first();
        $siswa = auth()->user()->dataSiswa;

        // Cek apakah sudah terdaftar
        if ($siswa->courses()->where('id_course', $course->id_course)->exists()) {
            return back()->with('error', 'Anda sudah terdaftar di course ini');
        }

        $siswa->courses()->attach($course->id_course, ['enrolled_at' => now()]);

        return redirect()->route('siswa.courses.index')->with('success', 'Berhasil mendaftar ke course: ' . $course->judul);
    }

    public function show(Course $course)
    {
        $siswa = auth()->user()->dataSiswa;
        
        // Cek apakah siswa terdaftar di course ini
        if (!$siswa->courses()->where('id_course', $course->id_course)->exists()) {
            abort(403, 'Anda tidak terdaftar di course ini');
        }

        $course->load([
            'mataPelajaran', 
            'kelas', 
            'guru', 
            'materiPembelajaran' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'tugas' => function($query) use ($siswa) {
                $query->with(['pengumpulanTugas' => function($q) use ($siswa) {
                    $q->where('id_siswa', $siswa->id_siswa);
                }])->orderBy('deadline', 'asc');
            }
        ]);
        
        return view('siswa.courses.show', compact('course'));
    }

    public function leave(Course $course)
    {
        $siswa = auth()->user()->dataSiswa;
        
        // Cek apakah siswa terdaftar di course ini
        if (!$siswa->courses()->where('id_course', $course->id_course)->exists()) {
            abort(403, 'Anda tidak terdaftar di course ini');
        }
        
        // Hapus enrollment
        $siswa->courses()->detach($course->id_course);
        
        return redirect()->route('siswa.courses.index')->with('success', 'Anda telah keluar dari course: ' . $course->judul);
    }
}