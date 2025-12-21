<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfilePhotoRequest;
use App\Http\Requests\UpdateProfilePasswordRequest;
use App\Services\UserProfileService;
use Illuminate\Http\Request;

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