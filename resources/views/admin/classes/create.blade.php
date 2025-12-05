
@extends('layouts.admin')

@section('title', 'Add New Class')

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
            <h1 class="text-2xl font-bold text-gray-900">Add New Class</h1>
            <p class="mt-1 text-sm text-gray-600">Create a new class for students</p>
        </div>

        <form action="{{ route('admin.classes.store') }}" method="POST" class="px-6 py-4">
            @csrf

            <div class="space-y-6">
                <!-- Class Name -->
                <div>
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                        Class Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_kelas') border-red-500 @enderror"
                        placeholder="e.g., 10 IPA 1, 11 IPS 2, 12 Bahasa 1">
                    <p class="mt-1 text-sm text-gray-500">Format: [Grade] [Program] [Number] (e.g., 10 IPA 1)</p>
                    @error('nama_kelas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grade Level -->
                <div>
                    <label for="tingkatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Grade Level <span class="text-red-500">*</span>
                    </label>
                    <select name="tingkatan" id="tingkatan" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tingkatan') border-red-500 @enderror">
                        <option value="">-- Select Grade Level --</option>
                        <option value="10" {{ old('tingkatan') == '10' ? 'selected' : '' }}>Grade 10 (X)</option>
                        <option value="11" {{ old('tingkatan') == '11' ? 'selected' : '' }}>Grade 11 (XI)</option>
                        <option value="12" {{ old('tingkatan') == '12' ? 'selected' : '' }}>Grade 12 (XII)</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Select the grade level for this class</p>
                    @error('tingkatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Class Naming Guidelines</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Use clear and consistent naming (e.g., 10 IPA 1, 11 IPS 2)</li>
                                    <li>Include grade level, program, and class number</li>
                                    <li>Class names must be unique</li>
                                    <li>Avoid special characters</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Example Classes -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Example Class Names:</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <div class="bg-white border border-gray-200 rounded px-3 py-2 text-sm text-gray-700">10 IPA 1</div>
                        <div class="bg-white border border-gray-200 rounded px-3 py-2 text-sm text-gray-700">11 IPS 2</div>
                        <div class="bg-white border border-gray-200 rounded px-3 py-2 text-sm text-gray-700">12 Bahasa 1</div>
                        <div class="bg-white border border-gray-200 rounded px-3 py-2 text-sm text-gray-700">10 MIPA 3</div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.classes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md flex items-center transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection