<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\TaskService;

class TaskComposer
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function compose(View $view)
    {
        // Data yang umum digunakan di semua view tasks
        $courses = $this->taskService->getAllCourses();

        $view->with([
            'courses' => $courses
        ]);
    }
}