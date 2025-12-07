@extends('layouts.admin')

@section('title', 'Add New Material')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.materials.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Materials
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Add New Material</h1>
            <p class="mt-1 text-sm text-gray-600">Create a new learning material for a course</p>
        </div>

        <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-4">
            @csrf

            <div class="space-y-6">
                <!-- Material Name -->
                <div>
                    <label for="nama_materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Material Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_materi" id="nama_materi" value="{{ old('nama_materi') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_materi') border-red-500 @enderror"
                        placeholder="e.g., Introduction to Algebra, Cell Biology Basics">
                    @error('nama_materi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="desk_materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="desk_materi" id="desk_materi" rows="4"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('desk_materi') border-red-500 @enderror"
                        placeholder="Describe what this material covers...">{{ old('desk_materi') }}</textarea>
                    @error('desk_materi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Selection -->
                <div>
                    <label for="id_course" class="block text-sm font-medium text-gray-700 mb-2">
                        Course <span class="text-red-500">*</span>
                    </label>
                    <select name="id_course" id="id_course" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_course') border-red-500 @enderror">
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id_course }}" {{ old('id_course') == $course->id_course ? 'selected' : '' }}>
                                {{ $course->judul }} - {{ $course->mataPelajaran->nama_mapel }} ({{ $course->kelas->nama_kelas }}) - {{ $course->guru->nama }}
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
                    <select name="id_TA" id="id_TA" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_TA') border-red-500 @enderror">
                        <option value="">-- Select Academic Year --</option>
                        @foreach($tahunAjaran as $ta)
                            <option value="{{ $ta->id_TA }}" {{ old('id_TA') == $ta->id_TA ? 'selected' : '' }}>
                                {{ $ta->semester }}
                                @if($ta->status === 'aktif')
                                    <span>(Active)</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('id_TA')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div>
                    <label for="file_materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Material File (Optional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file_materi" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="file_materi" name="file_materi" type="file" class="sr-only" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar" onchange="updateFileName(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR up to 20MB</p>
                            <p id="file-name" class="text-sm text-blue-600 font-medium mt-2"></p>
                        </div>
                    </div>
                    @error('file_materi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Material Guidelines</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Use clear and descriptive material names</li>
                                    <li>Provide detailed descriptions to help students understand the content</li>
                                    <li>Ensure uploaded files are properly formatted and readable</li>
                                    <li>Maximum file size is 20MB</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.materials.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md flex items-center transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Material
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameDisplay = document.getElementById('file-name');
    if (fileName) {
        fileNameDisplay.textContent = `Selected: ${fileName}`;
    } else {
        fileNameDisplay.textContent = '';
    }
}
</script>
@endsection