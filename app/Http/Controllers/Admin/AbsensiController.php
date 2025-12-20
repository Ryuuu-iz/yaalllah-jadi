<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Services\AttendanceService;
use App\Models\Course;
use App\Models\RekapAbsensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request)
    {
        $absensi = $this->attendanceService->getAttendanceWithFilters($request);
        $groupedAttendance = $this->attendanceService->getAttendanceGroupedBySession($absensi);

        // Data for filters
        $kelas = $this->attendanceService->getClasses();
        $mataPelajaran = $this->attendanceService->getSubjects();

        // Statistics
        $stats = $this->attendanceService->getAttendanceStats();

        return view('admin.attendance.index', compact('absensi', 'groupedAttendance', 'kelas', 'mataPelajaran', 'stats'));
    }

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

    public function store(Request $request)
    {
        $rules = [
            'id_course' => 'required|exists:course,id_course',
            'tanggal' => 'required|date',
            'deadline' => 'required|date|after:tanggal',
            'mode' => 'required|in:manual,self',
        ];

        // Add validation for absensi only in manual mode
        if ($request->input('mode') === 'manual') {
            $rules['absensi'] = 'required|array';
            $rules['absensi.*'] = 'required|in:hadir,izin,sakit,alpha';
        }

        $validated = $request->validate($rules);
        
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
        
        if ($validated['mode'] === 'manual') {
            // Mode manual: Admin/Guru input langsung
            foreach ($validated['absensi'] as $id_siswa => $status) {
                RekapAbsensi::create([
                    'tanggal' => $validated['tanggal'],
                    'deadline' => $validated['deadline'],
                    'is_open' => false, // Sudah selesai
                    'status_absensi' => $status,
                    'id_siswa' => $id_siswa,
                    'id_kelas' => $course->id_kelas,
                    'id_guru' => $course->id_guru,
                    'id_mapel' => $course->id_mapel,
                ]);
            }
        } else {
            // Mode self: Siswa absen sendiri
            foreach ($course->siswa as $siswa) {
                RekapAbsensi::create([
                    'tanggal' => $validated['tanggal'],
                    'deadline' => $validated['deadline'],
                    'is_open' => true, // Masih buka untuk siswa
                    'status_absensi' => 'alpha', // Default alpha jika tidak absen
                    'id_siswa' => $siswa->id_siswa,
                    'id_kelas' => $course->id_kelas,
                    'id_guru' => $course->id_guru,
                    'id_mapel' => $course->id_mapel,
                ]);
            }
        }
        
        return redirect()->route('admin.attendance.index')
            ->with('success', 'Absensi berhasil dibuat. ' . 
                ($validated['mode'] === 'self' ? 'Siswa dapat melakukan absensi mandiri sampai ' . \Carbon\Carbon::parse($validated['deadline'])->format('d M Y H:i') : ''));
    }

    public function edit(Request $request)
    {
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
        
        $validated = [
            'id_course' => $request->id_course,
            'tanggal' => $request->tanggal
        ];
        
        return view('admin.attendance.edit', compact('course', 'absensiData', 'courses', 'validated'));
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
        
        $course = Course::findOrFail($validated['id_course']);
        
        // Update absensi
        foreach ($validated['absensi'] as $id_siswa => $status) {
            $updateData = ['status_absensi' => $status];
            
            if (isset($validated['deadline'])) {
                $updateData['deadline'] = $validated['deadline'];
            }
            
            if (isset($validated['is_open'])) {
                $updateData['is_open'] = $validated['is_open'];
            }
            
            RekapAbsensi::where('id_kelas', $course->id_kelas)
                       ->where('id_mapel', $course->id_mapel)
                       ->where('id_guru', $course->id_guru)
                       ->where('id_siswa', $id_siswa)
                       ->whereDate('tanggal', $validated['tanggal'])
                       ->update($updateData);
        }
        
        return redirect()->route('admin.attendance.index')->with('success', 'Absensi berhasil diupdate');
    }

    public function toggleStatus(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_course' => 'required|exists:course,id_course',
        ]);

        $course = Course::findOrFail($validated['id_course']);
        
        $absensiRecords = RekapAbsensi::where('id_kelas', $course->id_kelas)
                                     ->where('id_mapel', $course->id_mapel)
                                     ->where('id_guru', $course->id_guru)
                                     ->whereDate('tanggal', $validated['tanggal'])
                                     ->get();

        if ($absensiRecords->isEmpty()) {
            return back()->with('error', 'Tidak ada data absensi ditemukan');
        }

        $currentStatus = $absensiRecords->first()->is_open;
        $newStatus = !$currentStatus;

        RekapAbsensi::where('id_kelas', $course->id_kelas)
                   ->where('id_mapel', $course->id_mapel)
                   ->where('id_guru', $course->id_guru)
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

        $deletedCount = RekapAbsensi::where('tanggal', $validated['tanggal'])
                                    ->where('id_kelas', $validated['id_kelas'])
                                    ->where('id_mapel', $validated['id_mapel'])
                                    ->delete();

        return redirect()->route('admin.attendance.index')
                        ->with('success', "Berhasil menghapus {$deletedCount} data absensi");
    }
}