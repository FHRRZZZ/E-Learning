<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PresensiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with('user')->orderBy('tanggal', 'desc');

        // Filter berdasarkan kelas user jika ada parameter kelas
        if ($request->filled('kelas')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter berdasarkan tanggal jika ada parameter tanggal
        if ($request->filled('tanggal')) {
            // Jika tanggal dikirim, filter presensi yang tanggalnya sama
            $query->whereDate('tanggal', $request->tanggal);
        }

        $presensis = $query->paginate(10);

        // Opsi kelas 1-6 untuk dropdown filter
        $kelasOptions = ['1', '2', '3', '4', '5', '6'];

        return view('admin.absensi.index', compact('presensis', 'kelasOptions'));
    }

    public function export(Request $request)
    {
        $kelas = $request->kelas;
        $tanggal = $request->tanggal;

        return Excel::download(new PresensiExport($kelas, $tanggal), 'presensi.xlsx');
    }


    public function create()
    {
        $users = User::orderBy('name')->get();
        $kelasOptions = ['1', '2', '3', '4', '5', '6'];

        return view('admin.absensi.create', compact('users', 'kelasOptions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'aksi' => 'required|in:hadir,sakit,izin,alpa',
            'keterangan' => 'nullable|string',
        ]);

        Presensi::create($request->all());

        return redirect()->route('admin.absensi.index')->with('success', 'Data absensi berhasil disimpan.');
    }

    public function scan()
    {
        $today = Carbon::today()->toDateString();

        $users = User::with(['presensis' => function ($query) use ($today) {
            $query->whereDate('tanggal', $today);
        }])->select('id', 'name', 'nisn')->orderBy('name')->get();

        return view('admin.absensi.scan', compact('users'));
    }

    public function scanStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
        ]);

        $nisnOrId = $request->user_id;
        $today = Carbon::today()->toDateString();
        $jam = Carbon::now()->toTimeString();

        // cari user (nisn atau id)
        $user = User::where('nisn', $nisnOrId)->orWhere('id', $nisnOrId)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // gunakan whereDate agar cocok baik kolom date maupun datetime
        $sudahAbsen = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if (!$sudahAbsen) {
            Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,               // simpan sebagai 'YYYY-MM-DD'
                'jam' => $jam,
                'aksi' => 'hadir',
                'keterangan' => 'Scan QR Code',
            ]);
        }

        return response()->json([
            'message' => $sudahAbsen ? 'User sudah absen hari ini' : 'Absensi berhasil disimpan',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nisn' => $user->nisn,
                'status' => $sudahAbsen ? 'Hadir (Sudah Absen)' : 'Hadir',
            ],
        ]);
    }


}
