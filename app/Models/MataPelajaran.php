<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mapel';

    protected $fillable = [
        'nama_mapel',
    ];

    // Relasi
    public function courses()
    {
        return $this->hasMany(Course::class, 'id_mapel', 'id_mapel');
    }

    public function rekapAbsensi()
    {
        return $this->hasMany(RekapAbsensi::class, 'id_mapel', 'id_mapel');
    }
}