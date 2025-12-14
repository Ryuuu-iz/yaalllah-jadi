<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->dataSiswa;
        
        // Ambil semua tugas dari course yang diikuti siswa
        $courses = $siswa->courses()->with(['tugas.materi', 'tugas.pengumpulanTugas' => function($query) use ($siswa) {
            $query->where('id_siswa', $siswa->id_siswa);
        }])->get();
        
        return view('siswa.tugas.index', compact('courses'));
    }

    public function submit(Request $request, $id)
    {
        $siswa = auth()->user()->dataSiswa;
        
        // Validasi siswa memiliki dataSiswa
        if (!$siswa) {
            return back()->with('error', 'Student profile not found. Please contact administrator.');
        }

        // Ambil tugas
        $tugas = Tugas::findOrFail($id);
        
        // Cek apakah siswa terdaftar di course ini
        if (!$siswa->courses()->where('course.id_course', $tugas->id_course)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }
        
        // Cek apakah sudah pernah mengumpulkan
        $existing = PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                                    ->where('id_siswa', $siswa->id_siswa)
                                    ->first();
        
        if ($existing) {
            return back()->with('error', 'You have already submitted this assignment.');
        }
        
        // Validasi input
        $validated = $request->validate([
            'file_pengumpulan' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
            'keterangan' => 'nullable|string|max:500',
        ]);
        
        // Prepare data
        $data = [
            'id_tugas' => $tugas->id_tugas,
            'id_siswa' => $siswa->id_siswa,
            'keterangan' => $validated['keterangan'] ?? null,
            'tgl_pengumpulan' => now(),
        ];
        
        // Upload file jika ada
        if ($request->hasFile('file_pengumpulan')) {
            try {
                $file = $request->file('file_pengumpulan');
                $filename = time() . '_' . $siswa->id_siswa . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('pengumpulan_tugas', $filename, 'public');
                $data['file_pengumpulan'] = $path;
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload file: ' . $e->getMessage());
            }
        }
        
        // Tentukan status berdasarkan deadline
        if (now() > $tugas->deadline) {
            $data['status'] = 'terlambat';
        } else {
            $data['status'] = 'tepat_waktu';
        }
        
        try {
            PengumpulanTugas::create($data);
            return back()->with('success', 'Assignment submitted successfully!');
        } catch (\Exception $e) {
            // Hapus file jika gagal menyimpan ke database
            if (isset($data['file_pengumpulan']) && Storage::disk('public')->exists($data['file_pengumpulan'])) {
                Storage::disk('public')->delete($data['file_pengumpulan']);
            }
            return back()->with('error', 'Failed to submit assignment: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $siswa = auth()->user()->dataSiswa;
        
        if (!$siswa) {
            return back()->with('error', 'Student profile not found. Please contact administrator.');
        }

        $tugas = Tugas::with([
            'course.mataPelajaran',
            'course.kelas',
            'course.guru',
            'materi',
        ])->findOrFail($id);
        
        // Cek apakah siswa terdaftar di course ini
        if (!$siswa->courses()->where('course.id_course', $tugas->id_course)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }
        
        // Ambil submission siswa jika ada
        $submission = PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                                     ->where('id_siswa', $siswa->id_siswa)
                                     ->first();
        
        return view('siswa.tugas.show', compact('tugas', 'submission'));
    }
}