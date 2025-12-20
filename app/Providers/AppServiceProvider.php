<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\AdminDashboardComposer;
use App\View\Composers\TaskComposer;
use App\View\Composers\UserManagementComposer;
use App\View\Composers\AttendanceComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(
            'admin.dashboard',
            AdminDashboardComposer::class
        );

        View::composer(
            'admin.tasks.*',
            TaskComposer::class
        );

        View::composer(
            'admin.attendance.*',
            AttendanceComposer::class
        );

        View::composer(
            'admin.users.*',
            UserManagementComposer::class
        );
    }
}
