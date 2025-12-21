<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_materi' => 'required|string|max:255',
            'desk_materi' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:20480', // 20MB max
            'id_course' => 'required|exists:course,id_course',
            'id_TA' => 'required|exists:tahun_ajaran,id_TA',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nama_materi.required' => 'Material name is required.',
            'nama_materi.max' => 'Material name cannot exceed 255 characters.',
            'id_course.required' => 'Course selection is required.',
            'id_course.exists' => 'Selected course does not exist.',
            'id_TA.required' => 'Academic year selection is required.',
            'id_TA.exists' => 'Selected academic year does not exist.',
            'file_materi.mimes' => 'Material file must be a PDF, DOC, DOCX, PPT, PPTX, ZIP, or RAR file.',
            'file_materi.max' => 'Material file cannot exceed 20MB.',
        ];
    }
}
