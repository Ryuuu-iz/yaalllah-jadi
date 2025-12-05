<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of classes
     */
    public function index()
    {
        $classes = Kelas::withCount('courses')->orderBy('tingkatan')->orderBy('nama_kelas')->paginate(15);
        
        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new class
     */
    public function create()
    {
        return view('admin.classes.create');
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
            'tingkatan' => 'required|string|max:10',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.classes.index')
                        ->with('success', 'Class berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit($id)
    {
        $class = Kelas::findOrFail($id);
        return view('admin.classes.edit', compact('class'));
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, $id)
    {
        $class = Kelas::findOrFail($id);
        
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $id . ',id_kelas',
            'tingkatan' => 'required|string|max:10',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')
                        ->with('success', 'Class berhasil diupdate');
    }

    /**
     * Remove the specified class
     */
    public function destroy($id)
    {
        $class = Kelas::findOrFail($id);
        
        // Check if class has any courses
        if ($class->courses()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus class yang masih memiliki course');
        }

        $class->delete();

        return redirect()->route('admin.classes.index')
                        ->with('success', 'Class berhasil dihapus');
    }
}