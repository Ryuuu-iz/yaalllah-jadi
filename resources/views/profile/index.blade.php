@extends('layouts.' . auth()->user()->role)

@section('title', 'My Profile')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">My Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Summary Card -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col items-center">
                    <!-- Avatar -->
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-4xl font-bold mb-4">
                        {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
                    </div>
                    
                    <!-- User Info -->
                    <h2 class="text-xl font-bold text-gray-900 text-center">
                        @if(auth()->user()->role === 'siswa' && auth()->user()->dataSiswa)
                            {{ auth()->user()->dataSiswa->nama }}
                        @elseif(auth()->user()->role === 'guru' && auth()->user()->dataGuru)
                            {{ auth()->user()->dataGuru->nama }}
                        @else
                            {{ auth()->user()->username }}
                        @endif
                    </h2>
                    
                    <p class="text-sm text-gray-500 mb-4">{{ '@' . auth()->user()->username }}</p>
                    
                    <!-- Role Badge -->
                    <span class="px-4 py-2 text-sm font-semibold rounded-full
                        {{ auth()->user()->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ auth()->user()->role === 'guru' ? 'bg-green-100 text-green-800' : '' }}
                        {{ auth()->user()->role === 'siswa' ? 'bg-blue-100 text-blue-800' : '' }}">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                    
                    <!-- Additional Info -->
                    <div class="mt-6 w-full space-y-3">
                        @if(auth()->user()->role === 'siswa' && auth()->user()->dataSiswa)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-500">NISN</span>
                            <span class="text-sm font-medium text-gray-900">{{ auth()->user()->dataSiswa->nisn }}</span>
                        </div>
                        @endif
                        
                        @if(auth()->user()->role === 'guru' && auth()->user()->dataGuru)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-500">NIP</span>
                            <span class="text-sm font-medium text-gray-900">{{ auth()->user()->dataGuru->nip }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-sm text-gray-500">Member Since</span>
                            <span class="text-sm font-medium text-gray-900">{{ auth()->user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Edit Profile Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Profile Information</h2>
                    <p class="mt-1 text-sm text-gray-600">Update your account's profile information</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="px-6 py-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($user->role === 'siswa' && $user->dataSiswa)
                        <!-- Nama Siswa -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $user->dataSiswa->nama) }}" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NISN -->
                        <div>
                            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                                NISN <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $user->dataSiswa->nisn) }}" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nisn') border-red-500 @enderror">
                            @error('nisn')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        @if($user->role === 'guru' && $user->dataGuru)
                        <!-- Nama Guru -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $user->dataGuru->nama) }}" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                                NIP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip', $user->dataGuru->nip) }}" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nip') border-red-500 @enderror">
                            @error('nip')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md flex items-center transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Change Password</h2>
                    <p class="mt-1 text-sm text-gray-600">Ensure your account is using a secure password</p>
                </div>

                <form action="{{ route('profile.update-password') }}" method="POST" class="px-6 py-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Current Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="current_password" id="current_password" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
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
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Minimum 6 characters</p>
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
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md flex items-center transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection