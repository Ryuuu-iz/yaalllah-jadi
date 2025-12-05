@extends('layouts.admin')

@section('title', 'Edit Academic Year')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.academic-years.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Academic Years
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Edit Academic Year: {{ $academicYear->semester }}</h1>
        </div>

        <form action="{{ route('admin.academic-years.update', $academicYear->id_TA) }}" method="POST" class="px-6 py-4">
            @csrf
            @method('PUT')

            <!-- Semester/Year -->
            <div class="mb-4">
                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester/Academic Year *</label>
                <input type="text" name="semester" id="semester" value="{{ old('semester', $academicYear->semester) }}" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('semester') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Format: YYYY/YYYY Semester (Ganjil/Genap)</p>
                @error('semester')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                    <option value="">Select Status</option>
                    <option value="aktif" {{ old('status', $academicYear->status) == 'aktif' ? 'selected' : '' }}>Active</option>
                    <option value="tidak_aktif" {{ old('status', $academicYear->status) == 'tidak_aktif' ? 'selected' : '' }}>Inactive</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">Setting to "Active" will automatically deactivate all other academic years</p>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.academic-years.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Update Academic Year
                </button>
            </div>
        </form>
    </div>
</div>
@endsection