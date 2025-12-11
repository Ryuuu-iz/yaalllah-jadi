<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    protected $table = 'rekap_absensi';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'tanggal',
        'deadline',
        'is_open',
        'status_absensi',
        'keterangan',
        'id_siswa',
        'id_kelas',
        'id_guru',
        'id_mapel',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'deadline' => 'datetime',
        'is_open' => 'boolean',
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

    // Helper methods
    public function isExpired()
    {
        return $this->deadline && now()->isAfter($this->deadline);
    }

    public function canSubmit()
    {
        return $this->is_open && !$this->isExpired();
    }
}