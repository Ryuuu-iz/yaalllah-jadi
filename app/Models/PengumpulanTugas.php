<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';
    protected $primaryKey = 'id_pengumpulan';

    protected $fillable = [
        'id_tugas',
        'id_siswa',
        'file_pengumpulan',
        'keterangan',
        'tgl_pengumpulan',
        'status',
        'nilai',
        'feedback_guru',
    ];

    protected $casts = [
        'tgl_pengumpulan' => 'datetime',
    ];

    // Auto-set status berdasarkan deadline saat creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pengumpulan) {
            // Jika status belum di-set secara manual
            if (empty($pengumpulan->status)) {
                $tugas = Tugas::find($pengumpulan->id_tugas);
                
                if ($tugas) {
                    // Set tgl_pengumpulan jika belum ada
                    if (empty($pengumpulan->tgl_pengumpulan)) {
                        $pengumpulan->tgl_pengumpulan = now();
                    }
                    
                    // Tentukan status berdasarkan deadline
                    if ($pengumpulan->tgl_pengumpulan > $tugas->deadline) {
                        $pengumpulan->status = 'terlambat';
                    } else {
                        $pengumpulan->status = 'tepat_waktu';
                    }
                }
            }
        });
    }

    // Relasi
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function siswa()
    {
        return $this->belongsTo(DataSiswa::class, 'id_siswa', 'id_siswa');
    }

    // Helper method untuk cek apakah sudah dinilai
    public function isGraded()
    {
        return $this->nilai !== null;
    }

    // Helper method untuk cek apakah terlambat
    public function isLate()
    {
        return $this->status === 'terlambat';
    }
}