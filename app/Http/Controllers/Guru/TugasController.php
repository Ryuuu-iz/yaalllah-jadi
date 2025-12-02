<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Course;
use App\Models\MateriPembelajaran;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->dataGuru;
        $tugas = Tugas::whereHas('course', function($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })->with(['course', 'materi', 'pengumpulanTugas'])->get();
        
        return view('guru.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $guru = auth()->user()->dataGuru;
        $courses = Course::where('id_guru', $guru->id_guru)->get();
        
        return view('guru.tugas.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tugas' => 'required|string',
            'desk_tugas' => 'nullable|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'deadline' => 'required|date',
            'id_course' => 'required|exists:course,id_course',
            'id_materi' => 'required|exists:materi_pembelajaran,id_materi',
        ]);
        
        $data = $validated;
        $data['tgl_upload'] = now()->format('Y-m-d');
        
        // Upload file jika ada
        if ($request->hasFile('file_tugas')) {
            $file = $request->file('file_tugas');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tugas', $filename, 'public');
            $data['file_tugas'] = $path;
        }
        
        Tugas::create($data);
        
        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil ditambahkan');
    }

    public function show(Tugas $tugas)
    {
        $tugas->load(['course', 'materi', 'pengumpulanTugas.siswa']);
        
        return view('guru.tugas.show', compact('tugas'));
    }

    public function gradeSubmission(Request $request, PengumpulanTugas $pengumpulan)
    {
        $validated = $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'feedback_guru' => 'nullable|string',
        ]);
        
        $pengumpulan->update($validated);
        
        return back()->with('success', 'Nilai berhasil diberikan');
    }
}