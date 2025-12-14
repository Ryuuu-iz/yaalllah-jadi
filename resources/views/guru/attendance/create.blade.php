@extends('layouts.guru')

@section('title', 'Create Attendance')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.attendance.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Attendance
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Create Attendance</h1>
        <p class="text-gray-600 mt-2">Create a new attendance session for your students</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Attendance Information
            </h2>
        </div>

        <!-- Form -->
        <form action="{{ route('guru.attendance.store') }}" method="POST" class="p-6 space-y-6" x-data="{ 
            mode: '{{ old('mode', 'self') }}',
            selectedCourse: '{{ old('id_course', request('id_course')) }}',
            students: []
        }">
            @csrf

            <!-- Course Selection -->
            <div>
                <label for="id_course" class="block text-sm font-medium text-gray-700 mb-2">
                    Course <span class="text-red-500">*</span>
                </label>
                <select name="id_course" 
                        id="id_course" 
                        x-model="selectedCourse"
                        @change="loadStudents()"
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_course') border-red-500 @enderror">
                    <option value="">Select a course...</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id_course }}" 
                                data-students='@json($course->siswa)'
                                {{ old('id_course', request('id_course')) == $course->id_course ? 'selected' : '' }}>
                            {{ $course->judul }} - {{ $course->kelas->nama_kelas }} ({{ $course->mataPelajaran->nama_mapel }})
                        </option>
                    @endforeach
                </select>
                @error('id_course')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attendance Date -->
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                    Attendance Date <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="tanggal" 
                       id="tanggal" 
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       required
                       max="{{ date('Y-m-d') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror">
                @error('tanggal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attendance Mode -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Attendance Mode <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Self Attendance -->
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none" :class="mode === 'self' ? 'border-blue-500 ring-2 ring-blue-500' : 'border-gray-300'">
                        <input type="radio" name="mode" value="self" x-model="mode" class="sr-only">
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <span class="block text-sm font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    Self Attendance
                                </span>
                                <span class="mt-1 flex items-center text-sm text-gray-500">
                                    Students mark their own attendance
                                </span>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-blue-600" :class="mode === 'self' ? 'block' : 'hidden'" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </label>

                    <!-- Manual Attendance -->
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none" :class="mode === 'manual' ? 'border-blue-500 ring-2 ring-blue-500' : 'border-gray-300'">
                        <input type="radio" name="mode" value="manual" x-model="mode" class="sr-only">
                        <div class="flex flex-1">
                            <div class="flex flex-col">
                                <span class="block text-sm font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Manual Entry
                                </span>
                                <span class="mt-1 flex items-center text-sm text-gray-500">
                                    You enter attendance manually
                                </span>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-blue-600" :class="mode === 'manual' ? 'block' : 'hidden'" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </label>
                </div>
            </div>

            <!-- Deadline (only for self attendance) -->
            <div x-show="mode === 'self'" x-transition>
                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                    Attendance Deadline <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" 
                       name="deadline" 
                       id="deadline" 
                       value="{{ old('deadline') }}"
                       :required="mode === 'self'"
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deadline') border-red-500 @enderror">
                @error('deadline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Set when students must complete their attendance</p>
            </div>

            <!-- Student List (only for manual mode and if course selected) -->
            <div x-show="mode === 'manual' && selectedCourse" x-transition>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Mark Attendance <span class="text-red-500">*</span>
                </label>
                
                <div x-show="!siswaList || siswaList.length === 0" class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p>Please select a course to see students</p>
                </div>

                @if($selectedCourse && $siswaList->isNotEmpty())
                <div class="space-y-3 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4">
                    @foreach($siswaList as $siswa)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">
                                {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $siswa->nama }}</h3>
                                <p class="text-sm text-gray-500 truncate">{{ $siswa->nisn }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 ml-4">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="radio" name="absensi[{{ $siswa->id_siswa }}]" value="hadir" class="sr-only peer" {{ old('absensi.'.$siswa->id_siswa) == 'hadir' ? 'checked' : '' }}>
                                <div class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg peer-checked:bg-green-100 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">
                                    <span class="text-sm font-medium">Present</span>
                                </div>
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="radio" name="absensi[{{ $siswa->id_siswa }}]" value="izin" class="sr-only peer" {{ old('absensi.'.$siswa->id_siswa) == 'izin' ? 'checked' : '' }}>
                                <div class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg peer-checked:bg-blue-100 peer-checked:border-blue-500 peer-checked:text-blue-700 transition-all">
                                    <span class="text-sm font-medium">Permission</span>
                                </div>
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="radio" name="absensi[{{ $siswa->id_siswa }}]" value="sakit" class="sr-only peer" {{ old('absensi.'.$siswa->id_siswa) == 'sakit' ? 'checked' : '' }}>
                                <div class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg peer-checked:bg-yellow-100 peer-checked:border-yellow-500 peer-checked:text-yellow-700 transition-all">
                                    <span class="text-sm font-medium">Sick</span>
                                </div>
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="radio" name="absensi[{{ $siswa->id_siswa }}]" value="alpha" class="sr-only peer" {{ old('absensi.'.$siswa->id_siswa, 'alpha') == 'alpha' ? 'checked' : '' }}>
                                <div class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg peer-checked:bg-red-100 peer-checked:border-red-500 peer-checked:text-red-700 transition-all">
                                    <span class="text-sm font-medium">Absent</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800" x-text="mode === 'self' ? 'Self Attendance Mode' : 'Manual Entry Mode'"></h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <div x-show="mode === 'self'">
                                <p class="mb-2"><strong>How it works:</strong></p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Students will mark their own attendance before the deadline</li>
                                    <li>Students who don't attend will be marked as absent automatically</li>
                                    <li>You can close attendance early or reopen it later</li>
                                    <li>Students can provide notes (e.g., sick, permission)</li>
                                </ul>
                            </div>
                            <div x-show="mode === 'manual'">
                                <p class="mb-2"><strong>How it works:</strong></p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>You mark each student's attendance status manually</li>
                                    <li>Attendance is recorded immediately</li>
                                    <li>You can edit the attendance later if needed</li>
                                    <li>Students cannot change their attendance status</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="flex-1 sm:flex-initial bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span x-text="mode === 'self' ? 'Create Attendance Session' : 'Save Attendance'"></span>
                </button>
                <a href="{{ route('guru.attendance.index') }}" class="flex-1 sm:flex-initial bg-gray-300 hover:bg-gray-400 text-gray-700 px-8 py-3 rounded-lg font-medium transition-colors text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load students when course is selected
    const courseSelect = document.getElementById('id_course');
    
    if (courseSelect && courseSelect.value) {
        // Auto-load students if course is pre-selected
        loadStudentsForCourse(courseSelect.value);
    }
});

function loadStudentsForCourse(courseId) {
    const courseSelect = document.getElementById('id_course');
    const selectedOption = courseSelect.options[courseSelect.selectedIndex];
    
    // Students data is embedded in the option's data attribute
    // This is already handled by Blade when the course is pre-selected
}
</script>
@endsection