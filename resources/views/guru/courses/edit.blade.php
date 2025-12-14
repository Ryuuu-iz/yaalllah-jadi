@extends('layouts.guru')

@section('title', 'Edit Class - ' . $course->judul)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.courses.show', $course->id_course) }}" class="inline-flex items-center text-green-600 hover:text-green-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Class Details
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Class</h1>
        <p class="text-gray-600 mt-2">Update class information</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Class Information
            </h2>
        </div>

        <!-- Form -->
        <form action="{{ route('guru.courses.update', $course->id_course) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Course Title -->
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                    Class Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="judul" 
                       id="judul" 
                       value="{{ old('judul', $course->judul) }}"
                       required
                       placeholder="e.g., Mathematics for Grade 10"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Give your class a clear and descriptive title</p>
            </div>

            <!-- Subject -->
            <div>
                <label for="id_mapel" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject <span class="text-red-500">*</span>
                </label>
                <select name="id_mapel" 
                        id="id_mapel" 
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('id_mapel') border-red-500 @enderror">
                    <option value="">Select a subject...</option>
                    @foreach($mataPelajaran as $mapel)
                        <option value="{{ $mapel->id_mapel }}" {{ old('id_mapel', $course->id_mapel) == $mapel->id_mapel ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
                @error('id_mapel')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Class/Grade -->
            <div>
                <label for="id_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                    Class/Grade <span class="text-red-500">*</span>
                </label>
                <select name="id_kelas" 
                        id="id_kelas" 
                        required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('id_kelas') border-red-500 @enderror">
                    <option value="">Select a class...</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $course->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} ({{ $k->tingkatan }})
                        </option>
                    @endforeach
                </select>
                @error('id_kelas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-gray-400">(Optional)</span>
                </label>
                <textarea name="deskripsi" 
                          id="deskripsi" 
                          rows="4"
                          placeholder="Describe what students will learn in this class..."
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $course->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Provide a brief overview of the class content and objectives</p>
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
                        <option value="{{ $ta->id_TA }}" {{ old('id_TA', $course->id_TA) == $ta->id_TA ? 'selected' : '' }}>
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
                <p class="mt-1 text-sm text-gray-500">Select the academic year for this class</p>
            </div>

            <!-- Enrollment Key -->
            <div x-data="{ 
                key: '{{ old('enrollment_key', $course->enrollment_key) }}',
                generateKey() {
                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    let result = '';
                    for (let i = 0; i < 8; i++) {
                        result += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    this.key = result;
                }
            }">
                <label for="enrollment_key" class="block text-sm font-medium text-gray-700 mb-2">
                    Enrollment Key <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    <input type="text" 
                           name="enrollment_key" 
                           id="enrollment_key" 
                           x-model="key"
                           required
                           maxlength="8"
                           class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 font-mono uppercase @error('enrollment_key') border-red-500 @enderror">
                    <button type="button" 
                            @click="generateKey()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
                            title="Generate New Key">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
                @error('enrollment_key')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Students use this key to enroll. Changing it will require students to re-enroll.</p>
            </div>

            <!-- Current Info Box -->
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-green-800">Current Class Information</h3>
                        <div class="mt-2 text-sm text-green-700 space-y-1">
                            <p>• <strong>Enrollment Key:</strong> {{ $course->enrollment_key }}</p>
                            <p>• <strong>Created:</strong> {{ $course->created_at->format('d M Y') }}</p>
                            <p>• <strong>Total Students:</strong> {{ $course->siswa->count() }}</p>
                            <p>• <strong>Materials:</strong> {{ $course->materiPembelajaran->count() }}</p>
                            <p>• <strong>Assignments:</strong> {{ $course->tugas->count() }}</p>
                        </div>
                    </div>
                </div>
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
                            <p>Changing the class or subject may affect enrolled students. Make sure all students are still appropriate for the new class settings.</p>
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
                <a href="{{ route('guru.courses.show', $course->id_course) }}" class="flex-1 sm:flex-initial bg-gray-300 hover:bg-gray-400 text-gray-700 px-8 py-3 rounded-lg font-medium transition-colors text-center">
                    Cancel
                </a>
                <button type="button" 
                        onclick="if(confirm('Are you sure you want to delete this class? All materials, assignments, and attendance records will be permanently deleted. This action cannot be undone.')) { document.getElementById('delete-form').submit(); }"
                        class="flex-1 sm:flex-initial bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-lg font-medium transition-colors shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Class
                </button>
            </div>
        </form>

        <!-- Hidden Delete Form -->
        <form id="delete-form" action="{{ route('guru.courses.destroy', $course->id_course) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <!-- Statistics Card -->
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Class Statistics
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-3xl font-bold text-blue-600">{{ $course->siswa->count() }}</p>
                    <p class="text-sm text-blue-800 mt-1">Enrolled Students</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-3xl font-bold text-green-600">{{ $course->materiPembelajaran->count() }}</p>
                    <p class="text-sm text-green-800 mt-1">Learning Materials</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-3xl font-bold text-purple-600">{{ $course->tugas->count() }}</p>
                    <p class="text-sm text-purple-800 mt-1">Assignments</p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <p class="text-3xl font-bold text-orange-600">{{ \App\Models\RekapAbsensi::where('id_kelas', $course->id_kelas)->where('id_mapel', $course->id_mapel)->where('id_guru', $course->id_guru)->select('tanggal')->distinct()->count() }}</p>
                    <p class="text-sm text-orange-800 mt-1">Attendance Days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('guru.courses.show', $course->id_course) }}#students" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Manage</p>
                    <p class="text-lg font-semibold text-gray-900">Students</p>
                </div>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('guru.materials.create') }}?id_course={{ $course->id_course }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Add</p>
                    <p class="text-lg font-semibold text-gray-900">Material</p>
                </div>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('guru.tugas.create') }}?id_course={{ $course->id_course }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Create</p>
                    <p class="text-lg font-semibold text-gray-900">Assignment</p>
                </div>
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('guru.absensi.create') }}?id_course={{ $course->id_course }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Take</p>
                    <p class="text-lg font-semibold text-gray-900">Attendance</p>
                </div>
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </a>
    </div>
</div>
@endsection