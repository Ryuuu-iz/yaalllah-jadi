<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DataSiswa;
use App\Models\DataGuru;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Guru
        $guru1 = User::create([
            'username' => 'guru1',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);
        DataGuru::create([
            'nama' => 'Budi Santoso',
            'nip' => '198501012010011001',
            'id_user' => $guru1->id_user,
        ]);

        // Siswa
        $siswa1 = User::create([
            'username' => 'siswa1',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
        ]);
        DataSiswa::create([
            'nama' => 'Andi Wijaya',
            'nisn' => '0012345678',
            'id_user' => $siswa1->id_user,
        ]);
    }
}