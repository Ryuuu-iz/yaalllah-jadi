@extends('layouts.admin')

@section('title', 'Edit Task')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.tasks.show', $task->id_tugas) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Task Details
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Edit Task: {{ $task->nama_tugas }}</h1>
            <p class="mt-1 text-sm text-gray-600">Update task information and settings</p>
        </div>

        <form action="{{ route('admin.tasks.update', $task->id_tugas) }}" method="POST" enctype="multipart/form-data" class="px-6 py-4">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Task Name -->
                <div>
                    <label for="nama_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                        Task Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_tugas" id="nama_tugas" value="{{ old('nama_tugas', $task->nama_tugas) }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_tugas') border-red-500 @enderror"
                        placeholder="e.g., Chapter 1 Quiz, Essay on Photosynthesis">
                    @error('nama_tugas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="desk_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="desk_tugas" id="desk_tugas" rows="4"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('desk_tugas') border-red-500 @enderror"
                        placeholder="Describe the task requirements and instructions...">{{ old('desk_tugas', $task->desk_tugas) }}</textarea>
                    @error('desk_tugas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Selection -->
                <div>
                    <label for="id_course" class="block text-sm font-medium text-gray-700 mb-2">
                        Course <span class="text-red-500">*</span>
                    </label>
                    <select name="id_course" id="id_course" required onchange="loadMaterials()"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_course') border-red-500 @enderror">
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id_course }}" 
                                data-materials="{{ $course->materiPembelajaran->toJson() }}"
                                {{ old('id_course', $task->id_course) == $course->id_course ? 'selected' : '' }}>
                                {{ $course->judul }} - {{ $course->mataPelajaran->nama_mapel }} ({{ $course->kelas->nama_kelas }}) - {{ $course->guru->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_course')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Material Selection -->
                <div>
                    <label for="id_materi" class="block text-sm font-medium text-gray-700 mb-2">
                        Related Material <span class="text-red-500">*</span>
                    </label>
                    <select name="id_materi" id="id_materi" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_materi') border-red-500 @enderror">
                        <option value="">-- Select Course First --</option>
                    </select>
                    @error('id_materi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                        Deadline <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="deadline" id="deadline" 
                        value="{{ old('deadline', $task->deadline->format('Y-m-d\TH:i')) }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deadline') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Set the deadline for students to submit this task</p>
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current File Display -->
                @if($task->file_tugas)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Current File</p>
                                <p class="text-xs text-gray-600 mt-1">{{ basename($task->file_tugas) }}</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($task->file_tugas) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
                @endif

                <!-- File Upload -->
                <div>
                    <label for="file_tugas" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $task->file_tugas ? 'Replace Task File (Optional)' : 'Upload Task File (Optional)' }}
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file_tugas" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="file_tugas" name="file_tugas" type="file" class="sr-only" accept=".pdf,.doc,.docx,.ppt,.pptx" onchange="updateFileName(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX up to 10MB</p>
                            <p id="file-name" class="text-sm text-blue-600 font-medium mt-2"></p>
                        </div>
                    </div>
                    @if($task->file_tugas)
                    <p class="mt-2 text-sm text-gray-500">
                        <strong>Note:</strong> Uploading a new file will replace the existing file.
                    </p>
                    @endif
                    @error('file_tugas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Warning Box -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Changes will affect all students enrolled in the course</li>
                                    <li>If you change the deadline, notify students about the update</li>
                                    <li>If you replace the file, the old file will be permanently deleted</li>
                                    <li>Existing submissions will not be affected</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Statistics -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Task Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $task->course->siswa()->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Total Students</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $task->pengumpulanTugas()->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Submissions</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $task->pengumpulanTugas()->whereNotNull('nilai')->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Graded</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-red-600">{{ $task->pengumpulanTugas()->where('status', 'terlambat')->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Late</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.tasks.show', $task->id_tugas) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md flex items-center transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Task
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

function loadMaterials() {
    const courseSelect = document.getElementById('id_course');
    const materiSelect = document.getElementById('id_materi');
    const selectedOption = courseSelect.options[courseSelect.selectedIndex];
    
    // Clear existing options
    materiSelect.innerHTML = '<option value="">-- Select Material --</option>';
    
    if (selectedOption.value) {
        const materials = JSON.parse(selectedOption.getAttribute('data-materials') || '[]');
        
        if (materials.length === 0) {
            materiSelect.innerHTML = '<option value="">-- No materials available --</option>';
        } else {
            materials.forEach(material => {
                const option = document.createElement('option');
                option.value = material.id_materi;
                option.textContent = material.nama_materi;
                materiSelect.appendChild(option);
            });
        }
    }
}

// Load materials on page load
document.addEventListener('DOMContentLoaded', function() {
    loadMaterials();
    
    // Restore selected material
    const currentMateri = '{{ old("id_materi", $task->id_materi) }}';
    if (currentMateri) {
        document.getElementById('id_materi').value = currentMateri;
    }
});
</script>
@endsection