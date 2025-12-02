@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Users
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Edit User: {{ $user->username }}</h1>
        </div>

        <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST" class="px-6 py-4">
            @csrf
            @method('PUT')

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('username') border-red-500 @enderror">
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role (Display Only) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <div class="px-3 py-2 bg-gray-100 rounded-md">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $user->role == 'guru' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $user->role == 'siswa' ? 'bg-blue-100 text-blue-800' : '' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500">Role cannot be changed</p>
            </div>

            <!-- Nama (for Guru and Siswa) -->
            @if($user->role == 'guru' || $user->role == 'siswa')
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                <input type="text" name="nama" id="nama" 
                    value="{{ old('nama', $user->role == 'guru' ? $user->dataGuru->nama : $user->dataSiswa->nama) }}" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama') border-red-500 @enderror">
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <!-- NIP (for Guru) - Display Only -->
            @if($user->role == 'guru')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                <div class="px-3 py-2 bg-gray-100 rounded-md">
                    {{ $user->dataGuru->nip }}
                </div>
                <p class="mt-1 text-sm text-gray-500">NIP cannot be changed</p>
            </div>
            @endif

            <!-- NISN (for Siswa) - Display Only -->
            @if($user->role == 'siswa')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                <div class="px-3 py-2 bg-gray-100 rounded-md">
                    {{ $user->dataSiswa->nisn }}
                </div>
                <p class="mt-1 text-sm text-gray-500">NISN cannot be changed</p>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection