<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MateriPembelajaran;
use App\Models\Course;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->dataGuru;
        
        $query = MateriPembelajaran::whereHas('course', function($q) use ($guru) {
            $q->where('id_guru', $guru->id_guru);
        })->with(['course.kelas', 'course.mataPelajaran', 'tahunAjaran']);
        
        // Filter by search
        if ($request->filled('search')) {
            $query->where('nama_materi', 'like', '%' . $request->search . '%');
        }
        
        // Filter by course
        if ($request->filled('id_course')) {
            $query->where('id_course', $request->id_course);
        }
        
        // Filter by academic year
        if ($request->filled('id_TA')) {
            $query->where('id_TA', $request->id_TA);
        }
        
        $materi = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('guru.materials.index', compact('materi'));
    }

    public function create(Request $request)
    {
        $guru = auth()->user()->dataGuru;
        $courses = Course::where('id_guru', $guru->id_guru)
            ->with(['kelas', 'mataPelajaran'])
            ->get();
        $tahunAjaran = TahunAjaran::orderBy('status', 'desc')->orderBy('created_at', 'desc')->get();
        
        return view('guru.materials.create', compact('courses', 'tahunAjaran'));
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
        
        $materi = MateriPembelajaran::create($validated);
        
        return redirect()->route('guru.materials.show', $materi->id_materi)
            ->with('success', 'Material successfully created');
    }

    public function show($id)
    {
        $materi = MateriPembelajaran::with([
            'course.mataPelajaran',
            'course.kelas',
            'course.siswa',
            'tahunAjaran',
            'tugas'
        ])->findOrFail($id);
        
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;
        if ($materi->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('guru.materials.show', compact('materi'));
    }

    public function edit($id)
    {
        $material = MateriPembelajaran::findOrFail($id);
        
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;
        if ($material->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $courses = Course::where('id_guru', $guru->id_guru)
            ->with(['kelas', 'mataPelajaran'])
            ->get();
        $tahunAjaran = TahunAjaran::orderBy('status', 'desc')->orderBy('created_at', 'desc')->get();
        
        return view('guru.materials.edit', compact('material', 'courses', 'tahunAjaran'));
    }

    public function update(Request $request, $id)
    {
        $material = MateriPembelajaran::findOrFail($id);
        
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;
        if ($material->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'nama_materi' => 'required|string|max:255',
            'desk_materi' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:20480',
            'id_course' => 'required|exists:course,id_course',
            'id_TA' => 'required|exists:tahun_ajaran,id_TA',
        ]);
        
        // Verifikasi course baru juga milik guru
        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->firstOrFail();
        
        // Upload file baru jika ada
        if ($request->hasFile('file_materi')) {
            // Hapus file lama jika ada
            if ($material->file_materi && \Storage::disk('public')->exists($material->file_materi)) {
                \Storage::disk('public')->delete($material->file_materi);
            }
            
            $file = $request->file('file_materi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materi', $filename, 'public');
            $validated['file_materi'] = $path;
        }
        
        $material->update($validated);
        
        return redirect()->route('guru.materials.show', $material->id_materi)
            ->with('success', 'Material successfully updated');
    }

    public function destroy($id)
    {
        $material = MateriPembelajaran::findOrFail($id);
        
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;
        if ($material->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }
        
        // Hapus file jika ada
        if ($material->file_materi && \Storage::disk('public')->exists($material->file_materi)) {
            \Storage::disk('public')->delete($material->file_materi);
        }
        
        $material->delete();

        return redirect()->route('guru.materials.index')
            ->with('success', 'Material successfully deleted');
    }
}