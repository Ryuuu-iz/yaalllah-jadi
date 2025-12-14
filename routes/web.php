    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
    use App\Http\Controllers\Admin\UserController;
    use App\Http\Controllers\Admin\CourseController as AdminCourseController;
    use App\Http\Controllers\Admin\MataPelajaranController as SubjectController;
    use App\Http\Controllers\Admin\AbsensiController as AdminAttendanceController;
    use App\Http\Controllers\Admin\TugasController as AdminTaskController;
    use App\Http\Controllers\Admin\MateriPembelajaranController as AdminMaterialController;
    use App\Http\Controllers\Admin\KelasController as AdminClassController;
    use App\Http\Controllers\Admin\TahunAjaranController;

    use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
    use App\Http\Controllers\Guru\CourseController as GuruCourseController;
    use App\Http\Controllers\Guru\MateriController;
    use App\Http\Controllers\Guru\TugasController;
    use App\Http\Controllers\Guru\AbsensiController;

    use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
    use App\Http\Controllers\Siswa\CourseController as SiswaCourseController;
    use App\Http\Controllers\Siswa\TugasController as SiswaTugasController;
    use App\Http\Controllers\Siswa\AbsensiController as SiswaAbsensiController;
    use App\Http\Controllers\Siswa\GuruController as SiswaGuruController;

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
        
        // User Management
        Route::resource('users', UserController::class);
        
        // Course Management
        Route::resource('courses', AdminCourseController::class);
        Route::post('/courses/{course}/enroll', [AdminCourseController::class, 'enrollStudent'])->name('courses.enroll');
        Route::delete('/courses/{course}/students/{id_siswa}', [AdminCourseController::class, 'removeStudent'])->name('courses.remove-student');
        Route::post('/courses/{course}/regenerate-key', [AdminCourseController::class, 'regenerateKey'])->name('courses.regenerate-key');
        
        // Subject Management
        Route::resource('subjects', SubjectController::class)->except(['show']);

        // Class Management
        Route::resource('classes', AdminClassController::class)->except(['show']);

        // Academic Year Management
        Route::resource('academic-years', TahunAjaranController::class)->except(['show']);
        Route::post('/academic-years/{id}/toggle-status', [TahunAjaranController::class, 'toggleStatus'])->name('academic-years.toggle-status');
        
        // Attendance Management
        Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AdminAttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AdminAttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/edit', [AdminAttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/update', [AdminAttendanceController::class, 'update'])->name('attendance.update');
        Route::get('/attendance/show', [AdminAttendanceController::class, 'show'])->name('attendance.show');
        Route::delete('/attendance/destroy', [AdminAttendanceController::class, 'destroy'])->name('attendance.destroy');
        Route::get('/attendance/export', [AdminAttendanceController::class, 'export'])->name('attendance.export');
        Route::post('/attendance/toggle-status', [AdminAttendanceController::class, 'toggleStatus'])->name('attendance.toggle-status');

        // Task Management
        Route::resource('tasks', AdminTaskController::class);
        Route::get('/tasks/{task}/submissions', [AdminTaskController::class, 'submissions'])->name('tasks.submissions');
        
        // Material Management
        Route::resource('materials', AdminMaterialController::class);

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });

    // Guru routes
    Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
        
        // Course Management
        Route::resource('courses', GuruCourseController::class);
        Route::post('/courses/{course}/enroll', [GuruCourseController::class, 'enrollStudent'])->name('courses.enroll');
        Route::delete('/courses/{course}/students/{id_siswa}', [GuruCourseController::class, 'removeStudent'])->name('courses.remove-student');
        Route::post('/courses/{course}/regenerate-key', [GuruCourseController::class, 'regenerateKey'])->name('courses.regenerate-key');
        Route::get('/courses/{course}/materials', [GuruCourseController::class, 'getMaterials'])->name('courses.materials');

        // Material Management
        Route::resource('materials', MateriController::class);
        
        // Task Management
        Route::resource('tasks', TugasController::class);
        Route::post('/tasks/task/{submissions}/grade', [TugasController::class, 'gradeSubmission'])->name('tasks.grade');
        
        // Attendance Management
        Route::get('/attendance', [AbsensiController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AbsensiController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AbsensiController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/edit', [AbsensiController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/update', [AbsensiController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/destroy', [AbsensiController::class, 'destroy'])->name('attendance.destroy');
        Route::post('/attendance/toggle-status', [AbsensiController::class, 'toggleStatus'])->name('attendance.toggle-status');

        // Profile 
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    });

    // Siswa routes
    Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');

        Route::get('/teachers', [SiswaGuruController::class, 'index'])->name('teachers.index');
        Route::get('/teachers/{teacher}', [SiswaGuruController::class, 'show'])->name('teachers.show');
        
        // Course Management
        Route::get('/courses', [SiswaCourseController::class, 'index'])->name('courses.index');
        Route::post('/courses/enroll', [SiswaCourseController::class, 'enroll'])->name('courses.enroll');
        Route::get('/courses/{course}', [SiswaCourseController::class, 'show'])->name('courses.show');
        Route::delete('/courses/{course}/leave', [SiswaCourseController::class, 'leave'])->name('courses.leave');
        
        // Task Management
        Route::get('/tasks', [SiswaTugasController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{task}', [SiswaTugasController::class, 'show'])->name('tasks.show');
        Route::post('/tasks/{task}/submit', [SiswaTugasController::class, 'submit'])->name('tasks.submit');
        
        // Attendance
        Route::get('/absensi', [SiswaAbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi/submit', [SiswaAbsensiController::class, 'submit'])->name('absensi.submit');
        Route::post('/absensi/request-permission', [SiswaAbsensiController::class, 'requestPermission'])->name('absensi.request-permission');


        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });