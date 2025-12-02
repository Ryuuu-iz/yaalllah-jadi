<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

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

    public function submit(Request $request, Tugas $tugas)
    {
        $siswa = auth()->user()->dataSiswa;
        
        // Cek apakah siswa terdaftar di course ini
        if (!$siswa->courses()->where('id_course', $tugas->id_course)->exists()) {
            abort(403, 'Anda tidak terdaftar di course ini');
        }
        
        // Cek apakah sudah pernah mengumpulkan
        $existing = PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
                                    ->where('id_siswa', $siswa->id_siswa)
                                    ->first();
        
        if ($existing) {
            return back()->with('error', 'Anda sudah mengumpulkan tugas ini');
        }
        
        $validated = $request->validate([
            'file_pengumpulan' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
            'keterangan' => 'nullable|string',
        ]);
        
        $data = [
            'id_tugas' => $tugas->id_tugas,
            'id_siswa' => $siswa->id_siswa,
            'keterangan' => $validated['keterangan'] ?? null,
            'tgl_pengumpulan' => now(),
        ];
        
        // Upload file jika ada
        if ($request->hasFile('file_pengumpulan')) {
            $file = $request->file('file_pengumpulan');
            $filename = time() . '_' . $siswa->id_siswa . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pengumpulan_tugas', $filename, 'public');
            $data['file_pengumpulan'] = $path;
        }
        
        PengumpulanTugas::create($data);
        
        return back()->with('success', 'Tugas berhasil dikumpulkan');
    }
}