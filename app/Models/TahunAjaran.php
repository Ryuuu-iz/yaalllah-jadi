<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_TA';

    protected $fillable = [
        'semester',
        'status',
    ];

    // Relasi
    public function materiPembelajaran()
    {
        return $this->hasMany(MateriPembelajaran::class, 'id_TA', 'id_TA');
    }
}