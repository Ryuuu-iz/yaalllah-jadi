@extends('layouts.admin')

@section('title', 'Edit Course')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.courses.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Courses
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Edit Course: {{ $course->judul }}</h1>
        </div>

        <form action="{{ route('admin.courses.update', $course->id_course) }}" method="POST" class="px-6 py-4">
            @csrf
            @method('PUT')

            <!-- Course Title -->
            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Course Title *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $course->judul) }}" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('judul') border-red-500 @enderror">
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $course->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject -->
            <div class="mb-4">
                <label for="id_mapel" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                <select name="id_mapel" id="id_mapel" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_mapel') border-red-500 @enderror">
                    <option value="">Select Subject</option>
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

            <!-- Class -->
            <div class="mb-4">
                <label for="id_kelas" class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                <select name="id_kelas" id="id_kelas" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_kelas') border-red-500 @enderror">
                    <option value="">Select Class</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $course->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error('id_kelas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Teacher -->
            <div class="mb-4">
                <label for="id_guru" class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
                <select name="id_guru" id="id_guru" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_guru') border-red-500 @enderror">
                    <option value="">Select Teacher</option>
                    @foreach($guru as $g)
                        <option value="{{ $g->id_guru }}" {{ old('id_guru', $course->id_guru) == $g->id_guru ? 'selected' : '' }}>
                            {{ $g->nama }} ({{ $g->user->username }})
                        </option>
                    @endforeach
                </select>
                @error('id_guru')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Enrollment Key -->
            <div class="mb-4">
                <label for="enrollment_key" class="block text-sm font-medium text-gray-700 mb-2">Enrollment Key</label>
                <input type="text" name="enrollment_key" id="enrollment_key" value="{{ old('enrollment_key', $course->enrollment_key) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('enrollment_key') border-red-500 @enderror">
                @error('enrollment_key')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.courses.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Update Course
                </button>
            </div>
        </form>
    </div>
</div>
@endsection