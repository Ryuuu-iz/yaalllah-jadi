<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class RekapAbsensi extends Model
{
    use HasFactory;
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

    public function course()
    {
        return $this->belongsTo(Course::class, 'id_kelas', 'id_kelas')
                   ->whereColumn('id_mapel', 'id_mapel')
                   ->whereColumn('id_guru', 'id_guru');
    }

    // Query Scopes
    public function scopeFilterByClass($query, $classId)
    {
        return $query->where('id_kelas', $classId);
    }

    public function scopeFilterBySubject($query, $subjectId)
    {
        return $query->where('id_mapel', $subjectId);
    }

    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeFilterByMonth($query, $month)
    {
        return $query->whereMonth('tanggal', $month);
    }

    public function scopeFilterByYear($query, $year)
    {
        return $query->whereYear('tanggal', $year);
    }

    // Helper methods
    public function isExpired()
    {
        return $this->deadline && now()->isAfter($this->deadline);
    }

    /**
     * Cek apakah siswa bisa submit/mengubah absensi
     * Siswa hanya bisa submit jika:
     * 1. is_open = true (masih dalam mode self-attendance)
     * 2. Belum expired (deadline belum lewat)
     * 3. Status masih 'alpha' (belum diisi oleh siswa sendiri atau guru/admin)
     */
    public function canSubmit()
    {
        // Jika is_open = false, berarti sudah ditutup atau mode manual
        if (!$this->is_open) {
            return false;
        }
        
        // Jika sudah expired
        if ($this->isExpired()) {
            return false;
        }
        
        // Jika status bukan alpha, berarti sudah diisi
        if ($this->status_absensi !== 'alpha') {
            return false;
        }
        
        return true;
    }

    /**
     * Cek apakah absensi sudah diisi oleh guru/admin
     * Jika status bukan 'alpha', berarti sudah diubah oleh guru/admin
     */
    public function isFilledByTeacher()
    {
        return $this->status_absensi !== 'alpha';
    }
    
    /**
     * Get reason why student cannot submit
     */
    public function getCannotSubmitReason()
    {
        if (!$this->is_open) {
            return 'Absensi sudah ditutup oleh guru/admin';
        }
        
        if ($this->isExpired()) {
            return 'Waktu absensi telah berakhir';
        }
        
        if ($this->status_absensi !== 'alpha') {
            return 'Absensi Anda sudah tercatat dengan status: ' . ucfirst($this->status_absensi);
        }
        
        return null;
    }
}