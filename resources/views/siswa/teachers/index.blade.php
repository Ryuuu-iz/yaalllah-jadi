@extends('layouts.siswa')

@section('title', 'Teachers')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Teachers</h1>
        <p class="text-gray-600 mt-2">Browse all teachers and their courses</p>
    </div>

    <!-- Teachers Grid -->
    @if($teachers->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 sm:p-12 text-center">
            <svg class="w-16 h-16 sm:w-24 sm:h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Teachers Found</h3>
            <p class="text-gray-500">There are no teachers registered in the system yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($teachers as $teacher)
            <a href="{{ route('siswa.teachers.show', $teacher->id_guru) }}" class="block group">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Teacher Avatar -->
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-8 text-center">
                        <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-3xl mb-3 shadow-lg">
                            {{ strtoupper(substr($teacher->nama, 0, 2)) }}
                        </div>
                        <h3 class="text-white font-bold text-lg">{{ $teacher->nama }}</h3>
                        <p class="text-blue-100 text-sm mt-1">NIP: {{ $teacher->nip }}</p>
                    </div>

                    <!-- Teacher Info -->
                    <div class="p-5">
                        <!-- Course Count -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="font-medium">{{ $teacher->courses_count }} {{ Str::plural('Course', $teacher->courses_count) }}</span>
                            </div>
                            
                            @if($teacher->courses_count > 0)
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                Active
                            </span>
                            @endif
                        </div>

                        <!-- Subjects Preview -->
                        @if($teacher->courses->isNotEmpty())
                        <div class="mb-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">Teaches:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($teacher->courses->unique('id_mapel')->take(3) as $course)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $course->mataPelajaran->nama_mapel }}
                                </span>
                                @endforeach
                                @if($teacher->courses->unique('id_mapel')->count() > 3)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    +{{ $teacher->courses->unique('id_mapel')->count() - 3 }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- View Button -->
                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-blue-600 group-hover:text-blue-700 transition-colors">
                                <span class="font-medium text-sm">View Courses</span>
                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection