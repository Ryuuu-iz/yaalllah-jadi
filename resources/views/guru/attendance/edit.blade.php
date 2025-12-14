@extends('layouts.guru')

@section('title', 'Edit Attendance')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.attendance.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Attendance
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Attendance</h1>
        <p class="text-gray-600 mt-2">Update attendance for {{ \Carbon\Carbon::parse($validated['tanggal'])->format('l, d F Y') }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Attendance Records
                </h2>
                <div class="flex items-center gap-2">
                    <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $course->kelas->nama_kelas }}
                    </span>
                    <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $course->mataPelajaran->nama_mapel }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Current Status Info -->
        @php
            $firstRecord = $absensiData->first();
            $isOpen = $firstRecord->is_open ?? false;
            $deadline = $firstRecord->deadline ?? null;
        @endphp

        @if($isOpen)
        <div class="bg-green-50 border-l-4 border-green-400 p-4 m-6">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-green-800">Self-Attendance Mode (Open)</h3>
                    <p class="mt-1 text-sm text-green-700">
                        Students can still mark their attendance
                        @if($deadline)
                            until {{ \Carbon\Carbon::parse($deadline)->format('d M Y, H:i') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @else
        <div class="bg-gray-50 border-l-4 border-gray-400 p-4 m-6">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-gray-800">Attendance Closed</h3>
                    <p class="mt-1 text-sm text-gray-700">
                        Students cannot modify their attendance. You can still edit manually.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('guru.attendance.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="id_course" value="{{ $validated['id_course'] }}">
            <input type="hidden" name="tanggal" value="{{ $validated['tanggal'] }}">

            <!-- Attendance Settings -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                        Deadline <span class="text-gray-400">(Optional)</span>
                    </label>
                    <input type="datetime-local" 
                           name="deadline" 
                           id="deadline" 
                           value="{{ old('deadline', $deadline ? \Carbon\Carbon::parse($deadline)->format('Y-m-d\TH:i') : '') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep current deadline</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Attendance Status
                    </label>
                    <div class="flex items-center gap-4 mt-3">
                        <label class="inline-flex items-center">
                            <input type="radio" name="is_open" value="1" {{ old('is_open', $isOpen) ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Open for Students</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="is_open" value="0" {{ !old('is_open', $isOpen) ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Closed</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Student Attendance List -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Student Attendance
                </label>
                
                <div class="space-y-3 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4">
                    @foreach($course->siswa as $siswa)
                        @php
                            $currentStatus = $absensiData->get($siswa->id_siswa)?->status_absensi ?? 'alpha';
                        @endphp
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">
                                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-gray-900 truncate">{{ $siswa->nama }}</h3>
                                    <p class="text-sm text-gray-500 truncate">{{ $siswa->nisn }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="radio" 
                                           name="absensi[{{ $siswa->id_siswa }}]" 
                                           value="hadir" 
                                           class="sr-only peer" 
                                           {{ old('absensi.'.$siswa->id_siswa, $currentStatus) == 'hadir' ? 'checked' : '' }}
                                           required>
                                    <div class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg peer-checked:bg-green-100 peer-checked:border-green-500 peer-checked:text-green-700 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="radio" 
                                           name="absensi[{{ $siswa->id_siswa }}]" 
                                           value="izin" 
                                           class="sr-only peer" 
                                           {{ old('absensi.'.$siswa->id_siswa, $currentStatus) == 'izin' ? 'checked' : '' }}>
                                    <div class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg peer-checked:bg-blue-100 peer-checked:border-blue-500 peer-checked:text-blue-700 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="radio" 
                                           name="absensi[{{ $siswa->id_siswa }}]" 
                                           value="sakit" 
                                           class="sr-only peer" 
                                           {{ old('absensi.'.$siswa->id_siswa, $currentStatus) == 'sakit' ? 'checked' : '' }}>
                                    <div class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg peer-checked:bg-yellow-100 peer-checked:border-yellow-500 peer-checked:text-yellow-700 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                </label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="radio" 
                                           name="absensi[{{ $siswa->id_siswa }}]" 
                                           value="alpha" 
                                           class="sr-only peer" 
                                           {{ old('absensi.'.$siswa->id_siswa, $currentStatus) == 'alpha' ? 'checked' : '' }}>
                                    <div class="px-3 py-2 bg-white border-2 border-gray-300 rounded-lg peer-checked:bg-red-100 peer-checked:border-red-500 peer-checked:text-red-700 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Editing Tips</h3>
                        <ul class="mt-2 text-sm text-blue-700 list-disc list-inside space-y-1">
                            <li>All changes will be saved for all students at once</li>
                            <li>Closing attendance will prevent students from marking their own attendance</li>
                            <li>You can reopen attendance later if needed</li>
                            <li>Icons: ✓ Present | 📋 Permission | ❤️ Sick | ✕ Absent</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="flex-1 sm:flex-initial bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors shadow-md flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('guru.attendance.index') }}" class="flex-1 sm:flex-initial bg-gray-300 hover:bg-gray-400 text-gray-700 px-8 py-3 rounded-lg font-medium transition-colors text-center">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Delete Form (Outside main form) -->
        <form action="{{ route('guru.attendance.destroy') }}" method="POST" class="px-6 pb-6">
            @csrf
            @method('DELETE')
            <input type="hidden" name="tanggal" value="{{ $validated['tanggal'] }}">
            <input type="hidden" name="id_kelas" value="{{ $course->id_kelas }}">
            <input type="hidden" name="id_mapel" value="{{ $course->id_mapel }}">
            <button type="submit" 
                    onclick="return confirm('Delete all attendance records for this date? This action cannot be undone.')"
                    class="w-full bg-red-500 hover:bg-red-600 text-white px-8 py-3 rounded-lg font-medium transition-colors shadow-md flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Attendance
            </button>
        </form>
    </div>

    <!-- Summary Card -->
    @php
        $presentCount = $absensiData->where('status_absensi', 'hadir')->count();
        $permissionCount = $absensiData->where('status_absensi', 'izin')->count();
        $sickCount = $absensiData->where('status_absensi', 'sakit')->count();
        $absentCount = $absensiData->where('status_absensi', 'alpha')->count();
        $totalStudents = $course->siswa->count();
    @endphp

    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Current Summary</h3>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</p>
                <p class="text-sm text-gray-600 mt-1">Total</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-3xl font-bold text-green-600">{{ $presentCount }}</p>
                <p class="text-sm text-green-800 mt-1">Present</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-3xl font-bold text-blue-600">{{ $permissionCount }}</p>
                <p class="text-sm text-blue-800 mt-1">Permission</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-3xl font-bold text-yellow-600">{{ $sickCount }}</p>
                <p class="text-sm text-yellow-800 mt-1">Sick</p>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <p class="text-3xl font-bold text-red-600">{{ $absentCount }}</p>
                <p class="text-sm text-red-800 mt-1">Absent</p>
            </div>
        </div>
    </div>
</div>
@endsection