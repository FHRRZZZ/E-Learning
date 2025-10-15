@extends('layouts.user')

@section('title', 'Dashboard User')
@section('header', 'Dashboard User')

@section('content')
<style>
:root {
  --primary: #2563eb;
  --secondary: #06b6d4;
  --danger: #ef4444;
  --success: #16a34a;
  --warning: #f59e0b;
  --dark: #0f172a;
  --text: #334155;
  --muted: #6b7280;
  --bg: #f3f4f6;
  --card-bg: #ffffff;
  --radius: 14px;
  --shadow: 0 6px 20px rgba(0,0,0,0.08);
}

body {
  margin: 0;
  background: var(--bg);
  font-family: "Poppins", "Segoe UI", Arial, sans-serif;
  color: var(--text);
}

.dashboard-container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
}

.title {
  font-size: 28px;
  font-weight: 700;
  color: var(--dark);
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Card */
.card {
  background: var(--card-bg);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 24px 28px;
  margin-bottom: 24px;
  transition: transform 0.2s ease, box-shadow 0.3s ease;
}
.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}
.card-title {
  font-weight: 700;
  font-size: 18px;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--dark);
}

/* Grid statistik */
.grid-4 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 14px;
}

.stat {
  background: #f9fafb;
  border-radius: 10px;
  padding: 14px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}
.hadir { border-left: 5px solid var(--success); }
.sakit { border-left: 5px solid var(--warning); }
.izin  { border-left: 5px solid var(--secondary); }
.alpa  { border-left: 5px solid var(--danger); }

/* Rata-rata Nilai */
.nilai-text {
  font-size: 34px;
  font-weight: 800;
  text-align: center;
  background: linear-gradient(90deg, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin: 8px 0;
}
.progress-bar {
  background: #e5e7eb;
  border-radius: 10px;
  height: 10px;
  overflow: hidden;
}
.progress {
  height: 100%;
  border-radius: 10px;
  transition: width 0.5s ease;
}

/* Daftar nilai */
.nilai-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.nilai-item {
  display: flex;
  justify-content: space-between;
  padding: 10px 14px;
  border-bottom: 1px solid #e5e7eb;
  font-size: 15px;
}
.nilai-item:last-child { border-bottom: none; }
.badge {
  padding: 4px 10px;
  border-radius: 8px;
  color: white;
  font-weight: 600;
  font-size: 13px;
}
.nilai-bagus { background: var(--success); }
.nilai-cukup { background: var(--warning); }
.nilai-jelek { background: var(--danger); }
.nilai-item.kosong { color: var(--muted); text-align: center; }

/* Tombol mengambang (AI Gemini) */
.btn-float {
  position: fixed;
  bottom: 24px;
  right: 24px;
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  box-shadow: 0 6px 15px rgba(37,99,235,0.4);
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.3s ease;
}
.btn-float:hover {
  transform: scale(1.1);
  box-shadow: 0 8px 25px rgba(37,99,235,0.5);
}

/* Responsif */
@media (max-width: 768px) {
  .title { font-size: 22px; }
  .card { padding: 20px; }
}
</style>

<div class="dashboard-container">
  <h1 class="title">
    <i data-lucide="user-circle-2" class="w-8 h-8 text-blue-600"></i>
    Halo, {{ $user->name }}
  </h1>

  {{-- 🗓️ Ringkasan Presensi --}}
  <div class="card">
    <h2 class="card-title">
      <i data-lucide="calendar-check" class="w-6 h-6 text-green-600"></i> Ringkasan Presensi
    </h2>
    <div class="grid-4">
      <div class="stat hadir"><i data-lucide="check-circle"></i> Hadir: {{ $hadir }}</div>
      <div class="stat sakit"><i data-lucide="thermometer"></i> Sakit: {{ $sakit }}</div>
      <div class="stat izin"><i data-lucide="clock-4"></i> Izin: {{ $izin }}</div>
      <div class="stat alpa"><i data-lucide="x-circle"></i> Alpa: {{ $alpa }}</div>
    </div>
  </div>

  {{-- 🌟 Rata-rata Nilai --}}
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
      <i data-lucide="star" style="color: {{ $color }}"></i> Rata-rata Nilai
    </h2>

    <p class="nilai-text">{{ $display }}</p>
    <p style="text-align:center; color: var(--muted); font-size:14px;">{{ $status }}</p>

    <div class="progress-bar">
      <div class="progress" style="width: {{ $percent }}%; background: {{ $color }}"></div>
    </div>
  </div>

  {{-- 📝 Daftar Nilai Tugas --}}
  <div class="card">
    <h2 class="card-title">
      <i data-lucide="list-checks" class="text-indigo-600"></i> Daftar Nilai Tugas
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
</div>

{{-- 🔘 Tombol Gemini --}}
<a href="{{ route('gemini') }}" class="btn-float">
  <i data-lucide="message-circle"></i>
</a>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
lucide.createIcons();
</script>
@endsection
