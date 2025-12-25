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

        if (!$siswa) {
            return redirect('/profile/complete')->with('error', 'Please complete your student profile to continue.');
        }

        $absensi = RekapAbsensi::where('id_absensi', $validated['id_absensi'])
                              ->where('id_siswa', $siswa->id_siswa)
                              ->firstOrFail();

        // Cek apakah masih bisa absen
        if (!$absensi->canSubmit()) {
            $reason = $absensi->getCannotSubmitReason();
            return back()->with('error', $reason ?? 'Anda tidak dapat melakukan absensi saat ini');
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

        if (!$siswa) {
            return redirect('/profile/complete')->with('error', 'Please complete your student profile to continue.');
        }

        $absensi = RekapAbsensi::where('id_absensi', $validated['id_absensi'])
                              ->where('id_siswa', $siswa->id_siswa)
                              ->firstOrFail();

        // Cek apakah masih bisa submit
        if (!$absensi->canSubmit()) {
            $reason = $absensi->getCannotSubmitReason();
            return back()->with('error', $reason ?? 'Anda tidak dapat mengajukan izin/sakit saat ini');
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