<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\RekapAbsensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = auth()->user()->dataSiswa;
        
        $query = RekapAbsensi::where('id_siswa', $siswa->id_siswa)
                            ->with(['kelas', 'guru', 'mataPelajaran']);
        
        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        
        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }
        
        $absensi = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        // Statistik
        $stats = RekapAbsensi::where('id_siswa', $siswa->id_siswa)
                            ->selectRaw('status_absensi, COUNT(*) as total')
                            ->groupBy('status_absensi')
                            ->pluck('total', 'status_absensi');
        
        // Statistik per bulan (6 bulan terakhir)
        $monthlyStats = RekapAbsensi::where('id_siswa', $siswa->id_siswa)
                                   ->where('tanggal', '>=', now()->subMonths(6))
                                   ->selectRaw('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun, status_absensi, COUNT(*) as total')
                                   ->groupBy('bulan', 'tahun', 'status_absensi')
                                   ->orderBy('tahun', 'desc')
                                   ->orderBy('bulan', 'desc')
                                   ->get();
        
        return view('siswa.absensi.index', compact('absensi', 'stats', 'monthlyStats'));
    }
}