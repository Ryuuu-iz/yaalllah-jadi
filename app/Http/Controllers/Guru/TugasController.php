<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Course;
use App\Models\MateriPembelajaran;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->dataGuru;

        if (!$guru) {
            return redirect('/profile/complete')->with('error', 'Please complete your teacher profile to continue.');
        }

        $query = Tugas::whereHas('course', function($q) use ($guru) {
            $q->where('id_guru', $guru->id_guru);
        })->with(['course.kelas', 'course.mataPelajaran', 'course.siswa', 'materi', 'pengumpulanTugas']);

        // Filter by search
        if ($request->filled('search')) {
            $query->where('nama_tugas', 'like', '%' . $request->search . '%');
        }

        // Filter by course
        if ($request->filled('id_course')) {
            $query->where('id_course', $request->id_course);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('deadline', '>=', now());
            } elseif ($request->status == 'overdue') {
                $query->where('deadline', '<', now());
            }
        }

        $tugas = $query->orderBy('deadline', 'desc')->paginate(10);

        return view('guru.tasks.index', compact('tugas'));
    }

    public function create()
    {
        $guru = auth()->user()->dataGuru;

        if (!$guru) {
            return redirect('/profile/complete')->with('error', 'Please complete your teacher profile to continue.');
        }

        $courses = Course::where('id_guru', $guru->id_guru)
            ->with(['kelas', 'mataPelajaran', 'materiPembelajaran'])
            ->get();

        return view('guru.tasks.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'desk_tugas' => 'nullable|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'deadline' => 'required|date|after:now',
            'id_course' => 'required|exists:course,id_course',
            'id_materi' => 'required|exists:materi_pembelajaran,id_materi',
        ]);

        // Verifikasi bahwa course milik guru yang login
        $guru = auth()->user()->dataGuru;

        if (!$guru) {
            return redirect('/profile/complete')->with('error', 'Please complete your teacher profile to continue.');
        }

        $course = Course::where('id_course', $validated['id_course'])
                       ->where('id_guru', $guru->id_guru)
                       ->first();

        if (!$course) {
            return redirect()->back()->with('error', 'You do not have permission to create assignment for this course.');
        }

        $data = $validated;
        $data['tgl_upload'] = now()->format('Y-m-d');

        // Upload file jika ada
        if ($request->hasFile('file_tugas')) {
            $file = $request->file('file_tugas');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tugas', $filename, 'public');
            $data['file_tugas'] = $path;
        }

        $tugas = Tugas::create($data);

        return redirect()->route('guru.tasks.show', $tugas->id_tugas)
            ->with('success', 'Assignment successfully created');
    }

    public function show($id)
    {
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;

        if (!$guru) {
            return redirect('/profile/complete')->with('error', 'Please complete your teacher profile to continue.');
        }

        $tugas = Tugas::with([
            'course.kelas',
            'course.mataPelajaran',
            'course.siswa',
            'materi',
            'pengumpulanTugas.siswa'
        ])->findOrFail($id);

        if ($tugas->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }

        return view('guru.tasks.show', compact('tugas'));
    }

    public function destroy($id)
    {
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;

        if (!$guru) {
            return redirect('/profile/complete')->with('error', 'Please complete your teacher profile to continue.');
        }

        $tugas = Tugas::findOrFail($id);

        if ($tugas->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }

        // Delete task file if exists
        if ($tugas->file_tugas && \Storage::disk('public')->exists($tugas->file_tugas)) {
            \Storage::disk('public')->delete($tugas->file_tugas);
        }

        // Delete all submission files
        foreach ($tugas->pengumpulanTugas as $pengumpulan) {
            if ($pengumpulan->file_pengumpulan && \Storage::disk('public')->exists($pengumpulan->file_pengumpulan)) {
                \Storage::disk('public')->delete($pengumpulan->file_pengumpulan);
            }
        }

        $tugas->delete();

        return redirect()->route('guru.tasks.index')
                        ->with('success', 'Assignment successfully deleted');
    }

    public function gradeSubmission(Request $request, $id)
    {
        // Verifikasi akses
        $guru = auth()->user()->dataGuru;

        if (!$guru) {
            return redirect('/profile/complete')->with('error', 'Please complete your teacher profile to continue.');
        }

        $pengumpulan = PengumpulanTugas::findOrFail($id);

        if ($pengumpulan->tugas->course->id_guru !== $guru->id_guru) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'feedback_guru' => 'nullable|string',
        ]);

        $pengumpulan->update($validated);

        return back()->with('success', 'Grade successfully saved');
    }
}