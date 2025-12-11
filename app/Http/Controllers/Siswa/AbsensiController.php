<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\RekapAbsensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Submit attendance (siswa absen mandiri)
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'id_absensi' => 'required|exists:rekap_absensi,id_absensi',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $siswa = auth()->user()->dataSiswa;
        
        $absensi = RekapAbsensi::where('id_absensi', $validated['id_absensi'])
                              ->where('id_siswa', $siswa->id_siswa)
                              ->firstOrFail();

        // Cek apakah masih bisa absen
        if (!$absensi->canSubmit()) {
            if ($absensi->isExpired()) {
                return back()->with('error', 'Waktu absensi telah berakhir');
            }
            return back()->with('error', 'Absensi sudah ditutup');
        }

        // Cek apakah sudah absen
        if ($absensi->status_absensi !== 'alpha') {
            return back()->with('error', 'Anda sudah melakukan absensi');
        }

        // Update status menjadi hadir
        $absensi->update([
            'status_absensi' => 'hadir',
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return back()->with('success', 'Absensi berhasil! Status Anda: Hadir');
    }

    /**
     * Request permission atau sakit (dengan keterangan)
     */
    public function requestPermission(Request $request)
    {
        $validated = $request->validate([
            'id_absensi' => 'required|exists:rekap_absensi,id_absensi',
            'status' => 'required|in:izin,sakit',
            'keterangan' => 'required|string|max:500',
        ]);

        $siswa = auth()->user()->dataSiswa;
        
        $absensi = RekapAbsensi::where('id_absensi', $validated['id_absensi'])
                              ->where('id_siswa', $siswa->id_siswa)
                              ->firstOrFail();

        // Cek apakah masih bisa submit
        if (!$absensi->canSubmit()) {
            if ($absensi->isExpired()) {
                return back()->with('error', 'Waktu absensi telah berakhir');
            }
            return back()->with('error', 'Absensi sudah ditutup');
        }

        // Update status
        $absensi->update([
            'status_absensi' => $validated['status'],
            'keterangan' => $validated['keterangan'],
        ]);

        $statusText = $validated['status'] === 'izin' ? 'Izin' : 'Sakit';
        
        return back()->with('success', "Pengajuan {$statusText} berhasil dikirim");
    }
}