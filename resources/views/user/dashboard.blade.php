@extends('layouts.user')

@section('title', 'Dashboard User')
@section('header', 'Dashboard User')

@section('content')
<style>
:root{
  --bg:#f5f7fb;
  --page-bg: linear-gradient(180deg,#ffffff 0%, #f5f7fb 60%);
  --card-bg: linear-gradient(180deg, #ffffff, #fbfdff);
  --muted:#6b7280;

  /* warna dipisah: heading, body, aksen */
  --text-heading: #0f172a;   /* warna gelap untuk judul */
  --text-body: #334155;      /* warna utama isi teks */
  --accent: #2563eb;         /* warna biru sidebar untuk aksen / tombol */
  --accent-2: linear-gradient(90deg,#2563eb,#06b6d4);

  --success:#16a34a;
  --danger:#ef4444;
  --glass: rgba(15,23,42,0.03);
  --radius:14px;
  --shadow: 0 10px 30px rgba(15,23,42,0.08);
  --soft-shadow: 0 6px 18px rgba(15,23,42,0.06);
  --glass-border: 1px solid rgba(15,23,42,0.04);
}

*{box-sizing:border-box}
body{
  background:var(--page-bg);
  font-family:"Segoe UI",Tahoma,Arial,sans-serif;
  color:var(--text-body);
  margin:0;padding:0
}
.dashboard-container{max-width:1100px;margin:30px auto;padding:22px;display:flex;flex-direction:column;gap:22px}

/* Title */
.title{
  font-size:28px;font-weight:800;margin:0 0 6px;display:flex;align-items:center;gap:10px;
  color:var(--text-heading);
}
.title i{opacity:0.95}
.title span{background:var(--accent-2);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

/* Card base */
.card{
  background:var(--card-bg);
  border-radius:var(--radius);
  padding:20px;
  box-shadow:var(--soft-shadow);
  border:var(--glass-border);
  transition:transform .18s ease, box-shadow .18s ease;
}
.card:hover{transform:translateY(-6px);box-shadow:var(--shadow)}

/* Card title */
.card-title{
  font-size:18px;font-weight:700;color:var(--text-heading);display:flex;align-items:center;gap:10px;margin:0 0 12px;
}

/* Grid */
.grid-4{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-top:10px}

/* Stat boxes */
.stat{
  border-radius:12px;padding:16px;text-align:center;font-weight:700;font-size:15px;
  color:#fff;display:flex;align-items:center;justify-content:center;gap:8px;min-height:64px;
  box-shadow:0 6px 14px rgba(2,6,23,0.12)
}
.stat i{opacity:0.95}
.stat.hadir{background:linear-gradient(135deg,#16a34a,#059669)}
.stat.sakit{background:linear-gradient(135deg,#fbbf24,#f97316)}
.stat.izin{background:linear-gradient(135deg,#2563eb,#06b6d4)}
.stat.alpa{background:linear-gradient(135deg,#ef4444,#dc2626)}

/* Rata-rata Nilai */
.nilai-text{
  font-size:32px;font-weight:900;color:var(--text-heading);margin:6px 0
}
.progress-bar{background:rgba(15,23,42,0.03);border-radius:999px;height:16px;overflow:hidden;border:1px solid rgba(15,23,42,0.02)}
.progress{height:100%;border-radius:999px;background:linear-gradient(90deg,#06b6d4,#7c3aed);transition:width .7s cubic-bezier(.2,.9,.3,1)}

/* Daftar nilai */
.nilai-list{margin-top:8px;padding:0;list-style:none}
.nilai-item{
  display:flex;justify-content:space-between;align-items:center;padding:12px 0;
  border-bottom:1px solid rgba(15,23,42,0.04);font-size:15px;color:var(--text-body)
}
.nilai-item.kosong{color:var(--muted);text-align:center;padding:18px 0}
.badge{padding:6px 12px;border-radius:999px;font-weight:800;font-size:13px;min-width:48px;text-align:center}
.nilai-bagus{background:var(--success);color:#04260f}
.nilai-cukup{background:#f59e0b;color:#071028}
.nilai-jelek{background:var(--danger);color:#2b0505}

/* Chart */
canvas{width:100%!important;max-height:320px}

/* Floating action button */
.btn-float{position:fixed;right:22px;bottom:22px;width:58px;height:58px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#07203a;box-shadow:0 14px 30px rgba(3,6,23,0.12);text-decoration:none;z-index:999}
.btn-blue{background:var(--accent);color:#fff}
.btn-float:hover{transform:translateY(-6px) rotate(6deg)}

/* Responsiveness */
@media (max-width:900px){
  .dashboard-container{padding:16px}
  .title{font-size:22px}
  .nilai-text{font-size:28px}
}
@media (max-width:560px){
  .grid-4{grid-template-columns:repeat(2,1fr)}
  .card{padding:16px}
}
</style>

<div class="dashboard-container">
    <h1 class="title">
        <i data-lucide="user-circle-2" class="w-8 h-8 text-blue-600"></i>
        Halo, {{ $user->name }}
    </h1>

    {{-- Ringkasan Presensi --}}
    <div class="card">
        <h2 class="card-title">
            <i data-lucide="calendar-check" class="w-6 h-6 text-green-600"></i>
            Ringkasan Presensi
        </h2>
        <div class="grid-4">
            <div class="stat hadir"><i data-lucide="check-circle"></i> Hadir: {{ $hadir }}</div>
            <div class="stat sakit"><i data-lucide="thermometer"></i> Sakit: {{ $sakit }}</div>
            <div class="stat izin"><i data-lucide="clock-4"></i> Izin: {{ $izin }}</div>
            <div class="stat alpa"><i data-lucide="x-circle"></i> Alpa: {{ $alpa }}</div>
        </div>
    </div>

    {{-- Rata-rata Nilai --}}
    <div class="card">
        @php
            $r = $rataNilai ?? null;
            if (is_null($r)) {
                $display = 'Belum ada nilai';
                $percent = 0;
                $status = 'Belum dinilai';
                $color = '#94a3b8';
            } else {
                $percent = round($r, 1);
                if ($percent >= 85) { $status = 'Sangat Baik'; $color = '#16a34a'; }
                elseif ($percent >= 70) { $status = 'Baik'; $color = '#f59e0b'; }
                else { $status = 'Perlu Perbaikan'; $color = '#ef4444'; }
                $display = $percent . '%';
            }
        @endphp

        <h2 class="card-title">
            <i data-lucide="star" class="w-6 h-6" style="color: {{ $color }};"></i>
            Rata-rata Nilai
        </h2>

        <p class="nilai-text" aria-live="polite">{{ $display }}</p>
        <div class="small-muted" style="margin-bottom:8px;color:var(--text-body)">{{ $status }}</div>

        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress" style="width: {{ $percent }}%; background: linear-gradient(90deg, {{ $color }}, #7c3aed);"></div>
        </div>
    </div>

    {{-- Daftar Nilai Tugas --}}
    <div class="card">
        <h2 class="card-title">
            <i data-lucide="list-checks" class="w-6 h-6 text-indigo-600"></i>
            Daftar Nilai Tugas
        </h2>
        <ul class="nilai-list">
            @forelse($daftarNilai as $tugas)
                @php
                    $nilai = $tugas->nilai ?? 0;
                    if($nilai >= 85) $class = 'nilai-bagus';
                    elseif($nilai >= 70) $class = 'nilai-cukup';
                    else $class = 'nilai-jelek';
                @endphp
                <li class="nilai-item">
                    <span>{{ $tugas->tugas->judul ?? 'Tidak ada judul' }}</span>
                    <span class="badge {{ $class }}">{{ $nilai }}</span>
                </li>
            @empty
                <li class="nilai-item kosong">Belum ada nilai</li>
            @endforelse
        </ul>
    </div>

    {{-- Grafik Aktivitas Harian --}}
    <div class="card">
        <h2 class="card-title">
            <i data-lucide="chart-line" class="w-6 h-6 text-blue-600"></i>
            Grafik Aktivitas Harian
        </h2>
        <canvas id="activityChart" height="100"></canvas>
    </div>
</div>

{{-- Floating Button untuk Gemini --}}
<a href="{{ route('gemini') }}" class="btn-float btn-blue">
    <i data-lucide="message-circle"></i>
</a>
<a href="{{ route('gemini') }}" class="btn-float btn-blue">
    <i data-lucide="message-circle"></i>
</a>

{{-- ==== SCRIPT ==== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Aktifkan ikon Lucide
    lucide.createIcons();

    const tugasData = @json(
        $daftarNilai->map(fn($t) => [
            'tanggal' => $t->created_at->format('Y-m-d'),
            'nilai'   => $t->nilai ? (float) $t->nilai : 0
        ])
    );

    const labels = tugasData.map(t => {
        const d = new Date(t.tanggal);
        return d.toLocaleDateString('id-ID', { weekday: 'long' });
    });
    const dataNilai = tugasData.map(t => t.nilai);

    new Chart(document.getElementById('activityChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Nilai Harian',
                data: dataNilai,
                backgroundColor: 'rgba(37,99,235,0.1)',
                borderColor: '#2563eb',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { min: 0, max: 100, ticks: { stepSize: 10 } } }
        }
    });
</script>
@endsection
