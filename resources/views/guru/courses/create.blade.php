@extends('layouts.guru')

@section('title', 'Create New Class')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center text-green-600 hover:text-green-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to My Classes
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Create New Class</h1>
        <p class="text-gray-600 mt-2">Fill in the details to create a new class</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-green-500 to-green-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Class Information
            </h2>
        </div>

        <!-- Form -->
        <form action="{{ route('guru.courses.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Course Title -->
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                    Class Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="judul" 
                       id="judul" 
                       value="{{ old('judul') }}"
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
                        <option value="{{ $mapel->id_mapel }}" {{ old('id_mapel') == $mapel->id_mapel ? 'selected' : '' }}>
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
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
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
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
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
                <p class="mt-1 text-sm text-gray-500">Select the academic year for this class</p>
            </div>

            <!-- Enrollment Key -->
            <div x-data="{ 
                key: '{{ old('enrollment_key', strtoupper(Str::random(8))) }}',
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
                    Enrollment Key <span class="text-gray-400">(Optional)</span>
                </label>
                <div class="flex gap-2">
                    <input type="text" 
                           name="enrollment_key" 
                           id="enrollment_key" 
                           x-model="key"
                           placeholder="Auto-generated if left empty"
                           maxlength="8"
                           class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 font-mono uppercase @error('enrollment_key') border-red-500 @enderror">
                    <button type="button" 
                            @click="generateKey()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
                @error('enrollment_key')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Students will use this key to enroll. Auto-generated if empty. Must be 8 characters.</p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">What happens after creating?</h3>
                        <div class="mt-2 text-sm text-blue-700 space-y-1">
                            <p>• An enrollment key will be automatically generated</p>
                            <p>• You can start adding materials and assignments</p>
                            <p>• Students can enroll using the enrollment key</p>
                            <p>• You can manually enroll students from the class page</p>
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
                    Create Class
                </button>
                <a href="{{ route('guru.dashboard') }}" class="flex-1 sm:flex-initial bg-gray-300 hover:bg-gray-400 text-gray-700 px-8 py-3 rounded-lg font-medium transition-colors text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Tips for Creating a Great Class
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p><strong>Clear Title:</strong> Use a descriptive name that includes the subject and grade level</p>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p><strong>Good Description:</strong> Outline key topics and learning objectives clearly</p>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p><strong>Right Class:</strong> Make sure to select the correct grade level for your students</p>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p><strong>Organization:</strong> Plan to add materials and assignments regularly for better learning</p>
            </div>
        </div>
    </div>
</div>
@endsection