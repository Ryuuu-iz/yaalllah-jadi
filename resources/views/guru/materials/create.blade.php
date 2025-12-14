@extends('layouts.guru')

@section('title', 'Add New Material')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.materials.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Materials
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Add New Material</h1>
        <p class="text-gray-600 mt-2">Upload learning material for your students</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Material Information
            </h2>
        </div>

        <!-- Form -->
        <form action="{{ route('guru.materials.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Material Title -->
            <div>
                <label for="nama_materi" class="block text-sm font-medium text-gray-700 mb-2">
                    Material Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_materi" 
                       id="nama_materi" 
                       value="{{ old('nama_materi') }}"
                       required
                       placeholder="e.g., Introduction to Linear Equations"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_materi') border-red-500 @enderror">
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
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_course') border-red-500 @enderror">
                    <option value="">Select a course...</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id_course }}" {{ old('id_course', request('id_course')) == $course->id_course ? 'selected' : '' }}>
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
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_TA') border-red-500 @enderror">
                    <option value="">Select academic year...</option>
                    @foreach($tahunAjaran as $ta)
                        <option value="{{ $ta->id_TA }}" {{ old('id_TA') == $ta->id_TA ? 'selected' : '' }}>
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
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('desk_materi') border-red-500 @enderror">{{ old('desk_materi') }}</textarea>
                @error('desk_materi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Provide a brief overview of what students will learn from this material</p>
            </div>

            <!-- File Upload -->
            <div>
                <label for="file_materi" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload File <span class="text-gray-400">(Optional)</span>
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="file_materi" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR (MAX. 20MB)</p>
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

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Material Tips</h3>
                        <div class="mt-2 text-sm text-blue-700 space-y-1">
                            <p>• Use clear and descriptive titles for easy identification</p>
                            <p>• Add detailed descriptions to help students understand the content</p>
                            <p>• Organize materials by topics or modules</p>
                            <p>• Supported formats: PDF, Word documents, PowerPoint, and compressed files</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="flex-1 sm:flex-initial bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Material
                </button>
                <a href="{{ route('guru.materials.index') }}" class="flex-1 sm:flex-initial bg-gray-300 hover:bg-gray-400 text-gray-700 px-8 py-3 rounded-lg font-medium transition-colors text-center">
                    Cancel
                </a>
            </div>
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
            <span class="flex items-center text-blue-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Selected: <strong class="ml-1">${fileName}</strong> (${fileSize} MB)
            </span>
        `;
    }
}
</script>
@endsection