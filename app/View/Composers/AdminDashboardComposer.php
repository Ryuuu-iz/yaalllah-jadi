<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\DashboardService;

class AdminDashboardComposer
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function compose(View $view)
    {
        $dashboardData = $this->dashboardService->getAdminDashboardStats();
        $academicYearText = $this->dashboardService->getActiveAcademicYear();

        $view->with(array_merge($dashboardData, [
            'academicYearText' => $academicYearText
        ]));
    }
}