<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MateriPembelajaran;
use App\Models\Course;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class MateriPembelajaranController extends Controller
{
    /**
     * Display a listing of all materials
     */
    public function index(Request $request)
    {
        $query = MateriPembelajaran::with(['course.mataPelajaran', 'course.kelas', 'course.guru', 'tahunAjaran']);
        
        // Filters
        if ($request->filled('id_course')) {
            $query->where('id_course', $request->id_course);
        }
        
        $materi = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Data for filters
        $courses = Course::with(['mataPelajaran', 'kelas'])->get();
        
        return view('admin.materials.index', compact('materi', 'courses'));
    }

    /**
     * Show the form for creating a new material
     */
    public function create()
    {
        $courses = Course::with(['mataPelajaran', 'kelas', 'guru'])->get();
        $tahunAjaran = TahunAjaran::all();
        
        return view('admin.materials.create', compact('courses', 'tahunAjaran'));
    }

    /**
     * Store a newly created material
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_materi' => 'required|string|max:255',
            'desk_materi' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:20480',
            'id_course' => 'required|exists:course,id_course',
            'id_TA' => 'required|exists:tahun_ajaran,id_TA',
        ]);
        
        // Upload file if exists
        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materi', $filename, 'public');
            $validated['file_materi'] = $path;
        }
        
        MateriPembelajaran::create($validated);
        
        return redirect()->route('admin.materials.index')
                        ->with('success', 'Material successfully created');
    }

    /**
     * Display the specified material
     */
    public function show($id)
    {
        $material = MateriPembelajaran::with([
            'course.mataPelajaran',
            'course.kelas',
            'course.guru.user',
            'tahunAjaran',
            'tugas.pengumpulanTugas'
        ])->findOrFail($id);
        
        return view('admin.materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified material
     */
    public function edit($id)
    {
        $material = MateriPembelajaran::findOrFail($id);
        $courses = Course::with(['mataPelajaran', 'kelas', 'guru'])->get();
        $tahunAjaran = TahunAjaran::all();
        
        return view('admin.materials.edit', compact('material', 'courses', 'tahunAjaran'));
    }

    /**
     * Update the specified material
     */
    public function update(Request $request, $id)
    {
        $material = MateriPembelajaran::findOrFail($id);
        
        $validated = $request->validate([
            'nama_materi' => 'required|string|max:255',
            'desk_materi' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:20480',
            'id_course' => 'required|exists:course,id_course',
            'id_TA' => 'required|exists:tahun_ajaran,id_TA',
        ]);
        
        // Upload new file if exists
        if ($request->hasFile('file_materi')) {
            // Delete old file if exists
            if ($material->file_materi && \Storage::disk('public')->exists($material->file_materi)) {
                \Storage::disk('public')->delete($material->file_materi);
            }
            
            $file = $request->file('file_materi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materi', $filename, 'public');
            $validated['file_materi'] = $path;
        }
        
        $material->update($validated);
        
        return redirect()->route('admin.materials.show', $material->id_materi)
                        ->with('success', 'Material successfully updated');
    }

    /**
     * Remove the specified material
     */
    public function destroy($id)
    {
        $material = MateriPembelajaran::findOrFail($id);
        
        // Delete file if exists
        if ($material->file_materi && \Storage::disk('public')->exists($material->file_materi)) {
            \Storage::disk('public')->delete($material->file_materi);
        }
        
        $material->delete();

        return redirect()->route('admin.materials.index')
                        ->with('success', 'Material successfully deleted');
    }
}