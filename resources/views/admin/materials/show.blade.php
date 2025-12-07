@extends('layouts.admin')

@section('title', 'Material Details')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.materials.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Materials
        </a>
    </div>

    <!-- Material Header -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $material->nama_materi }}</h1>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $material->tahunAjaran->semester }}
                        </span>
                    </div>
                    @if($material->desk_materi)
                    <p class="mt-2 text-gray-600">{{ $material->desk_materi }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.materials.edit', $material->id_materi) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Material
                    </a>
                    @if($material->file_materi)
                    <a href="{{ Storage::url($material->file_materi) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download File
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Course Info -->
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Course</p>
                    <p class="text-sm text-gray-900 font-semibold">{{ $material->course->judul }}</p>
                </div>

                <!-- Subject -->
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Subject</p>
                    <p class="text-sm text-gray-900">{{ $material->course->mataPelajaran->nama_mapel }}</p>
                </div>

                <!-- Class -->
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Class</p>
                    <p class="text-sm text-gray-900">{{ $material->course->kelas->nama_kelas }}</p>
                </div>

                <!-- Teacher -->
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Teacher</p>
                    <p class="text-sm text-gray-900">{{ $material->course->guru->nama }}</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Upload Date -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Uploaded</p>
                        <p class="text-sm text-gray-900">{{ $material->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Last Updated -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Last Updated</p>
                        <p class="text-sm text-gray-900">{{ $material->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- File Info -->
                @if($material->file_materi)
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">File Type</p>
                        <p class="text-sm text-gray-900">{{ strtoupper(pathinfo($material->file_materi, PATHINFO_EXTENSION)) }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Assignments -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Related Assignments</h2>
        </div>

        @if($material->tugas->isEmpty())
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No assignments</h3>
            <p class="mt-1 text-sm text-gray-500">There are no assignments associated with this material yet.</p>
        </div>
        @else
        <div class="divide-y divide-gray-200">
            @foreach($material->tugas as $tugas)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $tugas->nama_tugas }}</h3>
                        @if($tugas->desk_tugas)
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($tugas->desk_tugas, 100) }}</p>
                        @endif
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ $tugas->pengumpulanTugas->count() }} submissions</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($tugas->deadline < now())
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Overdue
                        </span>
                        @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                        @endif
                        <a href="{{ route('admin.tasks.show', $tugas->id_tugas) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection