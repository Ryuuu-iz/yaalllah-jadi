<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class DataSiswa extends Model
{
    use HasFactory;
    protected $table = 'data_siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'nama',
        'nisn',
        'id_user',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_siswa', 'id_siswa', 'id_course')
                    ->withTimestamps()
                    ->withPivot('enrolled_at');
    }

    public function rekapAbsensi()
    {
        return $this->hasMany(RekapAbsensi::class, 'id_siswa', 'id_siswa');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'id_siswa', 'id_siswa');
    }
}