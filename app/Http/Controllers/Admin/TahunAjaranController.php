<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of academic years
     */
    public function index()
    {
        $academicYears = TahunAjaran::withCount('materiPembelajaran')
                                   ->orderBy('status', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(15);
        
        return view('admin.academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new academic year
     */
    public function create()
    {
        return view('admin.academic-years.create');
    }

    /**
     * Store a newly created academic year
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|string|max:255|unique:tahun_ajaran,semester',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        // If new academic year is active, set all others to inactive
        if ($validated['status'] === 'aktif') {
            TahunAjaran::where('status', 'aktif')->update(['status' => 'tidak_aktif']);
        }

        TahunAjaran::create($validated);

        return redirect()->route('admin.academic-years.index')
                        ->with('success', 'Academic year berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified academic year
     */
    public function edit($id)
    {
        $academicYear = TahunAjaran::findOrFail($id);
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified academic year
     */
    public function update(Request $request, $id)
    {
        $academicYear = TahunAjaran::findOrFail($id);
        
        $validated = $request->validate([
            'semester' => 'required|string|max:255|unique:tahun_ajaran,semester,' . $id . ',id_TA',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        // If setting this academic year to active, set all others to inactive
        if ($validated['status'] === 'aktif') {
            TahunAjaran::where('id_TA', '!=', $id)
                      ->where('status', 'aktif')
                      ->update(['status' => 'tidak_aktif']);
        }

        $academicYear->update($validated);

        return redirect()->route('admin.academic-years.index')
                        ->with('success', 'Academic year berhasil diupdate');
    }

    /**
     * Remove the specified academic year
     */
    public function destroy($id)
    {
        $academicYear = TahunAjaran::findOrFail($id);
        
        // Check if academic year has any materials
        if ($academicYear->materiPembelajaran()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus academic year yang masih memiliki materi pembelajaran');
        }

        // Prevent deletion of active academic year
        if ($academicYear->status === 'aktif') {
            return back()->with('error', 'Tidak dapat menghapus academic year yang sedang aktif');
        }

        $academicYear->delete();

        return redirect()->route('admin.academic-years.index')
                        ->with('success', 'Academic year berhasil dihapus');
    }

    /**
     * Toggle academic year status
     */
    public function toggleStatus($id)
    {
        $academicYear = TahunAjaran::findOrFail($id);
        
        if ($academicYear->status === 'aktif') {
            $academicYear->update(['status' => 'tidak_aktif']);
            $message = 'Academic year set to inactive';
        } else {
            // Set all others to inactive first
            TahunAjaran::where('id_TA', '!=', $id)
                      ->where('status', 'aktif')
                      ->update(['status' => 'tidak_aktif']);
            
            $academicYear->update(['status' => 'aktif']);
            $message = 'Academic year set to active';
        }

        return back()->with('success', $message);
    }
}