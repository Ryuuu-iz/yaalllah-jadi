@extends('layouts.admin')

@section('title', 'Task Details')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.tasks.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Tasks
        </a>
    </div>

    <!-- Task Header -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->nama_tugas }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ $task->desk_tugas }}</p>
                </div>
                <div class="flex gap-2">
                    @if($task->file_tugas)
                        <a href="{{ Storage::url($task->file_tugas) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                            Download File
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Course</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $task->course->judul }} ({{ $task->course->mataPelajaran->nama_mapel }})</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Class</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $task->course->kelas->nama_kelas }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Teacher</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $task->course->guru->nama }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Deadline</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $task->deadline->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Upload Date</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $task->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Material</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $task->materi->nama_materi }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Students</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalStudents }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Submitted</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $submittedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Graded</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $gradedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Late Submissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $lateSubmissions }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">Student Submissions</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($submissions as $submission)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $submission->siswa->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $submission->siswa->nisn }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $submission->tgl_pengumpulan->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $submission->status == 'tepat_waktu' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $submission->status == 'tepat_waktu' ? 'On Time' : 'Late' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($submission->nilai)
                                <span class="font-semibold">{{ $submission->nilai }}</span>
                            @else
                                <span class="text-gray-400">Not graded</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($submission->file_pengumpulan)
                                <a href="{{ Storage::url($submission->file_pengumpulan) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                    Download
                                </a>
                            @else
                                <span class="text-gray-400">No file</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No submissions yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination for submissions -->
            @if(isset($submissions) && $submissions->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $submissions->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection