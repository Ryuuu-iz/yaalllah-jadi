@extends('layouts.admin')

@section('title', 'Add Attendance')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <a href="{{ route('admin.attendance.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Attendance
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Add Attendance</h1>
        </div>

        <!-- Course Selection Form -->
        @if(!$selectedCourse)
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.attendance.create') }}">
                <div class="mb-4">
                    <label for="id_course" class="block text-sm font-medium text-gray-700 mb-2">Select Course *</label>
                    <select name="id_course" id="id_course" required onchange="this.form.submit()"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id_course }}">
                                {{ $course->judul }} - {{ $course->mataPelajaran->nama_mapel }} - {{ $course->kelas->nama_kelas }} ({{ $course->guru->nama }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        @else
        <!-- Attendance Form -->
        <form action="{{ route('admin.attendance.store') }}" method="POST" class="px-6 py-4">
            @csrf
            <input type="hidden" name="id_course" value="{{ $selectedCourse->id_course }}">

            <!-- Course Info -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Course</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedCourse->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Subject - Class</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedCourse->mataPelajaran->nama_mapel }} - {{ $selectedCourse->kelas->nama_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Teacher</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedCourse->guru->nama }}</p>
                    </div>
                </div>
            </div>

            <!-- Date -->
            <div class="mb-6">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                    class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror">
                @error('tanggal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Students List -->
            @if($siswaList->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-sm text-yellow-700">No students enrolled in this course yet.</p>
                </div>
            @else
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Students Attendance ({{ $siswaList->count() }} students)</h3>
                    
                    <!-- Quick Actions -->
                    <div class="mb-4 flex gap-2">
                        <button type="button" onclick="setAllAttendance('hadir')" class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1 rounded text-sm">
                            Mark All Present
                        </button>
                        <button type="button" onclick="setAllAttendance('alpha')" class="bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1 rounded text-sm">
                            Mark All Absent
                        </button>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($siswaList as $index => $siswa)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $siswa->nama }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $siswa->nisn }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select name="absensi[{{ $siswa->id_siswa }}]" required
                                            class="attendance-select border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('absensi.' . $siswa->id_siswa) border-red-500 @enderror">
                                            <option value="">-- Select --</option>
                                            <option value="hadir" {{ old('absensi.' . $siswa->id_siswa) == 'hadir' ? 'selected' : '' }}>Present</option>
                                            <option value="izin" {{ old('absensi.' . $siswa->id_siswa) == 'izin' ? 'selected' : '' }}>Permission</option>
                                            <option value="sakit" {{ old('absensi.' . $siswa->id_siswa) == 'sakit' ? 'selected' : '' }}>Sick</option>
                                            <option value="alpha" {{ old('absensi.' . $siswa->id_siswa) == 'alpha' ? 'selected' : '' }}>Absent</option>
                                        </select>
                                        @error('absensi.' . $siswa->id_siswa)
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('admin.attendance.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Save Attendance
                    </button>
                </div>
            @endif
        </form>
        @endif
    </div>
</div>

<script>
function setAllAttendance(status) {
    const selects = document.querySelectorAll('.attendance-select');
    selects.forEach(select => {
        select.value = status;
    });
}
</script>
@endsection