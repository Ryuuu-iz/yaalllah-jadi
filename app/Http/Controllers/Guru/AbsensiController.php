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
                            ->with(['siswa', 'kelas.courses', 'mataPelajaran']);
        
        if ($request->filled('id_course')) {
            $course = Course::findOrFail($request->id_course);
            $query->where('id_kelas', $course->id_kelas)
                  ->where('id_mapel', $course->id_mapel);
        }
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        $absensi = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        // Statistics
        $stats = RekapAbsensi::where('id_guru', $guru->id_guru)
                            ->selectRaw('status_absensi, COUNT(*) as total')
                            ->groupBy('status_absensi')
                            ->pluck('total', 'status_absensi');
        
        return view('guru.attendance.index', compact('absensi', 'courses', 'stats'));
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
        
        return view('guru.attendance.create', compact('courses', 'selectedCourse', 'siswaList'));
    }

    public function store(Request $request)
    {
        $rules = [
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
            'mode' => 'required|in:manual,self',
        ];

        // Add validation based on mode
        if ($request->input('mode') === 'manual') {
            $rules['absensi'] = 'required|array';
            $rules['absensi.*'] = 'required|in:hadir,izin,sakit,alpha';
        } else {
            $rules['deadline'] = 'required|date|after:tanggal';
        }

        $validated = $request->validate($rules);
        
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
        
        if ($validated['mode'] === 'manual') {
            // Mode manual: Guru input langsung
            foreach ($validated['absensi'] as $id_siswa => $status) {
                RekapAbsensi::create([
                    'tanggal' => $validated['tanggal'],
                    'deadline' => null,
                    'is_open' => false,
                    'status_absensi' => $status,
                    'id_siswa' => $id_siswa,
                    'id_kelas' => $course->id_kelas,
                    'id_guru' => $guru->id_guru,
                    'id_mapel' => $course->id_mapel,
                ]);
            }
            
            return redirect()->route('guru.attendance.index')
                ->with('success', 'Absensi berhasil disimpan');
        } else {
            // Mode self: Siswa absen sendiri
            foreach ($course->siswa as $siswa) {
                RekapAbsensi::create([
                    'tanggal' => $validated['tanggal'],
                    'deadline' => $validated['deadline'],
                    'is_open' => true,
                    'status_absensi' => 'alpha', // Default alpha jika tidak absen
                    'id_siswa' => $siswa->id_siswa,
                    'id_kelas' => $course->id_kelas,
                    'id_guru' => $guru->id_guru,
                    'id_mapel' => $course->id_mapel,
                ]);
            }
            
            return redirect()->route('guru.attendance.index')
                ->with('success', 'Absensi berhasil dibuat. Siswa dapat melakukan absensi mandiri sampai ' . \Carbon\Carbon::parse($validated['deadline'])->format('d M Y H:i'));
        }
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
        
        return view('guru.attendance.edit', compact('course', 'absensiData', 'courses', 'validated'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
            'deadline' => 'nullable|date|after:tanggal',
            'is_open' => 'nullable|boolean',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
        ]);
        
        $guru = auth()->user()->dataGuru;
        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->firstOrFail();
        
        // Update absensi
        foreach ($validated['absensi'] as $id_siswa => $status) {
            $updateData = ['status_absensi' => $status];
            
            if (isset($validated['deadline'])) {
                $updateData['deadline'] = $validated['deadline'];
            }
            
            if (isset($validated['is_open'])) {
                $updateData['is_open'] = $validated['is_open'];
            }
            
            RekapAbsensi::where('id_guru', $guru->id_guru)
                       ->where('id_kelas', $course->id_kelas)
                       ->where('id_mapel', $course->id_mapel)
                       ->where('id_siswa', $id_siswa)
                       ->whereDate('tanggal', $validated['tanggal'])
                       ->update($updateData);
        }
        
        return redirect()->route('guru.attendance.index')->with('success', 'Absensi berhasil diupdate');
    }

    public function toggleStatus(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_course' => 'required|exists:course,id_course',
        ]);

        $guru = auth()->user()->dataGuru;
        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->firstOrFail();
        
        $absensiRecords = RekapAbsensi::where('id_guru', $guru->id_guru)
                                     ->where('id_kelas', $course->id_kelas)
                                     ->where('id_mapel', $course->id_mapel)
                                     ->whereDate('tanggal', $validated['tanggal'])
                                     ->get();

        if ($absensiRecords->isEmpty()) {
            return back()->with('error', 'Tidak ada data absensi ditemukan');
        }

        $currentStatus = $absensiRecords->first()->is_open;
        $newStatus = !$currentStatus;

        RekapAbsensi::where('id_guru', $guru->id_guru)
                   ->where('id_kelas', $course->id_kelas)
                   ->where('id_mapel', $course->id_mapel)
                   ->whereDate('tanggal', $validated['tanggal'])
                   ->update(['is_open' => $newStatus]);

        $message = $newStatus ? 'Absensi dibuka kembali untuk siswa' : 'Absensi ditutup';
        
        return back()->with('success', $message);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mapel' => 'required|exists:mata_pelajaran,id_mapel',
        ]);

        $guru = auth()->user()->dataGuru;

        $deletedCount = RekapAbsensi::where('id_guru', $guru->id_guru)
                                    ->where('tanggal', $validated['tanggal'])
                                    ->where('id_kelas', $validated['id_kelas'])
                                    ->where('id_mapel', $validated['id_mapel'])
                                    ->delete();

        return redirect()->route('guru.attendance.index')
                        ->with('success', "Berhasil menghapus {$deletedCount} data absensi");
    }
}