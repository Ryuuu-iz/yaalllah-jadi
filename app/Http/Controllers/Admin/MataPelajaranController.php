<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of subjects
     */
    public function index()
    {
        $subjects = MataPelajaran::withCount('courses')->orderBy('nama_mapel')->paginate(15);
        
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mata_pelajaran,nama_mapel',
        ]);

        MataPelajaran::create($validated);

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified subject
     */
    public function edit(MataPelajaran $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, MataPelajaran $subject)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mata_pelajaran,nama_mapel,' . $subject->id_mapel . ',id_mapel',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject berhasil diupdate');
    }

    /**
     * Remove the specified subject
     */
    public function destroy(MataPelajaran $subject)
    {
        // Check if subject has any courses
        if ($subject->courses()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus subject yang masih memiliki course');
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
                        ->with('success', 'Subject berhasil dihapus');
    }
}