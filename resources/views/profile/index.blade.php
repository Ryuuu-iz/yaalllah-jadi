@extends('layouts.' . auth()->user()->role)

@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            My Profile
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-md p-20">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-20">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="w-40 h-40 rounded-full bg-gray-400 flex items-center justify-center overflow-hidden">
                    <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-1 w-full">
                <div class="space-y-4">
                    <!-- Name -->
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-gray-700 font-medium w-28">Name</span>
                        <span class="text-gray-500 mx-4 hidden sm:inline">:</span>
                        <span class="text-gray-900 font-semibold">
                            @if(auth()->user()->role === 'siswa' && auth()->user()->dataSiswa)
                                {{ auth()->user()->dataSiswa->nama }}
                            @elseif(auth()->user()->role === 'guru' && auth()->user()->dataGuru)
                                {{ auth()->user()->dataGuru->nama }}
                            @else
                                {{ auth()->user()->username }}
                            @endif
                        </span>
                    </div>

                    <!-- NISN/NIP -->
                    @if(auth()->user()->role === 'siswa' && auth()->user()->dataSiswa)
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-gray-700 font-medium w-28">NISN</span>
                        <span class="text-gray-500 mx-4 hidden sm:inline">:</span>
                        <span class="text-gray-900">{{ auth()->user()->dataSiswa->nisn }}</span>
                    </div>
                    @endif

                    @if(auth()->user()->role === 'guru' && auth()->user()->dataGuru)
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-gray-700 font-medium w-28">NIP</span>
                        <span class="text-gray-500 mx-4 hidden sm:inline">:</span>
                        <span class="text-gray-900">{{ auth()->user()->dataGuru->nip }}</span>
                    </div>
                    @endif

                    <!-- Role -->
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-gray-700 font-medium w-28">Role</span>
                        <span class="text-gray-500 mx-4 hidden sm:inline">:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            {{ auth()->user()->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ auth()->user()->role === 'guru' ? 'bg-green-100 text-green-800' : '' }}
                            {{ auth()->user()->role === 'siswa' ? 'bg-cyan-100 text-cyan-800' : '' }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>

                <!-- Change Password Button -->
                <div class="mt-8">
                    <button type="button" onclick="document.getElementById('passwordModal').classList.remove('hidden')" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors">
                        Change Password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Modal -->
<div id="passwordModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Change Password</h3>
            <button type="button" onclick="document.getElementById('passwordModal').classList.add('hidden')" 
                class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route(auth()->user()->role . '.profile.update-password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Current Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('current_password') border-red-500 @enderror"
                        placeholder="Enter your current password">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="Enter your new password">
                    <p class="mt-1 text-xs text-gray-500">Password must be at least 6 characters long</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Confirm your new password">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('passwordModal').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script to show modal if there are errors -->
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('passwordModal').classList.remove('hidden');
    });
</script>
@endif
@endsection