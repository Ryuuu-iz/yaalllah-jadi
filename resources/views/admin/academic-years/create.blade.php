@extends('layouts.admin')

@section('title', 'Add New Academic Year')

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
            <h1 class="text-2xl font-bold text-gray-900">Add New Academic Year</h1>
            <p class="mt-1 text-sm text-gray-600">Create a new academic year/semester</p>
        </div>

        <form action="{{ route('admin.academic-years.store') }}" method="POST" class="px-6 py-4">
            @csrf

            <div class="space-y-6">
                <!-- Semester/Year -->
                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                        Semester/Academic Year <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="semester" id="semester" value="{{ old('semester') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('semester') border-red-500 @enderror"
                        placeholder="e.g., 2024/2025 Ganjil">
                    <p class="mt-1 text-sm text-gray-500">Format: YYYY/YYYY Semester (Ganjil/Genap)</p>
                    @error('semester')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="aktif" {{ old('status') == 'aktif' ? 'checked' : '' }} required
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <div class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">Active</span>
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Current
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Set this as the active academic year (will deactivate all others)</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'checked' : '' }} required
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <div class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">Inactive</span>
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Archived
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">This academic year will not be used for new materials</p>
                            </div>
                        </label>
                    </div>
                    @error('status')
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
                                    <li><strong>Only one academic year can be active at a time</strong></li>
                                    <li>Setting this to "Active" will automatically deactivate all other academic years</li>
                                    <li>Active academic years cannot be deleted</li>
                                    <li>Materials will be associated with the active academic year by default</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Example Academic Years -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Example Academic Years:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">2024/2025 Ganjil</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Semester 1</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Odd semester (July - December)</p>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">2024/2025 Genap</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Semester 2</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Even semester (January - June)</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Naming Guidelines</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Use format: YYYY/YYYY followed by semester name</li>
                                    <li>Common formats: "Ganjil" (odd), "Genap" (even), "Semester 1", "Semester 2"</li>
                                    <li>Be consistent with naming across years</li>
                                    <li>Names must be unique</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.academic-years.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md flex items-center transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Academic Year
                </button>
            </div>
        </form>
    </div>
</div>
@endsection