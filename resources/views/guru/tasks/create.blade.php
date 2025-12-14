@extends('layouts.guru')

@section('title', 'Create New Assignment')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.tasks.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Assignments
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Create New Assignment</h1>
        <p class="text-gray-600 mt-2">Create a new assignment for your students</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Assignment Information
            </h2>
        </div>

        <!-- Form -->
        <form action="{{ route('guru.tasks.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" x-data="{ selectedCourse: '{{ old('id_course', request('id_course')) }}' }">
            @csrf

            <!-- Assignment Title -->
            <div>
                <label for="nama_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                    Assignment Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_tugas" 
                       id="nama_tugas" 
                       value="{{ old('nama_tugas') }}"
                       required
                       placeholder="e.g., Homework: Linear Equations Practice"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('nama_tugas') border-red-500 @enderror">
                @error('nama_tugas')
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
                        x-model="selectedCourse"
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('id_course') border-red-500 @enderror">
                    <option value="">Select a course...</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id_course }}" 
                                data-materials='@json($course->materiPembelajaran)'
                                {{ old('id_course', request('id_course')) == $course->id_course ? 'selected' : '' }}>
                            {{ $course->judul }} - {{ $course->kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error('id_course')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Material -->
            <div>
                <label for="id_materi" class="block text-sm font-medium text-gray-700 mb-2">
                    Related Material <span class="text-red-500">*</span>
                </label>
                <select name="id_materi" 
                        id="id_materi" 
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('id_materi') border-red-500 @enderror">
                    <option value="">Select a material...</option>
                </select>
                @error('id_materi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Select the course first to see available materials</p>
            </div>

            <!-- Description -->
            <div>
                <label for="desk_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-gray-400">(Optional)</span>
                </label>
                <textarea name="desk_tugas" 
                          id="desk_tugas" 
                          rows="5"
                          placeholder="Describe the assignment instructions, requirements, and evaluation criteria..."
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('desk_tugas') border-red-500 @enderror">{{ old('desk_tugas') }}</textarea>
                @error('desk_tugas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Provide clear instructions and expectations</p>
            </div>

            <!-- Deadline -->
            <div>
                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                    Deadline <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" 
                       name="deadline" 
                       id="deadline" 
                       value="{{ old('deadline') }}"
                       required
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 @error('deadline') border-red-500 @enderror">
                @error('deadline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Set when students must submit this assignment</p>
            </div>

            <!-- File Upload -->
            <div>
                <label for="file_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                    Attachment File <span class="text-gray-400">(Optional)</span>
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="file_tugas" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX (MAX. 10MB)</p>
                        </div>
                        <input id="file_tugas" 
                               name="file_tugas" 
                               type="file" 
                               class="hidden" 
                               accept=".pdf,.doc,.docx,.ppt,.pptx"
                               onchange="displayFileName(this)">
                    </label>
                </div>
                <p id="file-name" class="mt-2 text-sm text-gray-600"></p>
                @error('file_tugas')
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
                        <h3 class="text-sm font-medium text-blue-800">Assignment Tips</h3>
                        <div class="mt-2 text-sm text-blue-700 space-y-1">
                            <p>• Use clear and specific titles</p>
                            <p>• Provide detailed instructions in the description</p>
                            <p>• Set realistic deadlines for students</p>
                            <p>• Attach reference materials or guidelines if needed</p>
                            <p>• Link to the relevant learning material</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="flex-1 sm:flex-initial bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-medium transition-colors shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Assignment
                </button>
                <a href="{{ route('guru.tasks.index') }}" class="flex-1 sm:flex-initial bg-gray-300 hover:bg-gray-400 text-gray-700 px-8 py-3 rounded-lg font-medium transition-colors text-center">
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
            <span class="flex items-center text-purple-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Selected: <strong class="ml-1">${fileName}</strong> (${fileSize} MB)
            </span>
        `;
    }
}

// Dynamic material loading based on selected course
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('id_course');
    const materialSelect = document.getElementById('id_materi');
    const oldMaterial = '{{ old('id_materi', request('id_materi')) }}';
    
    function updateMaterials() {
        const selectedOption = courseSelect.options[courseSelect.selectedIndex];
        const materials = selectedOption.getAttribute('data-materials');
        
        // Clear existing options
        materialSelect.innerHTML = '<option value="">Select a material...</option>';
        
        if (materials) {
            const materialsData = JSON.parse(materials);
            materialsData.forEach(material => {
                const option = document.createElement('option');
                option.value = material.id_materi;
                option.textContent = material.nama_materi;
                if (oldMaterial && material.id_materi == oldMaterial) {
                    option.selected = true;
                }
                materialSelect.appendChild(option);
            });
        }
    }
    
    courseSelect.addEventListener('change', updateMaterials);
    
    // Load materials on page load if course is already selected
    if (courseSelect.value) {
        updateMaterials();
    }
});
</script>
@endsection