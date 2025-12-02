<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\DataGuru;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of all courses
     */
    public function index()
    {
        $courses = Course::with(['mataPelajaran', 'kelas', 'guru.user', 'siswa'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
        
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create()
    {
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        $guru = DataGuru::with('user')->get();
        
        return view('admin.courses.create', compact('mataPelajaran', 'kelas', 'guru'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'id_mapel' => 'required|exists:mata_pelajaran,id_mapel',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:data_guru,id_guru',
            'enrollment_key' => 'nullable|string|unique:course,enrollment_key',
        ]);

        $validated['tgl_upload'] = now()->format('Y-m-d');
        
        Course::create($validated);

        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course berhasil ditambahkan');
    }

    /**
     * Display the specified course
     */
    public function show(Course $course)
    {
        $course->load([
            'mataPelajaran', 
            'kelas', 
            'guru.user',
            'siswa.user',
            'materiPembelajaran.tahunAjaran',
            'tugas.pengumpulanTugas.siswa'
        ]);
        
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course
     */
    public function edit(Course $course)
    {
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        $guru = DataGuru::with('user')->get();
        
        return view('admin.courses.edit', compact('course', 'mataPelajaran', 'kelas', 'guru'));
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'id_mapel' => 'required|exists:mata_pelajaran,id_mapel',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_guru' => 'required|exists:data_guru,id_guru',
            'enrollment_key' => 'nullable|string|unique:course,enrollment_key,' . $course->id_course . ',id_course',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course berhasil diupdate');
    }

    /**
     * Remove the specified course
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
                        ->with('success', 'Course berhasil dihapus');
    }

    /**
     * Manage students enrollment in course
     */
    public function manageEnrollment(Course $course)
    {
        $course->load(['siswa.user', 'kelas']);
        $availableStudents = \App\Models\DataSiswa::with('user')
                                ->whereNotIn('id_siswa', $course->siswa->pluck('id_siswa'))
                                ->get();
        
        return view('admin.courses.enrollment', compact('course', 'availableStudents'));
    }

    /**
     * Enroll student to course
     */
    public function enrollStudent(Request $request, Course $course)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:data_siswa,id_siswa',
        ]);

        if ($course->siswa()->where('id_siswa', $validated['id_siswa'])->exists()) {
            return back()->with('error', 'Siswa sudah terdaftar di course ini');
        }

        $course->siswa()->attach($validated['id_siswa'], ['enrolled_at' => now()]);

        return back()->with('success', 'Siswa berhasil ditambahkan ke course');
    }

    /**
     * Remove student from course
     */
    public function removeStudent(Course $course, $id_siswa)
    {
        $course->siswa()->detach($id_siswa);

        return back()->with('success', 'Siswa berhasil dihapus dari course');
    }

    /**
     * Regenerate enrollment key
     */
    public function regenerateKey(Course $course)
    {
        $course->update([
            'enrollment_key' => strtoupper(\Illuminate\Support\Str::random(8))
        ]);

        return back()->with('success', 'Enrollment key berhasil di-generate ulang: ' . $course->enrollment_key);
    }
}