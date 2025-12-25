<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfilePhotoRequest;
use App\Http\Requests\UpdateProfilePasswordRequest;
use App\Services\UserProfileService;
use Illuminate\Http\Request;
use App\Models\DataGuru;
use App\Models\DataSiswa;

class ProfileController extends Controller
{
    protected $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    /**
     * Show the profile page
     */
    public function index()
    {
        $user = $this->userProfileService->getUserProfile();

        return view('profile.index', compact('user'));
    }

    /**
     * Show the profile completion form
     */
    public function showCompletionForm()
    {
        $user = auth()->user();

        // Check if user already has the profile data
        if (($user->role === 'guru' && $user->dataGuru) || ($user->role === 'siswa' && $user->dataSiswa)) {
            // If they already have the data, redirect to their dashboard
            return redirect()->route($user->role . '.dashboard');
        }

        return view('profile.complete', compact('user'));
    }

    /**
     * Complete the user profile
     */
    public function completeProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if ($user->role === 'guru') {
            // Check if DataGuru already exists
            if (!$user->dataGuru) {
                DataGuru::create([
                    'id_user' => $user->id_user,
                    'nama' => $request->nama,
                    'nip' => $request->nip ?? null,
                ]);
            }
        } elseif ($user->role === 'siswa') {
            // Check if DataSiswa already exists
            if (!$user->dataSiswa) {
                DataSiswa::create([
                    'id_user' => $user->id_user,
                    'nama' => $request->nama,
                    'nisn' => $request->nisn ?? null,
                ]);
            }
        }

        // Redirect to appropriate dashboard after completion
        return redirect()->route($user->role . '.dashboard')->with('success', 'Profile completed successfully!');
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(UpdateProfilePhotoRequest $request)
    {
        $this->userProfileService->updateProfilePhoto($request);

        return back()->with('success', 'Profile photo updated successfully');
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto()
    {
        $result = $this->userProfileService->deleteProfilePhoto();

        if ($result) {
            return back()->with('success', 'Profile photo deleted successfully');
        }

        return back()->with('error', 'No profile photo to delete');
    }

    /**
     * Update password
     */
    public function updatePassword(UpdateProfilePasswordRequest $request)
    {
        try {
            $this->userProfileService->updatePassword($request);

            return back()->with('success', 'Password updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['current_password' => $e->getMessage()]);
        }
    }
}