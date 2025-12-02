<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\RekapAbsensi;
use App\Models\Course;
use App\Models\DataSiswa;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->dataGuru;
        
        // Get courses milik guru
        $courses = Course::where('id_guru', $guru->id_guru)
                        ->with(['mataPelajaran', 'kelas'])
                        ->get();
        
        // Filter
        $query = RekapAbsensi::where('id_guru', $guru->id_guru)
                            ->with(['siswa', 'kelas', 'mataPelajaran']);
        
        if ($request->filled('id_course')) {
            $course = Course::findOrFail($request->id_course);
            $query->where('id_kelas', $course->id_kelas)
                  ->where('id_mapel', $course->id_mapel);
        }
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        
        $absensi = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        return view('guru.absensi.index', compact('absensi', 'courses'));
    }

    public function create(Request $request)
    {
        $guru = auth()->user()->dataGuru;
        $courses = Course::where('id_guru', $guru->id_guru)
                        ->with(['mataPelajaran', 'kelas', 'siswa'])
                        ->get();
        
        $selectedCourse = null;
        $siswaList = collect();
        
        if ($request->filled('id_course')) {
            $selectedCourse = Course::with(['siswa', 'kelas', 'mataPelajaran'])
                                   ->findOrFail($request->id_course);
            $siswaList = $selectedCourse->siswa;
        }
        
        return view('guru.absensi.create', compact('courses', 'selectedCourse', 'siswaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
        ]);
        
        $guru = auth()->user()->dataGuru;
        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->firstOrFail();
        
        // Cek apakah sudah ada absensi untuk tanggal ini
        $existingCount = RekapAbsensi::where('id_guru', $guru->id_guru)
                                    ->where('id_kelas', $course->id_kelas)
                                    ->where('id_mapel', $course->id_mapel)
                                    ->whereDate('tanggal', $validated['tanggal'])
                                    ->count();
        
        if ($existingCount > 0) {
            return back()->with('error', 'Absensi untuk tanggal ini sudah ada');
        }
        
        // Simpan absensi untuk setiap siswa
        foreach ($validated['absensi'] as $id_siswa => $status) {
            RekapAbsensi::create([
                'tanggal' => $validated['tanggal'],
                'status_absensi' => $status,
                'id_siswa' => $id_siswa,
                'id_kelas' => $course->id_kelas,
                'id_guru' => $guru->id_guru,
                'id_mapel' => $course->id_mapel,
            ]);
        }
        
        return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil disimpan');
    }

    public function edit(Request $request)
    {
        $guru = auth()->user()->dataGuru;
        
        $validated = $request->validate([
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
        ]);
        
        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->with(['siswa', 'kelas', 'mataPelajaran'])
                       ->firstOrFail();
        
        // Ambil data absensi yang sudah ada
        $absensiData = RekapAbsensi::where('id_guru', $guru->id_guru)
                                  ->where('id_kelas', $course->id_kelas)
                                  ->where('id_mapel', $course->id_mapel)
                                  ->whereDate('tanggal', $validated['tanggal'])
                                  ->get()
                                  ->keyBy('id_siswa');
        
        $courses = Course::where('id_guru', $guru->id_guru)->get();
        
        return view('guru.absensi.edit', compact('course', 'absensiData', 'courses', 'validated'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
        ]);
        
        $guru = auth()->user()->dataGuru;
        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->firstOrFail();
        
        // Update absensi yang sudah ada
        foreach ($validated['absensi'] as $id_siswa => $status) {
            RekapAbsensi::where('id_guru', $guru->id_guru)
                       ->where('id_kelas', $course->id_kelas)
                       ->where('id_mapel', $course->id_mapel)
                       ->where('id_siswa', $id_siswa)
                       ->whereDate('tanggal', $validated['tanggal'])
                       ->update(['status_absensi' => $status]);
        }
        
        return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil diupdate');
    }

    public function destroy(Request $request)
    {
        $guru = auth()->user()->dataGuru;
        
        $validated = $request->validate([
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
        ]);
        
        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->firstOrFail();
        
        RekapAbsensi::where('id_guru', $guru->id_guru)
                   ->where('id_kelas', $course->id_kelas)
                   ->where('id_mapel', $course->id_mapel)
                   ->whereDate('tanggal', $validated['tanggal'])
                   ->delete();
        
        return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil dihapus');
    }
}