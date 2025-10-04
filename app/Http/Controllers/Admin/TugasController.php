<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $kelasOptions = ['1','2','3','4','5','6'];

        $query = Tugas::query();

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $tugas = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.tugas.index', compact('tugas', 'kelasOptions'));
    }

    public function create()
    {
        $kelasOptions = ['1','2','3','4','5','6'];
        return view('admin.tugas.create', compact('kelasOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|in:1,2,3,4,5,6',
            'judul' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,zip|max:5120', // max 5MB
        ]);

        $path = $request->file('file')->store('tugas_files');

        Tugas::create([
            'kelas' => $request->kelas,
            'judul' => $request->judul,
            'file' => $path,
        ]);

        return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil diupload.');
    }

    public function show(Tugas $tugas)
    {
        //
    }

    public function edit(Tugas $tugas)
    {
        //
    }

    public function update(Request $request, Tugas $tugas)
    {
        //
    }

public function destroy($id)
{
    $tugas = Tugas::find($id);

    if (!$tugas) {
        abort(404, 'Tugas tidak ditemukan');
    }

    if ($tugas->file) {
        Storage::delete($tugas->file);
    }

    $tugas->delete();

    return redirect()->route('admin.tugas.index')->with('success', 'Tugas berhasil dihapus.');
}

}

