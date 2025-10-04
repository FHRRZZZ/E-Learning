@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<style>
:root{
  --bg:#f8fafc;
  --card:#ffffff;
  --muted:#6b7280;
  --accent-blue-start:#3b82f6;
  --accent-blue-end:#1e40af;
  --glass: rgba(255,255,255,0.6);
  --shadow: 0 8px 24px rgba(2,6,23,0.08);
}

*{box-sizing:border-box}
body{background:var(--bg);font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;color:#0f172a;margin:0;padding:0}
.header-wrap{background:linear-gradient(135deg,var(--accent-blue-start),var(--accent-blue-end));border-radius:12px;padding:28px 26px;color:#fff;box-shadow:var(--shadow);margin-bottom:28px}
.header-inner{display:flex;align-items:center;gap:14px}
.header-inner .title{font-size:22px;font-weight:700;margin:0}
.header-inner .sub{font-size:14px;color:rgba(255,255,255,0.92);margin:0}

.grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:20px;
  align-items:stretch;
}
@media (max-width:980px){ .grid{grid-template-columns:repeat(2,1fr)} }
@media (max-width:640px){ .grid{grid-template-columns:1fr} .header-inner{flex-direction:column;align-items:flex-start} }

.card{
  background:var(--card);
  border-radius:12px;
  padding:18px;
  display:flex;
  gap:14px;
  align-items:center;
  border:1px solid rgba(15,23,42,0.04);
  box-shadow:0 6px 18px rgba(12,18,43,0.04);
  transition:transform .18s ease,box-shadow .18s ease;
}
.card:hover{transform:translateY(-6px);box-shadow:0 16px 34px rgba(12,18,43,0.08)}
.icon{
  width:56px;height:56px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;flex-shrink:0;
}
.icon.blue{background:linear-gradient(135deg,#60a5fa,#2563eb)}
.icon.green{background:linear-gradient(135deg,#4ade80,#16a34a)}
.icon.yellow{background:linear-gradient(135deg,#facc15,#eab308)}
.icon.red{background:linear-gradient(135deg,#f87171,#dc2626)}
.stat-title{font-size:13px;color:var(--muted);margin:0}
.stat-value{font-size:24px;font-weight:700;color:#0f172a;margin:6px 0 0}

.chart-wrap{
  margin-top:26px;background:var(--card);padding:20px;border-radius:12px;border:1px solid rgba(15,23,42,0.04);box-shadow:0 8px 24px rgba(12,18,43,0.04);
}
.chart-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.chart-head h2{font-size:16px;margin:0;color:#111827;font-weight:700}
.canvas-container{height:320px;width:100%}
.small-muted{font-size:13px;color:var(--muted);margin-top:6px}

/* accessibility focus */
a:focus,button:focus,input:focus,select:focus{outline:3px solid rgba(99,102,241,0.18);outline-offset:2px}
</style>

<div class="header-wrap">
  <div class="header-inner">
    <span data-lucide="crown" style="width:32px;height:32px"></span>
    <div>
      <p class="title">Selamat datang, {{ auth()->user()->name }}</p>
      <p class="sub">Ini adalah halaman dashboard admin. Kelola data dan pantau aktivitas di sini.</p>
    </div>
  </div>
</div>

<div class="grid">
  <div class="card">
    <div class="icon blue" aria-hidden="true"><span data-lucide="users"></span></div>
    <div>
      <p class="stat-title">Jumlah User</p>
      <p class="stat-value">{{ $jumlahUser }}</p>
    </div>
  </div>

  <div class="card">
    <div class="icon yellow" aria-hidden="true"><span data-lucide="clipboard-list"></span></div>
    <div>
      <p class="stat-title">Total Tugas</p>
      <p class="stat-value">{{ $totalTugas }}</p>
    </div>
  </div>

  <div class="card">
    <div class="icon red" aria-hidden="true"><span data-lucide="calendar"></span></div>
    <div>
      <p class="stat-title">Presensi Hari Ini</p>
      <p class="stat-value">{{ $presensiHariIni }}</p>
    </div>
  </div>
</div>

<div class="chart-wrap">
  <div class="chart-head">
    <h2>Grafik Aktivitas Harian</h2>
    <div class="small-muted">Perbandingan tugas dan absensi</div>
  </div>
  <div class="canvas-container">
    <canvas id="aktivitasChart" aria-label="Grafik Aktivitas Harian" role="img"></canvas>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
lucide.createIcons();

const ctx = document.getElementById('aktivitasChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: {!! json_encode($labels) !!},
    datasets: [
      {
        label: 'Tugas',
        data: {!! json_encode($dataTugas) !!},
        borderColor: 'rgba(59,130,246,1)',
        backgroundColor: 'rgba(59,130,246,0.12)',
        fill: true,
        tension: 0.36,
        pointRadius: 4,
        pointBackgroundColor: 'rgba(59,130,246,1)'
      },
      {
        label: 'Absensi',
        data: {!! json_encode($dataAbsensi) !!},
        borderColor: 'rgba(34,197,94,1)',
        backgroundColor: 'rgba(34,197,94,0.12)',
        fill: true,
        tension: 0.36,
        pointRadius: 4,
        pointBackgroundColor: 'rgba(34,197,94,1)'
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { position: 'top' } },
    scales: { y: { beginAtZero: true } }
  }
});
</script>
@endsection
