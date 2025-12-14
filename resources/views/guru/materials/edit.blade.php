@extends('layouts.guru')

@section('title', 'Edit Material - ' . $material->nama_materi)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.materials.show', $material->id_materi) }}" class="inline-flex items-center text-green-600 hover:text-green-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Material Details
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Material</h1>
        <p class="text-gray-600 mt-2">Update material information and file</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Material Information
            </h2>
        </div>

        <!-- Form -->
        <form action="{{ route('guru.materials.update', $material->id_materi) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Material Title -->
            <div>
                <label for="nama_materi" class="block text-sm font-medium text-gray-700 mb-2">
                    Material Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_materi" 
                       id="nama_materi" 
                       value="{{ old('nama_materi', $material->nama_materi) }}"
                       required
                       placeholder="e.g., Introduction to Linear Equations"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('nama_materi') border-red-500 @enderror">
                @error('nama_materi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Course -->
            <div>
                <label for="id_course" class="block text-sm font-medium text-gray-700 mb-2">
                    Course <span class="text-red-500">*</span>
                </label>
                <select name="id_course" 
                        id="id_course" 
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('id_course') border-red-500 @enderror">
                    <option value="">Select a course...</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id_course }}" {{ old('id_course', $material->id_course) == $course->id_course ? 'selected' : '' }}>
                            {{ $course->judul }} - {{ $course->kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error('id_course')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Academic Year -->
            <div>
                <label for="id_TA" class="block text-sm font-medium text-gray-700 mb-2">
                    Academic Year <span class="text-red-500">*</span>
                </label>
                <select name="id_TA" 
                        id="id_TA" 
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('id_TA') border-red-500 @enderror">
                    <option value="">Select academic year...</option>
                    @foreach($tahunAjaran as $ta)
                        <option value="{{ $ta->id_TA }}" {{ old('id_TA', $material->id_TA) == $ta->id_TA ? 'selected' : '' }}>
                            {{ $ta->semester }}
                            @if($ta->status === 'aktif')
                                (Active)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('id_TA')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="desk_materi" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-gray-400">(Optional)</span>
                </label>
                <textarea name="desk_materi" 
                          id="desk_materi" 
                          rows="4"
                          placeholder="Describe the content of this material..."
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('desk_materi') border-red-500 @enderror">{{ old('desk_materi', $material->desk_materi) }}</textarea>
                @error('desk_materi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Provide a brief overview of what students will learn from this material</p>
            </div>

            <!-- Current File Info -->
            @if($material->file_materi)
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-green-800">Current File</h3>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-sm text-green-700">
                                <strong>{{ basename($material->file_materi) }}</strong>
                            </p>
                            <a href="{{ Storage::url($material->file_materi) }}" target="_blank" class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- File Upload -->
            <div>
                <label for="file_materi" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload New File <span class="text-gray-400">(Optional - leave empty to keep current file)</span>
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="file_materi" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR (MAX. 20MB)</p>
                            <p class="text-xs text-gray-400 mt-2">Upload a new file to replace the current one</p>
                        </div>
                        <input id="file_materi" 
                               name="file_materi" 
                               type="file" 
                               class="hidden" 
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                               onchange="displayFileName(this)">
                    </label>
                </div>
                <p id="file-name" class="mt-2 text-sm text-gray-600"></p>
                @error('file_materi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warning Box -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-yellow-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Important Note</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Uploading a new file will replace the current file permanently. The old file cannot be recovered after replacement.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="flex-1 sm:flex-initial bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition-colors shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('guru.materials.show', $material->id_materi) }}" class="flex-1 sm:flex-initial bg-gray-300 hover:bg-gray-400 text-gray-700 px-8 py-3 rounded-lg font-medium transition-colors text-center">
                    Cancel
                </a>
                <button type="button" 
                        onclick="if(confirm('Are you sure you want to delete this material? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }"
                        class="flex-1 sm:flex-initial bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-lg font-medium transition-colors shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Material
                </button>
            </div>
        </form>

        <!-- Hidden Delete Form -->
        <form id="delete-form" action="{{ route('guru.materials.destroy', $material->id_materi) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
function displayFileName(input) {
    const fileName = input.files[0]?.name;
    const fileSize = (input.files[0]?.size / (1024 * 1024)).toFixed(2);
    const fileNameDisplay = document.getElementById('file-name');
    
    if (fileName) {
        fileNameDisplay.innerHTML = `
            <span class="flex items-center text-green-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                New file selected: <strong class="ml-1">${fileName}</strong> (${fileSize} MB)
            </span>
        `;
    }
}
</script>
@endsection