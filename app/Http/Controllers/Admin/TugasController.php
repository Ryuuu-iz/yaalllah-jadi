<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Course;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /**
     * Display a listing of all tasks
     */
    public function index(Request $request)
    {
        $query = Tugas::with(['course.mataPelajaran', 'course.kelas', 'course.guru', 'materi', 'pengumpulanTugas']);
        
        // Filters
        if ($request->filled('id_course')) {
            $query->where('id_course', $request->id_course);
        }

        if ($request->filled('status')) {
            if ($request->status == 'upcoming') {
                $query->where('deadline', '>=', now());
            } elseif ($request->status == 'past') {
                $query->where('deadline', '<', now());
            }
        }
        
        $tugas = $query->orderBy('deadline', 'desc')->paginate(15);
        
        // Data for filters
        $courses = Course::with(['mataPelajaran', 'kelas'])->get();
        
        return view('admin.tasks.index', compact('tugas', 'courses'));
    }

    /**
     * Display the specified task
     */
    public function show(Tugas $task)
    {
        $task->load([
            'course.mataPelajaran', 
            'course.kelas', 
            'course.guru.user',
            'materi',
            'pengumpulanTugas.siswa'
        ]);
        
        // Statistics
        $totalStudents = $task->course->siswa()->count();
        $submittedCount = $task->pengumpulanTugas()->count();
        $gradedCount = $task->pengumpulanTugas()->whereNotNull('nilai')->count();
        $lateSubmissions = $task->pengumpulanTugas()->where('status', 'terlambat')->count();
        
        return view('admin.tasks.show', compact('task', 'totalStudents', 'submittedCount', 'gradedCount', 'lateSubmissions'));
    }

    /**
     * Remove the specified task
     */
    public function destroy(Tugas $task)
    {
        // Delete task file if exists
        if ($task->file_tugas && \Storage::disk('public')->exists($task->file_tugas)) {
            \Storage::disk('public')->delete($task->file_tugas);
        }

        // Delete all submission files
        foreach ($task->pengumpulanTugas as $pengumpulan) {
            if ($pengumpulan->file_pengumpulan && \Storage::disk('public')->exists($pengumpulan->file_pengumpulan)) {
                \Storage::disk('public')->delete($pengumpulan->file_pengumpulan);
            }
        }
        
        $task->delete();

        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task berhasil dihapus');
    }

    /**
     * View all submissions for a task
     */
    public function submissions(Tugas $task)
    {
        $task->load([
            'course',
            'pengumpulanTugas.siswa'
        ]);

        $submissions = $task->pengumpulanTugas()
                           ->with('siswa')
                           ->orderBy('tgl_pengumpulan', 'desc')
                           ->paginate(20);

        return view('admin.tasks.submissions', compact('task', 'submissions'));
    }
}