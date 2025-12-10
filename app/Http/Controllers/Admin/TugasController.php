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

    public function create()
{
    $courses = Course::with(['mataPelajaran', 'kelas', 'guru', 'materiPembelajaran'])->get();
    
    return view('admin.tasks.create', compact('courses'));
}

/**
 * Store a newly created task
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'nama_tugas' => 'required|string|max:255',
        'desk_tugas' => 'nullable|string',
        'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        'deadline' => 'required|date',
        'id_course' => 'required|exists:course,id_course',
        'id_materi' => 'required|exists:materi_pembelajaran,id_materi',
    ]);
    
    // Set upload date
    $validated['tgl_upload'] = now()->format('Y-m-d');
    
    // Upload file if exists
    if ($request->hasFile('file_tugas')) {
        $file = $request->file('file_tugas');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('tugas', $filename, 'public');
        $validated['file_tugas'] = $path;
    }
    
    Tugas::create($validated);
    
    return redirect()->route('admin.tasks.index')
                    ->with('success', 'Task successfully created');
}

/**
 * Show the form for editing the specified task
 */
public function edit(Tugas $task)
{
    $task->load(['course.materiPembelajaran', 'pengumpulanTugas']);
    $courses = Course::with(['mataPelajaran', 'kelas', 'guru', 'materiPembelajaran'])->get();
    
    return view('admin.tasks.edit', compact('task', 'courses'));
}

/**
 * Update the specified task
 */
public function update(Request $request, Tugas $task)
{
    $validated = $request->validate([
        'nama_tugas' => 'required|string|max:255',
        'desk_tugas' => 'nullable|string',
        'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        'deadline' => 'required|date',
        'id_course' => 'required|exists:course,id_course',
        'id_materi' => 'required|exists:materi_pembelajaran,id_materi',
    ]);
    
    // Upload new file if exists
    if ($request->hasFile('file_tugas')) {
        // Delete old file if exists
        if ($task->file_tugas && \Storage::disk('public')->exists($task->file_tugas)) {
            \Storage::disk('public')->delete($task->file_tugas);
        }
        
        $file = $request->file('file_tugas');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('tugas', $filename, 'public');
        $validated['file_tugas'] = $path;
    }
    
    $task->update($validated);
    
    return redirect()->route('admin.tasks.show', $task->id_tugas)
                    ->with('success', 'Task successfully updated');
}
}