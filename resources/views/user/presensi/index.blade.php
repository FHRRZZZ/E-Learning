@extends('layouts.user')

@section('title', 'Presensi Saya')
@section('header', 'Presensi Saya')

@section('content')
<style>
:root {
  --page-bg: #eef2f7;
  --card-bg: #ffffff;
  --text: #1e293b;
  --muted: #64748b;
  --accent: #2563eb;
  --accent-light: #dbeafe;
  --success: #16a34a;
  --warning: #f59e0b;
  --danger: #ef4444;
  --radius: 14px;
  --shadow: 0 8px 20px rgba(0,0,0,0.08);
}

body {
  background: var(--page-bg);
  font-family: "Poppins", sans-serif;
  color: var(--text);
  margin: 0;
}

.container {
  max-width: 1080px;
  margin: 30px auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 24px;
}

/* Heading */
h2 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 10px;
  color: var(--text);
  border-left: 6px solid var(--accent);
  padding-left: 12px;
}

/* Grid */
.grid {
  display: flex;
  flex-wrap: wrap;
  gap: 24px;
}

/* Card */
.card {
  background: var(--card-bg);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 20px;
  width: 320px;
  transition: all 0.25s ease;
  border-top: 4px solid transparent;
  cursor: default;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 26px rgba(0,0,0,0.1);
}

/* Date and Time */
.card .date-time {
  font-size: 14px;
  color: var(--muted);
  margin-bottom: 8px;
}

/* Status */
.status {
  display: inline-block;
  padding: 8px 16px;
  border-radius: 999px;
  font-weight: 600;
  font-size: 15px;
  margin-bottom: 10px;
}

.status-hadir {
  background: #dcfce7;
  color: var(--success);
  border-left: 5px solid var(--success);
}
.status-sakit {
  background: #fef9c3;
  color: var(--warning);
  border-left: 5px solid var(--warning);
}
.status-izin {
  background: #dbeafe;
  color: var(--accent);
  border-left: 5px solid var(--accent);
}
.status-alpa {
  background: #fee2e2;
  color: var(--danger);
  border-left: 5px solid var(--danger);
}

/* Keterangan */
.keterangan {
  font-size: 15px;
  color: var(--muted);
  margin-top: 6px;
}

/* Pagination */
.pagination {
  margin-top: 10px;
  text-align: center;
}
.pagination nav a {
  color: var(--accent);
  font-weight: 500;
}

/* Responsive */
@media (max-width: 980px) {
  .card {
    width: 100%;
  }
  .grid {
    flex-direction: column;
  }
}
</style>

<div class="container">
    <h2>Riwayat Presensi</h2>

    @if($presensis->isEmpty())
        <p style="color: #6b7280;">Belum ada data presensi.</p>
    @else
        <div class="grid">
            @foreach ($presensis as $presensi)
                <div class="card">
                    <div class="date-time">
                        {{ $presensi->tanggal->format('d M Y') }} - {{ $presensi->jam }}
                    </div>
                    <div class="status
                        {{ $presensi->aksi === 'hadir' ? 'status-hadir' : ($presensi->aksi === 'sakit' ? 'status-sakit' : ($presensi->aksi === 'izin' ? 'status-izin' : 'status-alpa')) }}">
                        {{ ucfirst($presensi->aksi) }}
                    </div>
                    <div class="keterangan">
                        {{ $presensi->keterangan ?? '-' }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $presensis->links() }}
        </div>
    @endif
</div>
@endsection
