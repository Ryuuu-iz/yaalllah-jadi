<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapAbsensi;
use App\Models\Course;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\DataGuru;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Display a listing of attendance records
     */
    public function index(Request $request)
    {
        $query = RekapAbsensi::with(['siswa', 'kelas', 'guru', 'mataPelajaran']);
        
        // Filters
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }
        
        if ($request->filled('id_mapel')) {
            $query->where('id_mapel', $request->id_mapel);
        }
        
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        $absensi = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        // Data for filters
        $kelas = Kelas::all();
        $mataPelajaran = MataPelajaran::all();
        
        // Statistics
        $stats = RekapAbsensi::selectRaw('status_absensi, COUNT(*) as total')
                            ->groupBy('status_absensi')
                            ->pluck('total', 'status_absensi');
        
        return view('admin.attendance.index', compact('absensi', 'kelas', 'mataPelajaran', 'stats'));
    }

    /**
     * Show attendance details for a specific date and course
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mapel' => 'required|exists:mata_pelajaran,id_mapel',
        ]);

        $absensi = RekapAbsensi::with(['siswa', 'guru'])
                              ->where('tanggal', $validated['tanggal'])
                              ->where('id_kelas', $validated['id_kelas'])
                              ->where('id_mapel', $validated['id_mapel'])
                              ->get();

        $kelas = Kelas::findOrFail($validated['id_kelas']);
        $mataPelajaran = MataPelajaran::findOrFail($validated['id_mapel']);

        return view('admin.attendance.show', compact('absensi', 'kelas', 'mataPelajaran', 'validated'));
    }

    /**
     * Show the form for creating attendance
     */
    public function create(Request $request)
    {
        $courses = Course::with(['mataPelajaran', 'kelas', 'siswa', 'guru'])->get();
        
        $selectedCourse = null;
        $siswaList = collect();
        
        if ($request->filled('id_course')) {
            $selectedCourse = Course::with(['siswa', 'kelas', 'mataPelajaran', 'guru'])
                                   ->findOrFail($request->id_course);
            $siswaList = $selectedCourse->siswa;
        }
        
        return view('admin.attendance.create', compact('courses', 'selectedCourse', 'siswaList'));
    }

    /**
     * Store attendance records
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
        ]);
        
        $course = Course::findOrFail($validated['id_course']);
        
        // Cek apakah sudah ada absensi untuk tanggal ini
        $existingCount = RekapAbsensi::where('id_kelas', $course->id_kelas)
                                    ->where('id_mapel', $course->id_mapel)
                                    ->where('id_guru', $course->id_guru)
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
                'id_guru' => $course->id_guru,
                'id_mapel' => $course->id_mapel,
            ]);
        }
        
        return redirect()->route('admin.attendance.index')->with('success', 'Absensi berhasil disimpan');
    }

    /**
     * Show the form for editing attendance
     */
    public function edit(Request $request)
    {
        // Validate input dari query string
        $request->validate([
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
        ]);
        
        $course = Course::with(['siswa', 'kelas', 'mataPelajaran', 'guru'])
                       ->findOrFail($request->id_course);
        
        // Ambil data absensi yang sudah ada
        $absensiData = RekapAbsensi::where('id_kelas', $course->id_kelas)
                                  ->where('id_mapel', $course->id_mapel)
                                  ->where('id_guru', $course->id_guru)
                                  ->whereDate('tanggal', $request->tanggal)
                                  ->get()
                                  ->keyBy('id_siswa');
        
        $courses = Course::with(['mataPelajaran', 'kelas'])->get();
        
        // Kirim data yang divalidasi
        $validated = [
            'id_course' => $request->id_course,
            'tanggal' => $request->tanggal
        ];
        
        return view('admin.attendance.edit', compact('course', 'absensiData', 'courses', 'validated'));
    }

    /**
     * Update attendance records
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
        ]);
        
        $course = Course::findOrFail($validated['id_course']);
        
        // Update absensi yang sudah ada
        foreach ($validated['absensi'] as $id_siswa => $status) {
            RekapAbsensi::where('id_kelas', $course->id_kelas)
                       ->where('id_mapel', $course->id_mapel)
                       ->where('id_guru', $course->id_guru)
                       ->where('id_siswa', $id_siswa)
                       ->whereDate('tanggal', $validated['tanggal'])
                       ->update(['status_absensi' => $status]);
        }
        
        return redirect()->route('admin.attendance.index')->with('success', 'Absensi berhasil diupdate');
    }

    /**
     * Export attendance report
     */
    public function export(Request $request)
    {
        // This can be implemented with Excel/PDF export
        // For now, we'll just return the view
        return back()->with('info', 'Export feature coming soon');
    }

    /**
     * Delete attendance records for a specific date and course
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_mapel' => 'required|exists:mata_pelajaran,id_mapel',
        ]);

        $deletedCount = RekapAbsensi::where('tanggal', $validated['tanggal'])
                                    ->where('id_kelas', $validated['id_kelas'])
                                    ->where('id_mapel', $validated['id_mapel'])
                                    ->delete();

        return redirect()->route('admin.attendance.index')
                        ->with('success', "Successfully deleted {$deletedCount} attendance records");
    }
}