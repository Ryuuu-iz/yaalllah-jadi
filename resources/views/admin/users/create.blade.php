@extends('layouts.admin')

@section('title', 'Add New User')

@section('content')
<div>
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
            <h1 class="text-2xl font-bold text-gray-900">Add New User</h1>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="px-6 py-4">
            @csrf

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('username') border-red-500 @enderror">
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                <input type="password" name="password" id="password" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                <select name="role" id="role" required onchange="toggleRoleFields()"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama (for Guru and Siswa) -->
            <div class="mb-4" id="nama-field">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama') border-red-500 @enderror">
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIP (for Guru) -->
            <div class="mb-4" id="nip-field">
                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                    NIP <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nip') border-red-500 @enderror">
                @error('nip')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- NISN (for Siswa) -->
            <div class="mb-4" id="nisn-field">
                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                    NISN <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nisn') border-red-500 @enderror">
                @error('nisn')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRoleFields() {
    const role = document.getElementById('role').value;
    const namaField = document.getElementById('nama-field');
    const nipField = document.getElementById('nip-field');
    const nisnField = document.getElementById('nisn-field');
    const namaInput = document.getElementById('nama');
    const nipInput = document.getElementById('nip');
    const nisnInput = document.getElementById('nisn');
    
    // Hide all fields first
    namaField.style.display = 'none';
    nipField.style.display = 'none';
    nisnField.style.display = 'none';
    
    // Remove required attribute from all
    namaInput.removeAttribute('required');
    nipInput.removeAttribute('required');
    nisnInput.removeAttribute('required');
    
    // Show relevant fields based on role
    if (role === 'guru') {
        namaField.style.display = 'block';
        nipField.style.display = 'block';
        namaInput.setAttribute('required', 'required');
        nipInput.setAttribute('required', 'required');
    } else if (role === 'siswa') {
        namaField.style.display = 'block';
        nisnField.style.display = 'block';
        namaInput.setAttribute('required', 'required');
        nisnInput.setAttribute('required', 'required');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleRoleFields();
});

// Also trigger on role change
document.getElementById('role').addEventListener('change', toggleRoleFields);
</script>
@endsection