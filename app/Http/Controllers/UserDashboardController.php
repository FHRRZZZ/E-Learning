<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\TugasKumpul; // pastikan modelnya ada

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua presensi user ini
        $presensis = Presensi::where('user_id', $user->id)->get();

        // Hitung jumlah kehadiran berdasarkan aksi
        $hadir = $presensis->where('aksi', 'hadir')->count();
        $sakit = $presensis->where('aksi', 'sakit')->count();
        $izin = $presensis->where('aksi', 'izin')->count();
        $alpa = $presensis->where('aksi', 'alpa')->count();

        // Ambil semua nilai dari tabel tugas_kumpuls
        $nilaiUser = TugasKumpul::where('user_id', $user->id)->pluck('nilai');

        // Hitung rata-rata nilai (kalau ada)
        $rataNilai = $nilaiUser->count() > 0
            ? round($nilaiUser->filter()->avg(), 2)  // filter() supaya nilai null tidak ikut dihitung
            : 0;

        // Ambil daftar tugas beserta nilai
        $daftarNilai = TugasKumpul::where('user_id', $user->id)
            ->with('tugas') // pastikan relasi ke tabel tugas dibuat di model
            ->get();

        return view('user.dashboard', compact(
            'user', 'hadir', 'sakit', 'izin', 'alpa', 'rataNilai', 'daftarNilai'
        ));
    }
}
