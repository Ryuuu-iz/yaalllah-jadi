<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MateriPembelajaran;
use App\Models\Course;
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
     * Display the specified material
     */
    public function show(MateriPembelajaran $material)
    {
        $material->load([
            'course.mataPelajaran',
            'course.kelas',
            'course.guru.user',
            'tahunAjaran',
            'tugas'
        ]);
        
        return view('admin.materials.show', compact('material'));
    }

    /**
     * Remove the specified material
     */
    public function destroy(MateriPembelajaran $material)
    {
        // Delete file if exists
        if ($material->file_materi && \Storage::disk('public')->exists($material->file_materi)) {
            \Storage::disk('public')->delete($material->file_materi);
        }
        
        $material->delete();

        return redirect()->route('admin.materials.index')
                        ->with('success', 'Material berhasil dihapus');
    }
}