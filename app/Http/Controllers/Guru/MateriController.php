<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MateriPembelajaran;
use App\Models\Course;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->dataGuru;
        
        $materi = MateriPembelajaran::whereHas('course', function($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })
        ->with(['course', 'tahunAjaran'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        return view('guru.materi.index', compact('materi'));
    }

    public function create()
    {
        $guru = auth()->user()->dataGuru;
        $courses = Course::where('id_guru', $guru->id_guru)->get();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        
        return view('guru.materi.create', compact('courses', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_materi' => 'required|string|max:255',
            'desk_materi' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:20480',
            'id_course' => 'required|exists:course,id_course',
            'id_TA' => 'required|exists:tahun_ajaran,id_TA',
        ]);
        
        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;
        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->firstOrFail();
        
        // Upload file jika ada
        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materi', $filename, 'public');
            $validated['file_materi'] = $path;
        }
        
        MateriPembelajaran::create($validated);
        
        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil ditambahkan');
    }

    public function show(MateriPembelajaran $materi)
    {
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;
        if ($materi->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $materi->load(['course', 'tahunAjaran', 'tugas']);
        
        return view('guru.materi.show', compact('materi'));
    }

    public function edit(MateriPembelajaran $materi)
    {
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;
        if ($materi->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $courses = Course::where('id_guru', $guru->id_guru)->get();
        $tahunAjaran = TahunAjaran::where('status', 'aktif')->get();
        
        return view('guru.materi.edit', compact('materi', 'courses', 'tahunAjaran'));
    }

    public function update(Request $request, MateriPembelajaran $materi)
    {
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;
        if ($materi->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'nama_materi' => 'required|string|max:255',
            'desk_materi' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:20480',
            'id_course' => 'required|exists:course,id_course',
            'id_TA' => 'required|exists:tahun_ajaran,id_TA',
        ]);
        
        // Upload file baru jika ada
        if ($request->hasFile('file_materi')) {
            // Hapus file lama jika ada
            if ($materi->file_materi && \Storage::disk('public')->exists($materi->file_materi)) {
                \Storage::disk('public')->delete($materi->file_materi);
            }
            
            $file = $request->file('file_materi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materi', $filename, 'public');
            $validated['file_materi'] = $path;
        }
        
        $materi->update($validated);
        
        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diupdate');
    }

    public function destroy(MateriPembelajaran $materi)
    {
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;
        if ($materi->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        // Hapus file jika ada
        if ($materi->file_materi && \Storage::disk('public')->exists($materi->file_materi)) {
            \Storage::disk('public')->delete($materi->file_materi);
        }
        
        $materi->delete();
        
        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus');
    }
}