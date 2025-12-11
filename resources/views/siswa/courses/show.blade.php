@extends('layouts.siswa')

@section('title', $course->judul)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to My Classes
        </a>
    </div>

    <!-- Course Header -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $course->kelas->nama_kelas }}
                        </span>
                        <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $course->mataPelajaran->nama_mapel }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-3">{{ $course->judul }}</h1>
                    <div class="flex flex-col sm:flex-row gap-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ $course->guru->nama }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span>{{ $course->siswa->count() }} Students</span>
                        </div>
                    </div>
                    @if($course->deskripsi)
                    <p class="text-blue-100 mt-3">{{ $course->deskripsi }}</p>
                    @endif
                </div>

                <!-- Course Stats -->
                <div class="flex gap-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-4 text-center">
                        <p class="text-2xl font-bold text-white">{{ $materiCount }}</p>
                        <p class="text-blue-100 text-sm">Materials</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-4 text-center">
                        <p class="text-2xl font-bold text-white">{{ $tugasCount }}</p>
                        <p class="text-blue-100 text-sm">Assignments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ activeTab: 'materi' }" class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Tab Headers -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="activeTab = 'materi'" 
                    :class="activeTab === 'materi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Materials
                </button>
                <button @click="activeTab = 'tugas'" 
                    :class="activeTab === 'tugas' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Assignments
                </button>
                <button @click="activeTab = 'absensi'" 
                    :class="activeTab === 'absensi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Attendance
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Materials Tab -->
            <div x-show="activeTab === 'materi'" x-transition>
                @if($materials->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Materials Yet</h3>
                        <p class="text-gray-500">The teacher hasn't uploaded any materials for this course.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($materials as $materi)
                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $materi->nama_materi }}</h3>
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                            {{ $materi->tahunAjaran->semester }}
                                        </span>
                                    </div>
                                    @if($materi->desk_materi)
                                    <p class="text-gray-600 text-sm mb-3">{{ $materi->desk_materi }}</p>
                                    @endif
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Posted on {{ $materi->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                                @if($materi->file_materi)
                                <a href="{{ Storage::url($materi->file_materi) }}" target="_blank" 
                                    class="flex-shrink-0 ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Assignments Tab -->
            <div x-show="activeTab === 'tugas'" x-transition>
                @if($assignments->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Assignments Yet</h3>
                        <p class="text-gray-500">The teacher hasn't created any assignments for this course.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($assignments as $tugas)
                        @php
                            $submission = $tugas->pengumpulanTugas->first();
                            $isOverdue = $tugas->deadline < now();
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $tugas->nama_tugas }}</h3>
                                        @if($submission)
                                            @if($submission->nilai)
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                    Graded: {{ $submission->nilai }}
                                                </span>
                                            @else
                                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                    Submitted
                                                </span>
                                            @endif
                                        @elseif($isOverdue)
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                Overdue
                                            </span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                    @if($tugas->desk_tugas)
                                    <p class="text-gray-600 text-sm mb-3">{{ $tugas->desk_tugas }}</p>
                                    @endif
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}</span>
                                        </div>
                                        @if($tugas->file_tugas)
                                        <a href="{{ Storage::url($tugas->file_tugas) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            <span>Download Task File</span>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($submission)
                                <!-- Submission Details -->
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Your Submission</h4>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Submitted on:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $submission->tgl_pengumpulan->format('d M Y, H:i') }}</span>
                                        </div>
                                        @if($submission->file_pengumpulan)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Your file:</span>
                                            <a href="{{ Storage::url($submission->file_pengumpulan) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800">
                                                Download
                                            </a>
                                        </div>
                                        @endif
                                        @if($submission->keterangan)
                                        <div>
                                            <span class="text-sm text-gray-600 block mb-1">Note:</span>
                                            <p class="text-sm text-gray-900">{{ $submission->keterangan }}</p>
                                        </div>
                                        @endif
                                        @if($submission->nilai)
                                        <div class="border-t border-gray-200 pt-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-semibold text-gray-900">Grade:</span>
                                                <span class="text-2xl font-bold text-green-600">{{ $submission->nilai }}</span>
                                            </div>
                                            @if($submission->feedback_guru)
                                            <div class="bg-white rounded p-3 mt-2">
                                                <span class="text-sm text-gray-600 block mb-1">Teacher's Feedback:</span>
                                                <p class="text-sm text-gray-900">{{ $submission->feedback_guru }}</p>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Submit Form -->
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <button @click="$refs.submitForm{{ $tugas->id_tugas }}.classList.toggle('hidden')" 
                                        class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Submit Assignment
                                    </button>
                                    
                                    <form x-ref="submitForm{{ $tugas->id_tugas }}" action="{{ route('siswa.tugas.submit', $tugas->id_tugas) }}" method="POST" enctype="multipart/form-data" class="hidden mt-4 bg-gray-50 rounded-lg p-4">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File (Optional)</label>
                                                <input type="file" name="file_pengumpulan" 
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                <p class="mt-1 text-xs text-gray-500">Max 10MB (PDF, DOC, DOCX, ZIP, RAR)</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Note (Optional)</label>
                                                <textarea name="keterangan" rows="3" 
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    placeholder="Add any notes for your teacher..."></textarea>
                                            </div>
                                            <div class="flex gap-3">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                                    Submit
                                                </button>
                                                <button type="button" @click="$refs.submitForm{{ $tugas->id_tugas }}.classList.add('hidden')" 
                                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-medium transition-colors">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Attendance Tab -->
            <div x-show="activeTab === 'absensi'" x-transition>
                @if($attendances->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Attendance Records</h3>
                        <p class="text-gray-500">There are no attendance records for this course yet.</p>
                    </div>
                @else
                    <!-- Attendance Summary -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $attendanceStats['hadir'] ?? 0 }}</p>
                            <p class="text-sm text-green-800">Present</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $attendanceStats['izin'] ?? 0 }}</p>
                            <p class="text-sm text-blue-800">Permission</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-yellow-600">{{ $attendanceStats['sakit'] ?? 0 }}</p>
                            <p class="text-sm text-yellow-800">Sick</p>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-red-600">{{ $attendanceStats['alpha'] ?? 0 }}</p>
                            <p class="text-sm text-red-800">Absent</p>
                        </div>
                    </div>

                    <!-- Attendance List -->
                    <div class="space-y-3">
                        @foreach($attendances as $attendance)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">{{ $attendance->tanggal->format('d') }}</p>
                                    <p class="text-xs text-gray-500">{{ $attendance->tanggal->format('M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $attendance->tanggal->format('l') }}</p>
                                    <p class="text-xs text-gray-500">{{ $attendance->mataPelajaran->nama_mapel }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $attendance->status_absensi == 'hadir' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $attendance->status_absensi == 'izin' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $attendance->status_absensi == 'sakit' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $attendance->status_absensi == 'alpha' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($attendance->status_absensi) }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Tambahan: Quick self-attendance box jika ada sesi terbuka -->
                    @if($attendances->isNotEmpty())
                        @php
                            $openAttendance = $attendances->where('is_open', true)
                                                         ->where('status_absensi', 'alpha')
                                                         ->where('deadline', '>=', now())
                                                         ->first();
                        @endphp

                        @if($openAttendance)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-yellow-800">Absensi Terbuka!</h3>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        Deadline: <strong>{{ $openAttendance->deadline->format('d M Y, H:i') }}</strong>
                                        ({{ $openAttendance->deadline->diffForHumans() }})
                                    </p>

                                    <!-- Quick Attend Button -->
                                    <form action="{{ route('siswa.absensi.submit') }}" method="POST" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="id_absensi" value="{{ $openAttendance->id_absensi }}">
                                        <button type="submit" 
                                                onclick="return confirm('Konfirmasi kehadiran Anda?')"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                            ✓ Saya Hadir
                                        </button>
                                    </form>

                                    <!-- Permission/Sick Request -->
                                    <button @click="$refs.permissionForm{{ $openAttendance->id_absensi }}.classList.toggle('hidden')" 
                                            class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Ajukan Izin/Sakit
                                    </button>

                                    <form x-ref="permissionForm{{ $openAttendance->id_absensi }}" 
                                          action="{{ route('siswa.absensi.request-permission') }}" 
                                          method="POST" 
                                          class="hidden mt-4 bg-white rounded-lg p-4 border border-gray-200">
                                        @csrf
                                        <input type="hidden" name="id_absensi" value="{{ $openAttendance->id_absensi }}">

                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan</label>
                                                <select name="status" required class="w-full border-gray-300 rounded-lg">
                                                    <option value="">Pilih alasan</option>
                                                    <option value="izin">Izin</option>
                                                    <option value="sakit">Sakit</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan *</label>
                                                <textarea name="keterangan" rows="3" required
                                                          class="w-full border-gray-300 rounded-lg"
                                                          placeholder="Tuliskan alasan Anda..."></textarea>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                                    Kirim
                                                </button>
                                                <button type="button" 
                                                        @click="$refs.permissionForm{{ $openAttendance->id_absensi }}.classList.add('hidden')"
                                                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm">
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection