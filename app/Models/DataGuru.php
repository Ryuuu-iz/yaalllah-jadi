<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataGuru extends Model
{
    use HasFactory;

    protected $table = 'data_guru';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'nama',
        'nip',
        'id_user',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'id_guru', 'id_guru');
    }

    public function rekapAbsensi()
    {
        return $this->hasMany(RekapAbsensi::class, 'id_guru', 'id_guru');
    }
}