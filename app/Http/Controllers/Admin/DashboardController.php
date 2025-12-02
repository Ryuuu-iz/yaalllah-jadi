<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DataSiswa;
use App\Models\DataGuru;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalGuru = DataGuru::count();
        $totalSiswa = DataSiswa::count();
        $totalCourses = Course::count();

        return view('admin.dashboard', compact('totalUsers', 'totalGuru', 'totalSiswa', 'totalCourses'));
    }
}