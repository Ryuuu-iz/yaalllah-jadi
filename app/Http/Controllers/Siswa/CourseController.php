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
        if ($siswa->courses()->where('course.id_course', $course->id_course)->exists()) {
            return back()->with('error', 'Anda sudah terdaftar di course ini');
        }

        $siswa->courses()->attach($course->id_course, ['enrolled_at' => now()]);

        return redirect()->route('siswa.dashboard')->with('success', 'Berhasil mendaftar ke course: ' . $course->judul);
    }

    public function show(Course $course)
    {
        $siswa = auth()->user()->dataSiswa;
        
        // Cek apakah siswa terdaftar di course ini
        if (!$siswa->courses()->where('course.id_course', $course->id_course)->exists()) {
            abort(403, 'Anda tidak terdaftar di course ini');
        }

        // Load relationships
        $course->load([
            'mataPelajaran', 
            'kelas', 
            'guru', 
            'siswa'
        ]);
        
        // Get materials
        $materials = $course->materiPembelajaran()
                           ->with('tahunAjaran')
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        // Get assignments with student's submission
        $assignments = $course->tugas()
                             ->with(['materi', 'pengumpulanTugas' => function($query) use ($siswa) {
                                 $query->where('id_siswa', $siswa->id_siswa);
                             }])
                             ->orderBy('deadline', 'desc')
                             ->get();
        
        // Get attendance records for this course
        $attendances = $siswa->rekapAbsensi()
                            ->where('id_kelas', $course->id_kelas)
                            ->where('id_mapel', $course->id_mapel)
                            ->where('id_guru', $course->id_guru)
                            ->with('mataPelajaran')
                            ->orderBy('tanggal', 'desc')
                            ->get();
        
        // Attendance statistics
        $attendanceStats = $siswa->rekapAbsensi()
                                ->where('id_kelas', $course->id_kelas)
                                ->where('id_mapel', $course->id_mapel)
                                ->where('id_guru', $course->id_guru)
                                ->selectRaw('status_absensi, COUNT(*) as total')
                                ->groupBy('status_absensi')
                                ->pluck('total', 'status_absensi')
                                ->toArray();
        
        // Counts
        $materiCount = $materials->count();
        $tugasCount = $assignments->count();
        
        return view('siswa.courses.show', compact(
            'course', 
            'materials', 
            'assignments', 
            'attendances',
            'attendanceStats',
            'materiCount',
            'tugasCount'
        ));
    }

    public function leave(Course $course)
    {
        $siswa = auth()->user()->dataSiswa;
        
        // Cek apakah siswa terdaftar di course ini
        if (!$siswa->courses()->where('course.id_course', $course->id_course)->exists()) {
            abort(403, 'Anda tidak terdaftar di course ini');
        }
        
        // Hapus enrollment
        $siswa->courses()->detach($course->id_course);
        
        return redirect()->route('siswa.dashboard')->with('success', 'Anda telah keluar dari course: ' . $course->judul);
    }
}