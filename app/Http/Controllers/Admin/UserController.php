<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DataSiswa;
use App\Models\DataGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['dataSiswa', 'dataGuru'])->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,guru,siswa',
            'nama' => 'required|string',
            'nisn' => 'required_if:role,siswa|nullable|string|unique:data_siswa,nisn',
            'nip' => 'required_if:role,guru|nullable|string|unique:data_guru,nip',
        ]);

        // Buat user
        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Buat data siswa atau guru
        if ($validated['role'] === 'siswa') {
            DataSiswa::create([
                'nama' => $validated['nama'],
                'nisn' => $validated['nisn'],
                'id_user' => $user->id_user,
            ]);
        } elseif ($validated['role'] === 'guru') {
            DataGuru::create([
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
                'id_user' => $user->id_user,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'nama' => 'required|string',
        ]);

        $user->update([
            'username' => $validated['username'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        // Update data siswa atau guru
        if ($user->role === 'siswa' && $user->dataSiswa) {
            $user->dataSiswa->update(['nama' => $validated['nama']]);
        } elseif ($user->role === 'guru' && $user->dataGuru) {
            $user->dataGuru->update(['nama' => $validated['nama']]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}