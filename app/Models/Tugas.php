<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';

    protected $fillable = [
        'nama_tugas',
        'desk_tugas',
        'file_tugas',
        'tgl_upload',
        'deadline',
        'id_course',
        'id_materi',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // Relasi
    public function course()
    {
        return $this->belongsTo(Course::class, 'id_course', 'id_course');
    }

    public function materi()
    {
        return $this->belongsTo(MateriPembelajaran::class, 'id_materi', 'id_materi');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'id_tugas', 'id_tugas');
    }

    // Helper method untuk cek status pengumpulan siswa
    public function getPengumpulanBySiswa($id_siswa)
    {
        return $this->pengumpulanTugas()->where('id_siswa', $id_siswa)->first();
    }
}