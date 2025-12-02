<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    protected $table = 'rekap_absensi';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'tanggal',
        'status_absensi',
        'id_siswa',
        'id_kelas',
        'id_guru',
        'id_mapel',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi
    public function siswa()
    {
        return $this->belongsTo(DataSiswa::class, 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(DataGuru::class, 'id_guru', 'id_guru');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mapel', 'id_mapel');
    }
}