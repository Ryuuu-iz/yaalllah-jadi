<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'password',
        'role',
        'foto_profile',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi
    public function dataSiswa()
    {
        return $this->hasOne(DataSiswa::class, 'id_user', 'id_user');
    }

    public function dataGuru()
    {
        return $this->hasOne(DataGuru::class, 'id_user', 'id_user');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    // Helper untuk mendapatkan URL foto profile
    public function getFotoProfileUrl()
    {
        if ($this->foto_profile && \Storage::disk('public')->exists($this->foto_profile)) {
            return asset('storage/' . $this->foto_profile);
        }
        return null;
    }
}