<?php

namespace App\Services;

use App\Models\MateriPembelajaran;
use App\Models\Course;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialService
{
    public function getMaterialsWithFilters(Request $request)
    {
        $query = MateriPembelajaran::with([
            'course.mataPelajaran:id_mapel,nama_mapel', 
            'course.kelas:id_kelas,nama_kelas', 
            'course.guru:id_guru,nama', 
            'tahunAjaran:id_TA,semester'
        ]);

        // Filters
        if ($request->filled('id_course')) {
            $query->where('id_course', $request->id_course);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
    }

    public function getMaterialById($id)
    {
        return MateriPembelajaran::with([
            'course.mataPelajaran:id_mapel,nama_mapel',
            'course.kelas:id_kelas,nama_kelas',
            'course.guru:id_guru,nama',
            'tahunAjaran:id_TA,semester',
            'tugas:id_tugas,nama_tugas,desk_tugas,deadline,id_course,id_materi'
        ])->findOrFail($id);
    }

    public function createMaterial(array $data)
    {
        // Upload file if exists
        if (isset($data['file_materi'])) {
            $file = $data['file_materi'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materi', $filename, 'public');
            $data['file_materi'] = $path;
        }

        return MateriPembelajaran::create($data);
    }

    public function updateMaterial($material, array $data)
    {
        // Upload new file if exists
        if (isset($data['file_materi'])) {
            // Delete old file if exists
            if ($material->file_materi && Storage::disk('public')->exists($material->file_materi)) {
                Storage::disk('public')->delete($material->file_materi);
            }

            $file = $data['file_materi'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materi', $filename, 'public');
            $data['file_materi'] = $path;
        }

        return $material->update($data);
    }

    public function deleteMaterial($material)
    {
        // Delete file if exists
        if ($material->file_materi && Storage::disk('public')->exists($material->file_materi)) {
            Storage::disk('public')->delete($material->file_materi);
        }

        return $material->delete();
    }

    public function getAllCourses()
    {
        return cache()->remember('all_courses_material_dropdown', 300, function () {
            return Course::with([
                'mataPelajaran:id_mapel,nama_mapel',
                'kelas:id_kelas,nama_kelas',
                'guru:id_guru,nama'
            ])->select('id_course', 'judul', 'id_mapel', 'id_kelas', 'id_guru')->get();
        });
    }

    public function getAllAcademicYears()
    {
        //return TahunAjaran::select('id_TA', 'tahun_ajaran', 'is_active')->get();
        return TahunAjaran::select('id_TA')->get();
    }
}