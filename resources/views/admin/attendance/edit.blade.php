@extends('layouts.admin')

@section('title', 'Edit Attendance')

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
            <h1 class="text-2xl font-bold text-gray-900">Edit Attendance</h1>
        </div>

        <!-- Course Selection Form -->
        @if(!isset($course))
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.attendance.edit') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="id_course" class="block text-sm font-medium text-gray-700 mb-2">Select Course *</label>
                        <select name="id_course" id="id_course" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select Course --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id_course }}">
                                    {{ $c->judul }} - {{ $c->mataPelajaran->nama_mapel }} - {{ $c->kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="tanggal" id="tanggal" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Load Attendance
                </button>
            </form>
        </div>
        @else
        <!-- Attendance Edit Form -->
        <form action="{{ route('admin.attendance.update') }}" method="POST" class="px-6 py-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_course" value="{{ $course->id_course }}">
            <input type="hidden" name="tanggal" value="{{ $validated['tanggal'] }}">

            <!-- Course Info -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Course</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Subject</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->mataPelajaran->nama_mapel }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Class</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->kelas->nama_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date</p>
                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($validated['tanggal'])->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            @if($course->siswa->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-sm text-yellow-700">No students enrolled in this course yet.</p>
                </div>
            @else
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Students Attendance ({{ $course->siswa->count() }} students)</h3>
                    
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
                                @foreach($course->siswa as $index => $siswa)
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
                                            class="attendance-select border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">-- Select --</option>
                                            <option value="hadir" {{ (isset($absensiData[$siswa->id_siswa]) && $absensiData[$siswa->id_siswa]->status_absensi == 'hadir') ? 'selected' : '' }}>Present</option>
                                            <option value="izin" {{ (isset($absensiData[$siswa->id_siswa]) && $absensiData[$siswa->id_siswa]->status_absensi == 'izin') ? 'selected' : '' }}>Permission</option>
                                            <option value="sakit" {{ (isset($absensiData[$siswa->id_siswa]) && $absensiData[$siswa->id_siswa]->status_absensi == 'sakit') ? 'selected' : '' }}>Sick</option>
                                            <option value="alpha" {{ (isset($absensiData[$siswa->id_siswa]) && $absensiData[$siswa->id_siswa]->status_absensi == 'alpha') ? 'selected' : '' }}>Absent</option>
                                        </select>
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
                        Update Attendance
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