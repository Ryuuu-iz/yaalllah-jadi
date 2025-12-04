@extends('layouts.admin')

@section('title', 'Edit Subject')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.subjects.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Subjects
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Edit Subject: {{ $subject->nama_mapel }}</h1>
        </div>

        <form action="{{ route('admin.subjects.update', $subject->id_mapel) }}" method="POST" class="px-6 py-4">
            @csrf
            @method('PUT')

            <!-- Subject Name -->
            <div class="mb-4">
                <label for="nama_mapel" class="block text-sm font-medium text-gray-700 mb-2">Subject Name *</label>
                <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel', $subject->nama_mapel) }}" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_mapel') border-red-500 @enderror">
                @error('nama_mapel')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.subjects.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Update Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection