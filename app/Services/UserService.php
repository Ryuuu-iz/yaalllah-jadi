<?php

namespace App\Services;

use App\Models\User;
use App\Models\DataSiswa;
use App\Models\DataGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getUsersWithFilter(Request $request)
    {
        $query = User::with(['dataSiswa', 'dataGuru']);
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        return $query->paginate(10)->appends($request->query());
    }

    public function createUser(array $data)
    {
        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($data['role'] === 'siswa') {
            DataSiswa::create([
                'nama' => $data['nama'],
                'nisn' => $data['nisn'],
                'id_user' => $user->id_user,
            ]);
        } elseif ($data['role'] === 'guru') {
            DataGuru::create([
                'nama' => $data['nama'],
                'nip' => $data['nip'],
                'id_user' => $user->id_user,
            ]);
        }

        return $user;
    }

    public function updateUser(User $user, array $data)
    {
        $user->update([
            'username' => $data['username'],
            'password' => $data['password'] ? Hash::make($data['password']) : $user->password,
        ]);

        if ($user->role === 'siswa' && $user->dataSiswa) {
            $user->dataSiswa->update(['nama' => $data['nama']]);
        } elseif ($user->role === 'guru' && $user->dataGuru) {
            $user->dataGuru->update(['nama' => $data['nama']]);
        }

        return $user;
    }

    public function deleteUser(User $user)
    {
        return $user->delete();
    }
}