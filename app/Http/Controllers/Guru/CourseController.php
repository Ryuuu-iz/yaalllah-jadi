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
        $courses = Course::where('id_guru', $guru->id_guru)->with(['mataPelajaran', 'kelas'])->get();
        
        return view('guru.courses.index', compact('courses'));
    }

    public function create()
    {
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        
        return view('guru.courses.create', compact('mataPelajaran', 'kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'id_mapel' => 'required|exists:mata_pelajaran,id_mapel',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        $guru = auth()->user()->dataGuru;

        Course::create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'id_mapel' => $validated['id_mapel'],
            'id_kelas' => $validated['id_kelas'],
            'id_guru' => $guru->id_guru,
            'tgl_upload' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('guru.courses.index')->with('success', 'Course berhasil ditambahkan');
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);
        
        $course->load(['mataPelajaran', 'kelas', 'siswa', 'materiPembelajaran', 'tugas']);
        
        return view('guru.courses.show', compact('course'));
    }

    public function enrollStudent(Request $request, Course $course)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:data_siswa,id_siswa',
        ]);

        $course->siswa()->attach($validated['id_siswa'], ['enrolled_at' => now()]);

        return back()->with('success', 'Siswa berhasil ditambahkan ke course');
    }
}