<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TugasKumpul;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\TugasKumpulExport;
use Maatwebsite\Excel\Facades\Excel;


class TugasKumpulController extends Controller
{
    // Halaman admin: daftar tugas yang sudah dikumpul
    public function index(Request $request)
    {
        $query = TugasKumpul::with(['tugas', 'user'])->orderBy('created_at', 'desc');

        // Filter berdasarkan kelas tugas jika ada parameter kelas
        if ($request->filled('kelas')) {
            $query->whereHas('tugas', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        $kelasOptions = ['1', '2', '3', '4', '5', '6'];
        $kumpuls = $query->paginate(10);

        return view('admin.tugas_kumpul.index', compact('kumpuls', 'kelasOptions'));
    }

    // Halaman admin untuk edit nilai dan komentar tugas yang sudah dikumpul
    public function edit(TugasKumpul $tugasKumpul)
    {
        return view('admin.tugas_kumpul.edit', compact('tugasKumpul'));
    }

    // Simpan update nilai dan komentar dari admin
    public function update(Request $request, TugasKumpul $tugasKumpul)
    {
        $request->validate([
            'nilai' => 'nullable|string|max:10',
            'komentar' => 'nullable|string',
        ]);

        $tugasKumpul->update([
            'nilai' => $request->nilai,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('admin.tugas_kumpul.index')->with('success', 'Nilai dan komentar berhasil disimpan.');
    }

    // Halaman user untuk upload tugas (kumpul tugas)
    public function create($tugasId)
    {
        $tugas = Tugas::findOrFail($tugasId);
        return view('user.tugas_kumpul.create', compact('tugas'));
    }

    // Proses upload tugas oleh user
    public function store(Request $request, $tugasId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        $user = auth()->user();

        // Cek apakah user sudah pernah mengumpulkan tugas ini
        $existing = TugasKumpul::where('tugas_id', $tugasId)
            ->where('user_id', $user->id)
            ->first();

        $filePath = $request->file('file')->store('tugas_kumpul');

        if ($existing) {
            // Update file jika sudah pernah upload
            // Hapus file lama jika ada
            Storage::delete($existing->file);
            $existing->update([
                'file' => $filePath,
                'nilai' => null,
                'komentar' => null,
            ]);
        } else {
            // Buat record baru
            TugasKumpul::create([
                'tugas_id' => $tugasId,
                'user_id' => $user->id,
                'file' => $filePath,
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Tugas berhasil dikumpulkan.');
    }
    public function export(Request $request)
    {
        $kelas = $request->kelas;
        return Excel::download(new TugasKumpulExport($kelas), 'tugas-kumpul.xlsx');
    }
}
