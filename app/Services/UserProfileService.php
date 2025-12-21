<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileService
{
    public function getUserProfile()
    {
        $user = auth()->user();
        
        // Load related data based on role with optimized queries
        if ($user->role === 'siswa') {
            $user->load('dataSiswa:id_siswa,nama,nisn,id_user');
        } elseif ($user->role === 'guru') {
            $user->load('dataGuru:id_guru,nama,nip,id_user');
        }
        
        return $user;
    }

    public function updateProfilePhoto($request)
    {
        $user = auth()->user();

        // Delete old photo if exists
        if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
            Storage::disk('public')->delete($user->foto_profile);
        }

        // Upload new photo
        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');
            $filename = time() . '_' . $user->id_user . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');

            $user->update([
                'foto_profile' => $path,
            ]);
        }

        return $user;
    }

    public function deleteProfilePhoto()
    {
        $user = auth()->user();

        if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
            Storage::disk('public')->delete($user->foto_profile);

            $user->update([
                'foto_profile' => null,
            ]);

            return true;
        }

        return false;
    }

    public function updatePassword($request)
    {
        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            throw new \Exception('The current password is incorrect.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $user;
    }
}