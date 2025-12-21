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
        $query = RekapAbsensi::with([
            'siswa:id_siswa,nama,nisn',
            'kelas:id_kelas,nama_kelas',
            'guru:id_guru,nama',
            'mataPelajaran:id_mapel,nama_mapel'
        ]);

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

        $courseKeys = [];
        foreach ($grouped as $key => $group) {
            list($date, $classId, $subjectId, $teacherId) = explode('|', $key);
            $courseKeys[] = ['classId' => $classId, 'subjectId' => $subjectId, 'teacherId' => $teacherId];
        }

        $uniqueCourseKeys = collect($courseKeys)->unique(function ($item) {
            return $item['classId'] . '|' . $item['subjectId'] . '|' . $item['teacherId'];
        })->values();

       
        $courseLookup = [];
        if ($uniqueCourseKeys->isNotEmpty()) {
            $coursesQuery = Course::query();

            foreach ($uniqueCourseKeys as $index => $keyData) {
                $whereClause = [
                    'id_kelas' => $keyData['classId'],
                    'id_mapel' => $keyData['subjectId'],
                    'id_guru' => $keyData['teacherId']
                ];

                if ($index === 0) {
                    $coursesQuery->where($whereClause);
                } else {
                    $coursesQuery->orWhere($whereClause);
                }
            }

            $courses = $coursesQuery->get();

            foreach ($courses as $course) {
                $lookupKey = $course->id_kelas . '|' . $course->id_mapel . '|' . $course->id_guru;
                $courseLookup[$lookupKey] = $course;
            }
        }

        $result = [];
        foreach ($grouped as $key => $group) {
            $firstItem = $group->first();
            list($date, $classId, $subjectId, $teacherId) = explode('|', $key);

            $lookupKey = $classId . '|' . $subjectId . '|' . $teacherId;
            $course = $courseLookup[$lookupKey] ?? null;

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
        return Kelas::select('id_kelas', 'nama_kelas')->get();
    }

    public function getSubjects()
    {
        return MataPelajaran::select('id_mapel', 'nama_mapel')->get();
    }

    public function findCourse($classId, $subjectId, $teacherId)
    {
        return Course::where('id_kelas', $classId)
                     ->where('id_mapel', $subjectId)
                     ->where('id_guru', $teacherId)
                     ->first();
    }
}