@extends('layouts.siswa')

@section('title', 'My Classes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Your Classes</h1>
        <p class="text-gray-600 mt-2">You have {{ $totalCourses }} {{ Str::plural('class', $totalCourses) }} enrolled</p>
    </div>

    <!-- Courses Grid -->
    @if($courses->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 sm:p-12 text-center">
            <svg class="w-16 h-16 sm:w-24 sm:h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Classes Yet</h3>
            <p class="text-gray-500 mb-6">You haven't enrolled in any classes yet. Use the enrollment key to join a class.</p>
            
            <!-- Enrollment Form -->
            <form action="{{ route('siswa.courses.enroll') }}" method="POST" class="max-w-md mx-auto">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="enrollment_key" placeholder="Enter enrollment key" required
                        class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Enroll
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
            <a href="{{ route('siswa.courses.show', $course->id_course) }}" class="block group">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Course Image -->
                    <div class="h-40 sm:h-48 bg-gradient-to-br from-blue-400 to-blue-600 relative overflow-hidden">
                        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800')] bg-cover bg-center opacity-30"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        
                        <!-- Course Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/90 backdrop-blur-sm text-blue-600 px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $course->kelas->nama_kelas }}
                            </span>
                        </div>

                        <!-- Course Title Overlay -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-white font-bold text-lg leading-tight line-clamp-2">{{ $course->judul }}</h3>
                        </div>
                    </div>

                    <!-- Course Info -->
                    <div class="p-5">
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium truncate">{{ $course->guru->nama }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="truncate">{{ $course->mataPelajaran->nama_mapel }}</span>
                        </div>

                        @if($course->deskripsi)
                        <p class="text-gray-600 text-sm mt-3 line-clamp-2">{{ $course->deskripsi }}</p>
                        @endif

                        <!-- View Button -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-blue-600 font-medium group-hover:text-blue-700 transition-colors">View Class</span>
                                <svg class="w-5 h-5 text-blue-600 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Enrollment Section -->
        <div class="mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Join Another Class</h3>
                    <p class="text-gray-600">Have an enrollment key? Enter it here to join a new class</p>
                </div>
                
                <form action="{{ route('siswa.courses.enroll') }}" method="POST" class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="text" name="enrollment_key" placeholder="Enter enrollment key" required
                        class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 sm:min-w-[250px]">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors whitespace-nowrap">
                        Enroll Now
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection