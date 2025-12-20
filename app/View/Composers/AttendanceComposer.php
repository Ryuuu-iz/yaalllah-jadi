<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\AttendanceService;

class AttendanceComposer
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function compose(View $view)
    {
        // Data yang umum digunakan di semua view attendance
        $kelas = $this->attendanceService->getClasses();
        $mataPelajaran = $this->attendanceService->getSubjects();

        $view->with([
            'kelas' => $kelas,
            'mataPelajaran' => $mataPelajaran
        ]);
    }
}