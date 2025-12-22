@extends('layouts.siswa')

@section('title', $teacher->nama . ' - Teacher')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('siswa.teachers.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Teachers
        </a>
    </div>

    <!-- Teacher Profile Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <!-- Avatar -->
                <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-4xl shadow-xl flex-shrink-0">
                    {{ strtoupper(substr($teacher->nama, 0, 2)) }}
                </div>
                
                <!-- Teacher Info -->
                <div class="flex-1 text-center sm:text-left text-white">
                    <h1 class="text-3xl font-bold mb-2">{{ $teacher->nama }}</h1>
                    <div class="space-y-2">
                        <p class="text-blue-100 flex items-center justify-center sm:justify-start">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            NIP: {{ $teacher->nip }}
                        </p>
                        <p class="text-blue-100 flex items-center justify-center sm:justify-start">
                        </p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="flex gap-4 sm:gap-6">
                    <div class="text-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-4">
                            <p class="text-3xl font-bold text-white">{{ $courses->count() }}</p>
                            <p class="text-blue-100 text-sm mt-1">Total Courses</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-6 py-4">
                            <p class="text-3xl font-bold text-white">{{ $courses->unique('id_mapel')->count() }}</p>
                            <p class="text-blue-100 text-sm mt-1">Subjects</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Section -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Courses</h2>
        <p class="text-gray-600">Browse all courses taught by {{ $teacher->nama }}</p>
    </div>

    @if($courses->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 sm:p-12 text-center">
            <svg class="w-16 h-16 sm:w-24 sm:h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Courses Available</h3>
            <p class="text-gray-500">This teacher doesn't have any courses yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                <!-- Course Header -->
                <div class="h-40 bg-gradient-to-br from-blue-400 to-blue-600 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800')] bg-cover bg-center opacity-30"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    
                    <!-- Enrollment Status Badge -->
                    <div class="absolute top-4 right-4">
                        @if($course->is_enrolled)
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Enrolled
                        </span>
                        @else
                        <span class="bg-white/90 backdrop-blur-sm text-blue-600 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $course->kelas->nama_kelas }}
                        </span>
                        @endif
                    </div>

                    <!-- Course Title -->
                    <div class="absolute bottom-4 left-4 right-4">
                        <h3 class="text-white font-bold text-lg leading-tight line-clamp-2">{{ $course->judul }}</h3>
                    </div>
                </div>

                <!-- Course Info -->
                <div class="p-5">
                    <div class="flex items-center text-sm text-gray-600 mb-3">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="truncate">{{ $course->mataPelajaran->nama_mapel }}</span>
                    </div>

                    <div class="flex items-center text-sm text-gray-600 mb-3">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="truncate">{{ $course->kelas->nama_kelas }}</span>
                    </div>

                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="truncate">{{ $course->siswa->count() }} students</span>
                    </div>

                    @if($course->deskripsi)
                    <p class="text-gray-600 text-sm mt-3 line-clamp-2">{{ $course->deskripsi }}</p>
                    @endif

                    <!-- Action Button -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        @if($course->is_enrolled)
                        <a href="{{ route('siswa.courses.show', $course->id_course) }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg font-medium transition-colors">
                            View Course
                        </a>
                        @else
                        <div x-data="{ showEnrollForm: false }">
                            <button @click="showEnrollForm = !showEnrollForm" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Enroll in This Course
                            </button>
                            
                            <!-- Enrollment Form -->
                            <div x-show="showEnrollForm" 
                                 x-transition
                                 class="mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <form action="{{ route('siswa.courses.enroll') }}" method="POST">
                                    @csrf
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Enter Enrollment Key
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Ask your teacher for the enrollment key to join this course.</p>
                                    <div class="flex gap-2">
                                        <input type="text" 
                                               name="enrollment_key" 
                                               placeholder="Enter enrollment key" 
                                               required
                                               class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 text-sm uppercase">
                                        <button type="submit" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors text-sm whitespace-nowrap">
                                            Enroll
                                        </button>
                                    </div>
                                    <button type="button" 
                                            @click="showEnrollForm = false"
                                            class="mt-2 text-xs text-gray-500 hover:text-gray-700">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection