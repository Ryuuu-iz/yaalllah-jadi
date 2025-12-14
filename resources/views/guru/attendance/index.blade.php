@extends('layouts.guru')

@section('title', 'Attendance Records')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Attendance Records</h1>
            <p class="text-gray-600 mt-2">Manage all your attendance sessions</p>
        </div>
        <a href="{{ route('guru.attendance.create') }}" class="inline-flex items-center bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Attendance
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                <select name="id_course" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id_course }}" {{ request('id_course') == $course->id_course ? 'selected' : '' }}>
                            {{ $course->judul }} - {{ $course->kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                <select name="bulan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    <option value="">All Months</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Filter
                </button>
                <a href="{{ route('guru.attendance.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    @if(isset($stats) && $stats->isNotEmpty())
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Present</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats->get('hadir', 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Permission</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats->get('izin', 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Sick</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats->get('sakit', 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Absent</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats->get('alpha', 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Attendance List -->
    @if($absensi->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-8 sm:p-12 text-center">
            <svg class="w-16 h-16 sm:w-24 sm:h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Attendance Records</h3>
            <p class="text-gray-500 mb-6">Start by creating your first attendance session</p>
            <a href="{{ route('guru.attendance.create') }}" class="inline-flex items-center bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create First Attendance
            </a>
        </div>
    @else
        <!-- Group by Date -->
        @php
            $groupedAbsensi = $absensi->groupBy(function($item) {
                return $item->tanggal->format('Y-m-d') . '|' . $item->id_kelas . '|' . $item->id_mapel;
            });
        @endphp

        <div class="space-y-4">
            @foreach($groupedAbsensi as $key => $records)
                @php
                    $firstRecord = $records->first();
                    $date = $firstRecord->tanggal;
                    $totalRecords = $records->count();
                    $presentCount = $records->where('status_absensi', 'hadir')->count();
                    $permissionCount = $records->where('status_absensi', 'izin')->count();
                    $sickCount = $records->where('status_absensi', 'sakit')->count();
                    $absentCount = $records->where('status_absensi', 'alpha')->count();
                    $isOpen = $firstRecord->is_open ?? false;
                @endphp

                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-orange-600">{{ $date->format('d') }}</p>
                                    <p class="text-xs text-orange-500 font-medium">{{ $date->format('M Y') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $date->format('l, d F Y') }}</h3>
                                    <div class="flex items-center gap-3 mt-1">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $firstRecord->kelas->nama_kelas }}</span> - 
                                            {{ $firstRecord->mataPelajaran->nama_mapel }}
                                        </p>
                                        @if($isOpen)
                                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Open for Students
                                            </span>
                                        @else
                                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Closed
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                @php
                                    // Find course ID for this attendance session
                                    $courseId = \App\Models\Course::where('id_kelas', $firstRecord->id_kelas)
                                                                  ->where('id_mapel', $firstRecord->id_mapel)
                                                                  ->where('id_guru', $firstRecord->id_guru)
                                                                  ->value('id_course');
                                @endphp

                                <a href="{{ route('guru.attendance.edit', ['id_course' => $courseId, 'tanggal' => $date->format('Y-m-d')]) }}" 
                                   class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors" 
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                @if($isOpen)
                                <form action="{{ route('guru.attendance.toggle-status') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="tanggal" value="{{ $date->format('Y-m-d') }}">
                                    <input type="hidden" name="id_course" value="{{ $courseId }}">
                                    <button type="submit" 
                                            class="text-orange-600 hover:text-orange-800 p-2 rounded-lg hover:bg-orange-50 transition-colors" 
                                            title="Close Attendance">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('guru.attendance.toggle-status') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="tanggal" value="{{ $date->format('Y-m-d') }}">
                                    <input type="hidden" name="id_course" value="{{ $courseId }}">
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-50 transition-colors" 
                                            title="Reopen Attendance">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('guru.attendance.destroy') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="tanggal" value="{{ $date->format('Y-m-d') }}">
                                    <input type="hidden" name="id_kelas" value="{{ $firstRecord->id_kelas }}">
                                    <input type="hidden" name="id_mapel" value="{{ $firstRecord->id_mapel }}">
                                    <button type="submit" 
                                            onclick="return confirm('Delete all attendance records for this date?')"
                                            class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors" 
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Summary Stats -->
                        <div class="grid grid-cols-4 gap-3 mt-4">
                            <div class="bg-white rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $presentCount }}</p>
                                <p class="text-xs text-gray-600">Present</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $permissionCount }}</p>
                                <p class="text-xs text-gray-600">Permission</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-yellow-600">{{ $sickCount }}</p>
                                <p class="text-xs text-gray-600">Sick</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-red-600">{{ $absentCount }}</p>
                                <p class="text-xs text-gray-600">Absent</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student List -->
                    <div class="p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($records as $record)
                            <div class="flex items-center justify-between p-3 rounded-lg
                                {{ $record->status_absensi === 'hadir' ? 'bg-green-50 border border-green-200' : '' }}
                                {{ $record->status_absensi === 'izin' ? 'bg-blue-50 border border-blue-200' : '' }}
                                {{ $record->status_absensi === 'sakit' ? 'bg-yellow-50 border border-yellow-200' : '' }}
                                {{ $record->status_absensi === 'alpha' ? 'bg-red-50 border border-red-200' : '' }}">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                                        {{ $record->status_absensi === 'hadir' ? 'bg-green-200 text-green-700' : '' }}
                                        {{ $record->status_absensi === 'izin' ? 'bg-blue-200 text-blue-700' : '' }}
                                        {{ $record->status_absensi === 'sakit' ? 'bg-yellow-200 text-yellow-700' : '' }}
                                        {{ $record->status_absensi === 'alpha' ? 'bg-red-200 text-red-700' : '' }}">
                                        {{ strtoupper(substr($record->siswa->nama, 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $record->siswa->nama }}</span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded flex-shrink-0
                                    {{ $record->status_absensi === 'hadir' ? 'bg-green-200 text-green-800' : '' }}
                                    {{ $record->status_absensi === 'izin' ? 'bg-blue-200 text-blue-800' : '' }}
                                    {{ $record->status_absensi === 'sakit' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                    {{ $record->status_absensi === 'alpha' ? 'bg-red-200 text-red-800' : '' }}">
                                    {{ strtoupper(substr($record->status_absensi, 0, 1)) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $absensi->links() }}
        </div>
    @endif
</div>
@endsection