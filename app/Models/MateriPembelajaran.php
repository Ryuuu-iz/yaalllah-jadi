<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class MateriPembelajaran extends Model
{
    use HasFactory;
    protected $table = 'materi_pembelajaran';
    protected $primaryKey = 'id_materi';

    protected $fillable = [
        'nama_materi',
        'desk_materi',
        'file_materi',
        'id_TA',
        'id_course',
    ];

    // Relasi
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_TA', 'id_TA');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'id_course', 'id_course');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_materi', 'id_materi');
    }
}