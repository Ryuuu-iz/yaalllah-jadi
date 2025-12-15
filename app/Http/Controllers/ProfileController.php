<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
     * Update foto profile
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        // Hapus foto lama jika ada
        if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
            Storage::disk('public')->delete($user->foto_profile);
        }

        // Upload foto baru
        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');
            $filename = time() . '_' . $user->id_user . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $filename, 'public');
            
            $user->update([
                'foto_profile' => $path,
            ]);
        }

        return back()->with('success', 'Profile photo updated successfully');
    }

    /**
     * Delete foto profile
     */
    public function deletePhoto()
    {
        $user = auth()->user();

        if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
            Storage::disk('public')->delete($user->foto_profile);
            
            $user->update([
                'foto_profile' => null,
            ]);

            return back()->with('success', 'Profile photo deleted successfully');
        }

        return back()->with('error', 'No profile photo to delete');
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