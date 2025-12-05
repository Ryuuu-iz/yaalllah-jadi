@extends('layouts.admin')

@section('title', 'Edit Class')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.classes.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Classes
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Edit Class: {{ $class->nama_kelas }}</h1>
        </div>

        <form action="{{ route('admin.classes.update', $class->id_kelas) }}" method="POST" class="px-6 py-4">
            @csrf
            @method('PUT')

            <!-- Class Name -->
            <div class="mb-4">
                <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">Class Name *</label>
                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $class->nama_kelas) }}" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_kelas') border-red-500 @enderror">
                @error('nama_kelas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grade Level -->
            <div class="mb-4">
                <label for="tingkatan" class="block text-sm font-medium text-gray-700 mb-2">Grade Level *</label>
                <select name="tingkatan" id="tingkatan" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tingkatan') border-red-500 @enderror">
                    <option value="">Select Grade Level</option>
                    <option value="10" {{ old('tingkatan', $class->tingkatan) == '10' ? 'selected' : '' }}>Tingkatan 10</option>
                    <option value="11" {{ old('tingkatan', $class->tingkatan) == '11' ? 'selected' : '' }}>Tingkatan 11</option>
                    <option value="12" {{ old('tingkatan', $class->tingkatan) == '12' ? 'selected' : '' }}>Tingkatan 12</option>
                </select>
                @error('tingkatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.classes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Update Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection