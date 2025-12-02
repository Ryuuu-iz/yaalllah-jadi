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

    // Auto-set status berdasarkan deadline
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pengumpulan) {
            $tugas = Tugas::find($pengumpulan->id_tugas);
            
            if ($tugas && $pengumpulan->tgl_pengumpulan > $tugas->deadline) {
                $pengumpulan->status = 'terlambat';
            } else {
                $pengumpulan->status = 'tepat_waktu';
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
}