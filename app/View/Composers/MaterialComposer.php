<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\MaterialService;

class MaterialComposer
{
    protected $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    public function compose(View $view)
    {
        // Data commonly used in all view materials
        $courses = $this->materialService->getAllCourses();
        $tahunAjaran = $this->materialService->getAllAcademicYears();

        $view->with([
            'courses' => $courses,
            'tahunAjaran' => $tahunAjaran
        ]);
    }
}