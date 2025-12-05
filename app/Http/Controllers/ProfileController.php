<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the profile page
     */
    public function index()
    {
        $user = auth()->user();
        
        // Load related data based on role
        if ($user->role === 'siswa') {
            $user->load('dataSiswa');
        } elseif ($user->role === 'guru') {
            $user->load('dataGuru');
        }
        
        return view('profile.index', compact('user'));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        // Validation rules
        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
        ];
        
        // Add role-specific validation
        if ($user->role === 'siswa') {
            $rules['nama'] = 'required|string|max:255';
            $rules['nisn'] = 'required|string|max:20|unique:data_siswa,nisn,' . $user->dataSiswa->id_siswa . ',id_siswa';
        } elseif ($user->role === 'guru') {
            $rules['nama'] = 'required|string|max:255';
            $rules['nip'] = 'required|string|max:20|unique:data_guru,nip,' . $user->dataGuru->id_guru . ',id_guru';
        }
        
        $validated = $request->validate($rules);
        
        // Update username
        $user->update([
            'username' => $validated['username'],
        ]);
        
        // Update role-specific data
        if ($user->role === 'siswa' && $user->dataSiswa) {
            $user->dataSiswa->update([
                'nama' => $validated['nama'],
                'nisn' => $validated['nisn'],
            ]);
        } elseif ($user->role === 'guru' && $user->dataGuru) {
            $user->dataGuru->update([
                'nama' => $validated['nama'],
                'nip' => $validated['nip'],
            ]);
        }
        
        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);
        
        $user = auth()->user();
        
        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return back()->with('success', 'Password updated successfully');
    }
}