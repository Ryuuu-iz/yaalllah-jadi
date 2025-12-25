@extends('layouts.' . auth()->user()->role)

@section('title', 'Complete Profile')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Success/Error Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- Profile Completion Card -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Profile</h2>
        
        <p class="text-gray-600 mb-8">
            To access all features of the system, please complete your profile information below.
        </p>

        <form action="{{ route('profile.complete.submit') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Name Field -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                        placeholder="Enter your full name">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NISN/NIP Field based on role -->
                @if(auth()->user()->role === 'siswa')
                <div>
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                        NISN (Student ID Number)
                    </label>
                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nisn') border-red-500 @enderror"
                        placeholder="Enter your NISN (optional)">
                    @error('nisn')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @elseif(auth()->user()->role === 'guru')
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                        NIP (Teacher ID Number)
                    </label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nip') border-red-500 @enderror"
                        placeholder="Enter your NIP (optional)">
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Role Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Important</h3>
                            <p class="text-sm text-blue-700 mt-1">
                                You are logged in as a <strong>{{ ucfirst(auth()->user()->role) }}</strong>. 
                                Please provide the required information to complete your profile.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Complete Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection