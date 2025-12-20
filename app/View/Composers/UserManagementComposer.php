<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Kelas;
use App\Models\MataPelajaran;

class UserManagementComposer
{
    public function compose(View $view)
    {
        // Data yang umum digunakan di semua view user management
        $kelas = Kelas::all();
        $mataPelajaran = MataPelajaran::all();

        $view->with([
            'kelas' => $kelas,
            'mataPelajaran' => $mataPelajaran
        ]);
    }
}