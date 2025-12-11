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
            <p class="mt-1 text-sm text-gray-600">Create attendance session for students</p>
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
        <form id="attendanceForm" action="{{ route('admin.attendance.store') }}" method="POST" class="px-6 py-4">
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

            <!-- Date and Deadline -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror">
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">Deadline *</label>
                    <input type="datetime-local" name="deadline" id="deadline" value="{{ old('deadline') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deadline') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Deadline untuk siswa melakukan absensi</p>
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Mode Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Attendance Mode *</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all border-blue-500 bg-blue-50">
                        <input type="radio" name="mode" value="self" checked required onchange="toggleMode()"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 mt-1">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Self Attendance</span>
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Siswa melakukan absensi mandiri melalui course mereka sampai deadline
                            </p>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all border-gray-200 hover:border-gray-300">
                        <input type="radio" name="mode" value="manual" required onchange="toggleMode()"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 mt-1">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Manual Entry</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Admin/Guru input absensi langsung untuk semua siswa
                            </p>
                        </div>
                    </label>
                </div>
                @error('mode')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if($siswaList->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-sm text-yellow-700">No students enrolled in this course yet.</p>
                </div>
            @else
                <!-- Self Mode Info -->
                <div id="selfModeInfo" class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Self Attendance Mode</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Semua siswa akan mendapat status "Alpha" secara default</li>
                                    <li>Siswa dapat mengubah status mereka menjadi "Hadir" melalui course</li>
                                    <li>Siswa dapat mengajukan "Izin" atau "Sakit" dengan keterangan</li>
                                    <li>Absensi akan otomatis tertutup setelah deadline</li>
                                    <li>{{ $siswaList->count() }} siswa akan dibuat record absensi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manual Mode Table -->
                <div id="manualModeTable" class="mb-6" style="display: none;">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $siswa->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $siswa->nisn }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select name="absensi[{{ $siswa->id_siswa }}]" 
                                            class="attendance-select border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                            disabled>
                                            <option value="">-- Select --</option>
                                            <option value="hadir">Present</option>
                                            <option value="izin">Permission</option>
                                            <option value="sakit">Sick</option>
                                            <option value="alpha">Absent</option>
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
                        Create Attendance
                    </button>
                </div>
            @endif
        </form>
        @endif
    </div>
</div>

<script>
function toggleMode() {
    const mode = document.querySelector('input[name="mode"]:checked').value;
    const selfInfo = document.getElementById('selfModeInfo');
    const manualTable = document.getElementById('manualModeTable');
    const selects = document.querySelectorAll('.attendance-select');
    
    if (mode === 'self') {
        selfInfo.style.display = 'block';
        manualTable.style.display = 'none';
        // Disable and clear all selects
        selects.forEach(select => {
            select.disabled = true;
            select.removeAttribute('required');
            select.value = '';
        });
    } else {
        selfInfo.style.display = 'none';
        manualTable.style.display = 'block';
        // Enable all selects and make required
        selects.forEach(select => {
            select.disabled = false;
            select.setAttribute('required', 'required');
        });
    }
}

function setAllAttendance(status) {
    const selects = document.querySelectorAll('.attendance-select');
    selects.forEach(select => {
        if (!select.disabled) {
            select.value = status;
        }
    });
}

// Auto set default deadline (tomorrow at 23:59)
document.getElementById('tanggal').addEventListener('change', function() {
    const deadlineInput = document.getElementById('deadline');
    if (!deadlineInput.value) {
        const selectedDate = new Date(this.value);
        selectedDate.setDate(selectedDate.getDate() + 1);
        selectedDate.setHours(23, 59);
        
        const year = selectedDate.getFullYear();
        const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
        const day = String(selectedDate.getDate()).padStart(2, '0');
        const hours = String(selectedDate.getHours()).padStart(2, '0');
        const minutes = String(selectedDate.getMinutes()).padStart(2, '0');
        
        deadlineInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleMode();
});
</script>
@endsection