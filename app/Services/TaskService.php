<?php

namespace App\Services;

use App\Models\Tugas;
use App\Models\Course;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskService
{
    public function getAllTasksWithFilters(Request $request)
    {
        $query = Tugas::with(['course.mataPelajaran', 'course.kelas', 'course.guru', 'materi']);

        // Filters
        if ($request->filled('id_course')) {
            $query->filterByCourse($request->id_course);
        }

        if ($request->filled('status')) {
            $query->filterByStatus($request->status);
        }

        return $query->orderByDeadlineDesc()->paginate(15);
    }

    public function getTaskById($id)
    {
        return Tugas::with([
            'course.mataPelajaran',
            'course.kelas',
            'course.guru.user',
            'materi'
            // Jangan muat pengumpulanTugas di sini karena bisa banyak
        ])->findOrFail($id);
    }

    public function getTaskStatistics($task)
    {
        $totalStudents = $task->course->siswa()->count();
        $submittedCount = $task->pengumpulanTugas()->count();
        $gradedCount = $task->pengumpulanTugas()->whereNotNull('nilai')->count();
        $lateSubmissions = $task->pengumpulanTugas()->where('status', 'terlambat')->count();

        return [
            'totalStudents' => $totalStudents,
            'submittedCount' => $submittedCount,
            'gradedCount' => $gradedCount,
            'lateSubmissions' => $lateSubmissions
        ];
    }

    public function deleteTask($task)
    {
        // Delete task file if exists
        if ($task->file_tugas && Storage::disk('public')->exists($task->file_tugas)) {
            Storage::disk('public')->delete($task->file_tugas);
        }

        // Delete all submission files
        foreach ($task->pengumpulanTugas as $pengumpulan) {
            if ($pengumpulan->file_pengumpulan && Storage::disk('public')->exists($pengumpulan->file_pengumpulan)) {
                Storage::disk('public')->delete($pengumpulan->file_pengumpulan);
            }
        }

        return $task->delete();
    }

    public function getTaskSubmissions($task, $perPage = 20)
    {
        return $task->pengumpulanTugas()
                   ->with('siswa')
                   ->orderBy('tgl_pengumpulan', 'desc')
                   ->paginate($perPage);
    }

    public function getAllCourses()
    {
        return Course::with(['mataPelajaran', 'kelas', 'guru', 'materiPembelajaran'])->get();
    }

    public function createTask(array $data)
    {
        return Tugas::create($data);
    }

    public function updateTask($task, array $data)
    {
        return $task->update($data);
    }
}