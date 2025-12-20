<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Str;
    class Course extends Model
{
    use HasFactory;

    protected $table = 'course';
    protected $primaryKey = 'id_course';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tgl_upload',
        'id_mapel',
        'id_kelas',
        'id_TA',
        'enrollment_key',
        'id_guru',
    ];

    // Auto-generate enrollment key
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->enrollment_key)) {
                $course->enrollment_key = strtoupper(Str::random(8));
            }
        });
    }

    // Relasi
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mapel', 'id_mapel');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(DataGuru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsToMany(DataSiswa::class, 'course_siswa', 'id_course', 'id_siswa')
                    ->withTimestamps()
                    ->withPivot('enrolled_at');
    }

    public function materiPembelajaran()
    {
        return $this->hasMany(MateriPembelajaran::class, 'id_course', 'id_course');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_course', 'id_course');
    }
}