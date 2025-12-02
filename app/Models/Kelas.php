<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkatan',
    ];

    // Relasi
    public function courses()
    {
        return $this->hasMany(Course::class, 'id_kelas', 'id_kelas');
    }

    public function rekapAbsensi()
    {
        return $this->hasMany(RekapAbsensi::class, 'id_kelas', 'id_kelas');
    }
}