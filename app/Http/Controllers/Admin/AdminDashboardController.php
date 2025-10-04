<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Presensi;
use App\Models\Tugas;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $jumlahUser = User::count();
        $totalTugas = Tugas::count();
        $presensiHariIni = Presensi::whereDate('tanggal', today())->count();

        // --- Data untuk grafik ---
        // Ambil range 7 hari terakhir
        $dates = collect(range(0, 6))->map(fn($i) => now()->subDays(6 - $i)->toDateString());

        // Labels (format nama hari)
        $labels = $dates->map(fn($d) => \Carbon\Carbon::parse($d)->locale('id')->dayName);

        // Data Tugas per hari
        $tugasData = Tugas::select(DB::raw('DATE(created_at) as tanggal'), DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $dataTugas = $dates->map(fn($d) => $tugasData[$d] ?? 0);

        // Data Presensi per hari
        $presensiData = Presensi::select(DB::raw('DATE(tanggal) as tanggal'), DB::raw('COUNT(*) as total'))
            ->whereBetween('tanggal', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $dataAbsensi = $dates->map(fn($d) => $presensiData[$d] ?? 0);

        return view('admin.dashboard', [
            'jumlahUser' => $jumlahUser,
            'totalTugas' => $totalTugas,
            'presensiHariIni' => $presensiHariIni,
            'labels' => $labels,
            'dataTugas' => $dataTugas,
            'dataAbsensi' => $dataAbsensi,
        ]);
    }
}
