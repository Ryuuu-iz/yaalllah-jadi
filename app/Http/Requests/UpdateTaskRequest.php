<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'nama_tugas' => 'required|string|max:255',
            'desk_tugas' => 'nullable|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240', // 10MB max
            'deadline' => 'required|date',
            'id_course' => 'required|exists:course,id_course',
            'id_materi' => 'required|exists:materi_pembelajaran,id_materi',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'nama_tugas.required' => 'Task name is required.',
            'nama_tugas.max' => 'Task name cannot exceed 255 characters.',
            'deadline.required' => 'Deadline is required.',
            'deadline.date' => 'Deadline must be a valid date.',
            'id_course.required' => 'Course selection is required.',
            'id_course.exists' => 'Selected course does not exist.',
            'id_materi.required' => 'Related material is required.',
            'id_materi.exists' => 'Selected material does not exist.',
            'file_tugas.mimes' => 'Task file must be a PDF, DOC, DOCX, PPT, or PPTX file.',
            'file_tugas.max' => 'Task file cannot exceed 10MB.',
        ];
    }
}
