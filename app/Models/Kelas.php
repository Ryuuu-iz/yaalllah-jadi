<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class Kelas extends Model
{
    use HasFactory;

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