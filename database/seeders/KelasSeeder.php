<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['nama_kelas' => '10 IPA 1', 'tingkatan' => '10'],
            ['nama_kelas' => '10 IPA 2', 'tingkatan' => '10'],
            ['nama_kelas' => '11 IPA 1', 'tingkatan' => '11'],
            ['nama_kelas' => '12 IPA 1', 'tingkatan' => '12'],
        ];

        foreach ($kelas as $k) {
            Kelas::create($k);
        }
    }
}
