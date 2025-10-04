<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\TugasKumpul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
public function index()
{
    $user = Auth::user();

    $tugasList = Tugas::where('kelas', $user->kelas)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

    // Ambil data lengkap tugas_kumpul user (bukan hanya pluck id)
    $tugasKumpulUser = TugasKumpul::where('user_id', $user->id)
                            ->get()
                            ->keyBy('tugas_id');

    return view('user.tugas.index', compact('tugasList', 'tugasKumpulUser'));
}



public function upload(Request $request, Tugas $tugas)
{
    $user = Auth::user();

    if ($tugas->kelas != $user->kelas) {
        abort(403, 'Tidak berhak mengupload tugas ini.');
    }

    $request->validate([
        'file' => 'required|file|mimes:pdf,doc,docx,zip|max:5120',
    ]);

    $path = $request->file('file')->store('tugas_files');

    $tugasKumpul = TugasKumpul::updateOrCreate(
        ['tugas_id' => $tugas->id, 'user_id' => $user->id],
        ['file' => $path, 'nilai' => null, 'komentar' => null]
    );

    return redirect()->route('user.tugas.index')->with('success', 'Tugas berhasil diupload.');
}

}
