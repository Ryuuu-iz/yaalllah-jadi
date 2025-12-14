@extends('layouts.guru')

@section('title', $course->judul)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to My Classes
        </a>
    </div>

    <!-- Course Header -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 px-6 py-8">
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
                    @if($course->deskripsi)
                    <p class="text-blue-100 mt-3">{{ $course->deskripsi }}</p>
                    @endif
                    
                    <!-- Enrollment Key -->
                    <div class="mt-4 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3 inline-flex items-center gap-3">
                        <div>
                            <p class="text-blue-100 text-xs">Enrollment Key:</p>
                            <p class="text-white font-mono font-bold text-lg">{{ $course->enrollment_key }}</p>
                        </div>
                        <button onclick="copyEnrollmentKey('{{ $course->enrollment_key }}')" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition-colors text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        <form action="{{ route('guru.courses.regenerate-key', $course->id_course) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Generate new enrollment key? The old key will no longer work.')" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition-colors text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Course Stats -->
                <div class="flex gap-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-4 text-center">
                        <p class="text-2xl font-bold text-white">{{ $studentCount }}</p>
                        <p class="text-blue-100 text-sm">Students</p>
                    </div>
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
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="{{ route('guru.courses.edit', $course->id_course) }}" class="inline-flex items-center bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Course
                </a>
                <form action="{{ route('guru.courses.destroy', $course->id_course) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this course? All materials, assignments, and attendance records will be deleted.')" class="inline-flex items-center bg-red-500/80 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Course
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ activeTab: 'students' }" class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Tab Headers -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <button @click="activeTab = 'students'" 
                    :class="activeTab === 'students' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Students ({{ $studentCount }})
                </button>
                <button @click="activeTab = 'materials'" 
                    :class="activeTab === 'materials' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Materials ({{ $materiCount }})
                </button>
                <button @click="activeTab = 'assignments'" 
                    :class="activeTab === 'assignments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Assignments ({{ $tugasCount }})
                </button>
                <button @click="activeTab = 'attendance'" 
                    :class="activeTab === 'attendance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Attendance
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Students Tab -->
            <div x-show="activeTab === 'students'" x-transition>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Enrolled Students</h3>
                    <button @click="$refs.enrollModal.classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Enroll Student
                    </button>
                </div>

                @if($course->siswa->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Students Enrolled</h3>
                        <p class="text-gray-500">Start by enrolling students to this class.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($course->siswa as $siswa)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">
                                        {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-semibold text-gray-900 truncate">{{ $siswa->nama }}</h4>
                                        <p class="text-sm text-gray-500 truncate">{{ $siswa->nisn }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $siswa->user->username }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('guru.courses.remove-student', [$course->id_course, $siswa->id_siswa]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Remove this student from the class?')" class="text-red-600 hover:text-red-800 p-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                <!-- Enroll Modal -->
                <div x-ref="enrollModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg max-w-md w-full p-6" @click.away="$refs.enrollModal.classList.add('hidden')">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Enroll Student</h3>
                        
                        @if($availableStudents->isEmpty())
                            <p class="text-gray-500 mb-4">No available students to enroll.</p>
                        @else
                            <form action="{{ route('guru.courses.enroll', $course->id_course) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Student</label>
                                    <select name="id_siswa" required class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Choose a student...</option>
                                        @foreach($availableStudents as $student)
                                        <option value="{{ $student->id_siswa }}">{{ $student->nama }} ({{ $student->nisn }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex gap-3">
                                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        Enroll
                                    </button>
                                    <button type="button" @click="$refs.enrollModal.classList.add('hidden')" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Materials Tab -->
            <div x-show="activeTab === 'materials'" x-transition>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Course Materials</h3>
                    <a href="{{ route('guru.materials.create') }}?id_course={{ $course->id_course }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Material
                    </a>
                </div>

                @if($materials->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Materials Yet</h3>
                        <p class="text-gray-500 mb-4">Upload your first learning material for this class.</p>
                        <a href="{{ route('guru.materials.create') }}?id_course={{ $course->id_course }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add First Material
                        </a>
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
                                <div class="flex gap-2 ml-4">
                                    @if($materi->file_materi)
                                    <a href="{{ Storage::url($materi->file_materi) }}" target="_blank" class="text-blue-600 hover:text-blue-800 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    <a href="{{ route('guru.materials.edit', $materi->id_materi) }}" class="text-blue-600 hover:text-blue-800 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('guru.materials.destroy', $materi->id_materi) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this material?')" class="text-red-600 hover:text-red-800 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Assignments Tab -->
            <div x-show="activeTab === 'assignments'" x-transition>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Assignments</h3>
                    <a href="{{ route('guru.tasks.create') }}?id_course={{ $course->id_course }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Assignment
                    </a>
                </div>

                @if($assignments->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Assignments Yet</h3>
                        <p class="text-gray-500 mb-4">Create your first assignment for this class.</p>
                        <a href="{{ route('guru.tasks.create') }}?id_course={{ $course->id_course }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create First Assignment
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($assignments as $tugas)
                        @php
                            $submissionCount = $tugas->pengumpulanTugas->count();
                            $gradedCount = $tugas->pengumpulanTugas->whereNotNull('nilai')->count();
                            $isOverdue = $tugas->deadline < now();
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $tugas->nama_tugas }}</h3>
                                        @if($isOverdue)
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                Overdue
                                            </span>
                                        @else
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                Active
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
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <span>{{ $submissionCount }}/{{ $studentCount }} Submitted</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                            <span>{{ $gradedCount }}/{{ $submissionCount }} Graded</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <a href="{{ route('guru.tasks.show', $tugas->id_tugas) }}" class="text-blue-600 hover:text-blue-800 p-2" title="View Submissions">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('guru.tasks.destroy', $tugas->id_tugas) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this assignment?')" class="text-red-600 hover:text-red-800 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Attendance Tab -->
            <div x-show="activeTab === 'attendance'" x-transition>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Attendance Records</h3>
                    <a href="{{ route('guru.attendance.create') }}?id_course={{ $course->id_course }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Attendance
                    </a>
                </div>

                @if($attendances->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Attendance Records</h3>
                        <p class="text-gray-500 mb-4">Start tracking student attendance for this class.</p>
                        <a href="{{ route('guru.attendance.create') }}?id_course={{ $course->id_course }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create First Attendance
                        </a>
                    </div>
                @else
                    <!-- Attendance Summary -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $attendanceStats['hadir'] ?? 0 }}</p>
                            <p class="text-sm text-blue-800">Present</p>
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

                    <!-- Attendance List by Date -->
                    <div class="space-y-4">
                        @foreach($attendances as $date => $records)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-5 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('d') }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($date)->format('M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
                                        <p class="text-sm text-gray-500">{{ $records->count() }} records</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('guru.attendance.edit') }}?id_course={{ $course->id_course }}&tanggal={{ $date }}" class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('guru.attendance.destroy') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id_course" value="{{ $course->id_course }}">
                                        <input type="hidden" name="tanggal" value="{{ $date }}">
                                        <button type="submit" onclick="return confirm('Delete all attendance records for this date?')" class="text-red-600 hover:text-red-800 p-2" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach($records as $attendance)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-2 min-w-0 flex-1">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xs flex-shrink-0">
                                                {{ strtoupper(substr($attendance->siswa->nama, 0, 2)) }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-900 truncate">{{ $attendance->siswa->nama }}</span>
                                        </div>
                                        <span class="px-2 py-1 rounded text-xs font-semibold flex-shrink-0 ml-2
                                            {{ $attendance->status_absensi == 'hadir' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $attendance->status_absensi == 'izin' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $attendance->status_absensi == 'sakit' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $attendance->status_absensi == 'alpha' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ substr(ucfirst($attendance->status_absensi), 0, 1) }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyEnrollmentKey(key) {
    navigator.clipboard.writeText(key).then(() => {
        alert('Enrollment key copied to clipboard!');
    });
}
</script>
@endsection