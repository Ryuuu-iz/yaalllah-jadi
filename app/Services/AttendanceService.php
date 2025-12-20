<?php

namespace App\Services;

use App\Models\RekapAbsensi;
use App\Models\Course;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AttendanceService
{
    public function getAttendanceWithFilters(Request $request)
    {
        $query = RekapAbsensi::with(['siswa', 'kelas', 'guru', 'mataPelajaran', 'course']);

        // Filters
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('id_mapel')) {
            $query->where('id_mapel', $request->id_mapel);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        return $query->orderBy('tanggal', 'desc')->paginate(20);
    }

    public function getAttendanceStats()
    {
        return RekapAbsensi::selectRaw('status_absensi, COUNT(*) as total')
                           ->groupBy('status_absensi')
                           ->pluck('total', 'status_absensi');
    }

    public function getAttendanceGroupedBySession($attendanceCollection)
    {
        $grouped = $attendanceCollection->groupBy(function($item) {
            return $item->tanggal->format('Y-m-d') . '|' . $item->id_kelas . '|' . $item->id_mapel . '|' . $item->id_guru;
        });

        $result = [];
        foreach ($grouped as $key => $group) {
            $firstItem = $group->first();
            list($date, $classId, $subjectId, $teacherId) = explode('|', $key);

            $course = $this->findCourse($classId, $subjectId, $teacherId);

            $result[$key] = [
                'attendance' => $group,
                'course' => $course,
                'date' => $date,
                'classId' => $classId,
                'subjectId' => $subjectId,
                'teacherId' => $teacherId,
                'firstItem' => $firstItem
            ];
        }

        return $result;
    }

    public function getClasses()
    {
        return Kelas::all();
    }

    public function getSubjects()
    {
        return MataPelajaran::all();
    }

    public function findCourse($classId, $subjectId, $teacherId)
    {
        return Course::where('id_kelas', $classId)
                     ->where('id_mapel', $subjectId)
                     ->where('id_guru', $teacherId)
                     ->first();
    }
}