<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\DataSiswa;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->dataGuru;
        $courses = Course::where('id_guru', $guru->id_guru)
            ->with(['mataPelajaran', 'kelas', 'siswa'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('guru.courses.index', compact('courses'));
    }

    public function create()
    {
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        $tahunAjaran = \App\Models\TahunAjaran::orderBy('status', 'desc')->orderBy('created_at', 'desc')->get();
        
        return view('guru.courses.create', compact('mataPelajaran', 'kelas', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'id_mapel' => 'required|exists:mata_pelajaran,id_mapel',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_TA' => 'required|exists:tahun_ajaran,id_TA',
            'enrollment_key' => 'nullable|string|max:8|unique:course,enrollment_key',
        ]);

        $guru = auth()->user()->dataGuru;

        // Generate enrollment key if not provided
        if (empty($validated['enrollment_key'])) {
            do {
                $key = strtoupper(\Illuminate\Support\Str::random(8));
            } while (Course::where('enrollment_key', $key)->exists());
            $validated['enrollment_key'] = $key;
        } else {
            $validated['enrollment_key'] = strtoupper($validated['enrollment_key']);
        }

        Course::create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'id_mapel' => $validated['id_mapel'],
            'id_kelas' => $validated['id_kelas'],
            'id_TA' => $validated['id_TA'],
            'enrollment_key' => $validated['enrollment_key'],
            'id_guru' => $guru->id_guru,
            'tgl_upload' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('guru.dashboard')->with('success', 'Course berhasil ditambahkan dengan enrollment key: ' . $validated['enrollment_key']);
    }

    public function show(Course $course)
    {
        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;
        if ($course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $course->load([
            'mataPelajaran', 
            'kelas', 
            'siswa.user',
            'materiPembelajaran.tahunAjaran',
            'tugas.pengumpulanTugas'
        ]);
        
        // Get materials
        $materials = $course->materiPembelajaran()
            ->with('tahunAjaran')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get assignments
        $assignments = $course->tugas()
            ->with(['materi', 'pengumpulanTugas.siswa'])
            ->orderBy('deadline', 'desc')
            ->get();
        
        // Get attendance records
        $attendances = \App\Models\RekapAbsensi::where('id_kelas', $course->id_kelas)
            ->where('id_mapel', $course->id_mapel)
            ->where('id_guru', $course->id_guru)
            ->with(['siswa', 'mataPelajaran'])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->tanggal->format('Y-m-d');
            });
        
        // Attendance statistics
        $attendanceStats = \App\Models\RekapAbsensi::where('id_kelas', $course->id_kelas)
            ->where('id_mapel', $course->id_mapel)
            ->where('id_guru', $course->id_guru)
            ->selectRaw('status_absensi, COUNT(*) as total')
            ->groupBy('status_absensi')
            ->pluck('total', 'status_absensi')
            ->toArray();
        
        // Available students for enrollment (students in same class but not enrolled)
        $availableStudents = DataSiswa::whereHas('user', function($query) {
            $query->where('role', 'siswa');
        })
        ->whereNotIn('id_siswa', $course->siswa->pluck('id_siswa'))
        ->with('user')
        ->get();
        
        // Counts
        $materiCount = $materials->count();
        $tugasCount = $assignments->count();
        $studentCount = $course->siswa->count();
        
        return view('guru.courses.show', compact(
            'course', 
            'materials', 
            'assignments', 
            'attendances',
            'attendanceStats',
            'availableStudents',
            'materiCount',
            'tugasCount',
            'studentCount'
        ));
    }

    public function edit(Course $course)
    {
        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;
        if ($course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        $tahunAjaran = \App\Models\TahunAjaran::orderBy('status', 'desc')->orderBy('created_at', 'desc')->get();
        
        return view('guru.courses.edit', compact('course', 'mataPelajaran', 'kelas', 'tahunAjaran'));
    }

    public function update(Request $request, Course $course)
    {
        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;
        if ($course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'id_mapel' => 'required|exists:mata_pelajaran,id_mapel',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_TA' => 'required|exists:tahun_ajaran,id_TA',
            'enrollment_key' => 'required|string|max:8|unique:course,enrollment_key,' . $course->id_course . ',id_course',
        ]);

        // Uppercase enrollment key
        $validated['enrollment_key'] = strtoupper($validated['enrollment_key']);

        $course->update($validated);

        return redirect()->route('guru.courses.show', $course->id_course)
            ->with('success', 'Course berhasil diupdate');
    }

    public function destroy(Course $course)
    {
        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;
        if ($course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $course->delete();

        return redirect()->route('guru.dashboard')
            ->with('success', 'Course berhasil dihapus');
    }

    public function enrollStudent(Request $request, Course $course)
    {
        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;
        if ($course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'id_siswa' => 'required|exists:data_siswa,id_siswa',
        ]);

        // Cek apakah siswa sudah terdaftar
        if ($course->siswa()->where('data_siswa.id_siswa', $validated['id_siswa'])->exists()) {
            return back()->with('error', 'Siswa sudah terdaftar di course ini');
        }

        // Enroll siswa
        $course->siswa()->attach($validated['id_siswa'], ['enrolled_at' => now()]);

        return back()->with('success', 'Siswa berhasil ditambahkan ke course');
    }

    public function removeStudent(Course $course, $id_siswa)
    {
        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;
        if ($course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $course->siswa()->detach($id_siswa);

        return back()->with('success', 'Siswa berhasil dihapus dari course');
    }

    public function regenerateKey(Course $course)
    {
        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;
        if ($course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $course->update([
            'enrollment_key' => strtoupper(\Illuminate\Support\Str::random(8))
        ]);

        return back()->with('success', 'Enrollment key berhasil di-generate ulang: ' . $course->enrollment_key);
    }
    
    // API endpoint untuk mendapatkan materials berdasarkan course
    public function getMaterials(Course $course)
    {
        $guru = auth()->user()->dataGuru;
        if ($course->id_guru !== $guru->id_guru) {
            abort(403);
        }
        
        return response()->json($course->materiPembelajaran);
    }
}