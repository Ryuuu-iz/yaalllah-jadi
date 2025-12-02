<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\CourseController as GuruCourseController;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Guru\TugasController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Siswa\CourseController as SiswaCourseController;
use App\Http\Controllers\Siswa\TugasController as SiswaTugasController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('courses', AdminCourseController::class);
});

// Guru routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
    Route::resource('courses', GuruCourseController::class);
    Route::post('/courses/{course}/enroll', [GuruCourseController::class, 'enrollStudent'])->name('courses.enroll');
    Route::resource('materi', MateriController::class);
    Route::resource('tugas', TugasController::class);
    Route::post('/tugas/pengumpulan/{pengumpulan}/grade', [TugasController::class, 'gradeSubmission'])->name('tugas.grade');
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
});

// Siswa routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
    Route::get('/courses', [SiswaCourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/enroll', [SiswaCourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/{course}', [SiswaCourseController::class, 'show'])->name('courses.show');
    Route::get('/tugas', [SiswaTugasController::class, 'index'])->name('tugas.index');
    Route::post('/tugas/{tugas}/submit', [SiswaTugasController::class, 'submit'])->name('tugas.submit');
});