<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class Tugas extends Model
{
    use HasFactory;
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

    // Query Scopes
    public function scopeFilterByCourse($query, $courseId)
    {
        return $query->where('id_course', $courseId);
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status === 'upcoming') {
            return $query->where('deadline', '>=', now());
        } elseif ($status === 'past') {
            return $query->where('deadline', '<', now());
        }
        return $query;
    }

    public function scopeOrderByDeadlineDesc($query)
    {
        return $query->orderBy('deadline', 'desc');
    }

    // Helper method untuk cek status pengumpulan siswa
    public function getPengumpulanBySiswa($id_siswa)
    {
        return $this->pengumpulanTugas()->where('id_siswa', $id_siswa)->first();
    }
}