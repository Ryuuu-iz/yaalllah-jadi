<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User hanya bisa mengupdate profil mereka sendiri
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'foto_profile.required' => 'Profile photo is required.',
            'foto_profile.image' => 'Profile photo must be an image.',
            'foto_profile.mimes' => 'Profile photo must be a JPEG, PNG, or JPG file.',
            'foto_profile.max' => 'Profile photo must not exceed 2MB.',
        ];
    }
}
