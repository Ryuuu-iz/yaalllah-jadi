<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Tugas;
use App\Models\Course;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of all tasks
     */
    public function index(Request $request)
    {
        $tugas = $this->taskService->getAllTasksWithFilters($request);

        return view('admin.tasks.index', compact('tugas'));
    }

    /**
     * Display the specified task
     */
    public function show(Tugas $task)
    {
        $task = $this->taskService->getTaskById($task->id_tugas);
        $stats = $this->taskService->getTaskStatistics($task);

        // Ambil submissions dengan pagination
        $submissions = $this->taskService->getTaskSubmissions($task);

        return view('admin.tasks.show', [
            'task' => $task,
            'submissions' => $submissions,
            'totalStudents' => $stats['totalStudents'],
            'submittedCount' => $stats['submittedCount'],
            'gradedCount' => $stats['gradedCount'],
            'lateSubmissions' => $stats['lateSubmissions']
        ]);
    }

    /**
     * Remove the specified task
     */
    public function destroy(Tugas $task)
    {
        $this->taskService->deleteTask($task);

        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task berhasil dihapus');
    }

    /**
     * View all submissions for a task
     */
    public function submissions(Tugas $task)
    {
        $task->load(['course', 'pengumpulanTugas.siswa']);
        $submissions = $this->taskService->getTaskSubmissions($task);

        return view('admin.tasks.submissions', compact('task', 'submissions'));
    }

    public function create()
    {
        return view('admin.tasks.create');
    }

    /**
     * Store a newly created task
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        // Set upload date
        $validated['tgl_upload'] = now()->format('Y-m-d');

        // Upload file if exists
        if ($request->hasFile('file_tugas')) {
            $file = $request->file('file_tugas');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tugas', $filename, 'public');
            $validated['file_tugas'] = $path;
        }

        $this->taskService->createTask($validated);

        return redirect()->route('admin.tasks.index')
                        ->with('success', 'Task successfully created');
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit(Tugas $task)
    {
        $task->load(['course.materiPembelajaran', 'pengumpulanTugas']);

        return view('admin.tasks.edit', compact('task'));
    }

    /**
     * Update the specified task
     */
    public function update(UpdateTaskRequest $request, Tugas $task)
    {
        $validated = $request->validated();

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

        $this->taskService->updateTask($task, $validated);

        return redirect()->route('admin.tasks.show', $task->id_tugas)
                        ->with('success', 'Task successfully updated');
    }
}