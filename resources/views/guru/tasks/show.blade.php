@extends('layouts.guru')

@section('title', $tugas->nama_tugas)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('guru.tasks.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Assignments
        </a>
    </div>

    <!-- Assignment Header -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 px-6 py-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $tugas->course->kelas->nama_kelas }}
                        </span>
                        <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $tugas->course->mataPelajaran->nama_mapel }}
                        </span>
                        @if($tugas->deadline < now())
                            <span class="bg-red-500/80 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Overdue
                            </span>
                        @else
                            <span class="bg-green-500/80 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                Active
                            </span>
                        @endif
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-3">{{ $tugas->nama_tugas }}</h1>
                    <div class="flex flex-col sm:flex-row gap-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>{{ $tugas->course->judul }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Posted: {{ $tugas->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                @php
                    $submissionCount = $tugas->pengumpulanTugas->count();
                    $totalStudents = $tugas->course->siswa->count();
                    $gradedCount = $tugas->pengumpulanTugas->whereNotNull('nilai')->count();
                @endphp

                <!-- Statistics Cards -->
                <div class="flex gap-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-4 text-center">
                        <p class="text-2xl font-bold text-white">{{ $submissionCount }}/{{ $totalStudents }}</p>
                        <p class="text-white/90 text-sm">Submitted</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-4 text-center">
                        <p class="text-2xl font-bold text-white">{{ $gradedCount }}/{{ $submissionCount }}</p>
                        <p class="text-white/90 text-sm">Graded</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-6">
                @if($tugas->file_tugas)
                <a href="{{ Storage::url($tugas->file_tugas) }}" target="_blank" class="inline-flex items-center bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download File
                </a>
                @endif
                <form action="{{ route('guru.tasks.destroy', $tugas->id_tugas) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this assignment? All submissions will also be deleted.')" class="inline-flex items-center bg-red-500/80 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Assignment
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    Description
                </h2>
                @if($tugas->desk_tugas)
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $tugas->desk_tugas }}</p>
                @else
                    <p class="text-gray-500 italic">No description provided.</p>
                @endif
            </div>

            <!-- Submissions List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Student Submissions
                    </h2>
                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                        {{ $submissionCount }} of {{ $totalStudents }}
                    </span>
                </div>

                @if($tugas->pengumpulanTugas->isEmpty())
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Submissions Yet</h3>
                        <p class="text-gray-500">Waiting for students to submit their work...</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($tugas->pengumpulanTugas as $submission)
                        <div class="p-5 hover:bg-gray-50 transition-colors" x-data="{ grading: false }">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold flex-shrink-0">
                                            {{ strtoupper(substr($submission->siswa->nama, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-gray-900 truncate">{{ $submission->siswa->nama }}</h3>
                                            <p class="text-sm text-gray-500 truncate">{{ $submission->siswa->nisn }}</p>
                                        </div>
                                        @if($submission->status === 'terlambat')
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded flex-shrink-0">
                                                Late
                                            </span>
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded flex-shrink-0">
                                                On Time
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ $submission->tgl_pengumpulan->format('d M Y, H:i') }}</span>
                                        </div>
                                        @if($submission->nilai !== null)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                                </svg>
                                                <span class="font-semibold text-blue-600">Grade: {{ $submission->nilai }}/100</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($submission->keterangan)
                                    <p class="text-sm text-gray-600 mb-3 bg-gray-50 p-3 rounded-lg">
                                        <span class="font-medium">Student Note:</span> {{ $submission->keterangan }}
                                    </p>
                                    @endif

                                    @if($submission->feedback_guru)
                                    <p class="text-sm text-blue-600 bg-blue-50 p-3 rounded-lg">
                                        <span class="font-medium">Your Feedback:</span> {{ $submission->feedback_guru }}
                                    </p>
                                    @endif

                                    <!-- Grading Form -->
                                    <div x-show="grading" x-transition class="mt-4 bg-blue-50 p-4 rounded-lg">
                                        <form action="{{ route('guru.tasks.grade', $submission->id_pengumpulan) }}" method="POST">
                                            @csrf
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Grade (0-100)</label>
                                                    <input type="number" name="nilai" min="0" max="100" value="{{ $submission->nilai }}" required class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500">
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Feedback (Optional)</label>
                                                <textarea name="feedback_guru" rows="3" class="w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" placeholder="Provide feedback to the student...">{{ $submission->feedback_guru }}</textarea>
                                            </div>
                                            <div class="flex gap-2">
                                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                    Save Grade
                                                </button>
                                                <button type="button" @click="grading = false" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="flex gap-2 ml-4">
                                    @if($submission->file_pengumpulan)
                                    <a href="{{ Storage::url($submission->file_pengumpulan) }}" target="_blank" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Download">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                    <button @click="grading = !grading" class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Grade">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Assignment Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Assignment Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Course</p>
                        <p class="font-semibold text-gray-900">{{ $tugas->course->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Subject</p>
                        <p class="font-semibold text-gray-900">{{ $tugas->course->mataPelajaran->nama_mapel }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Class</p>
                        <p class="font-semibold text-gray-900">{{ $tugas->course->kelas->nama_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Related Material</p>
                        <a href="{{ route('guru.materials.show', $tugas->materi->id_materi) }}" class="font-semibold text-blue-600 hover:text-blue-800">
                            {{ $tugas->materi->nama_materi }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Posted Date</p>
                        <p class="font-semibold text-gray-900">{{ $tugas->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Deadline</p>
                        <p class="font-semibold text-gray-900 {{ $tugas->deadline < now() ? 'text-red-600' : '' }}">
                            {{ $tugas->deadline->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg shadow-md p-6 text-white">
                <h3 class="text-lg font-bold mb-4">Statistics</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-100">Total Students</span>
                        <span class="text-2xl font-bold">{{ $totalStudents }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-100">Submitted</span>
                        <span class="text-2xl font-bold">{{ $submissionCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-100">Not Submitted</span>
                        <span class="text-2xl font-bold">{{ $totalStudents - $submissionCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-100">Graded</span>
                        <span class="text-2xl font-bold">{{ $gradedCount }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-100">Pending Grade</span>
                        <span class="text-2xl font-bold">{{ $submissionCount - $gradedCount }}</span>
                    </div>
                    @if($gradedCount > 0)
                    <div class="flex items-center justify-between pt-3 border-t border-blue-400">
                        <span class="text-blue-100">Average Grade</span>
                        <span class="text-2xl font-bold">{{ number_format($tugas->pengumpulanTugas->whereNotNull('nilai')->avg('nilai'), 1) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('guru.courses.show', $tugas->course->id_course) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <span class="text-sm font-medium text-gray-700">View Course</span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('guru.materials.show', $tugas->materi->id_materi) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <span class="text-sm font-medium text-gray-700">View Material</span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('guru.tasks.create') }}?id_course={{ $tugas->course->id_course }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <span class="text-sm font-medium text-gray-700">Create Another Assignment</span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection