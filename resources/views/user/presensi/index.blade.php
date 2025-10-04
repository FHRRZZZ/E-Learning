@extends('layouts.user')

@section('title', 'Presensi Saya')
@section('header', 'Presensi Saya')

@section('content')
<style>
:root{
  --page-bg: #f5f7fb;
  --card-bg: #ffffff;
  --muted: #6b7280;
  --text: #0f172a;
  --accent: #2563eb;
  --accent-2: #06b6d4;
  --success: #16a34a;
  --warning: #f59e0b;
  --danger: #ef4444;
  --glass: rgba(2,6,23,0.04);
  --radius:12px;
  --shadow: 0 10px 30px rgba(2,6,23,0.06);
}

*{box-sizing:border-box}
body{background:var(--page-bg);font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Arial;color:var(--text);margin:0;padding:0}
.container{
  max-width:1060px;
  margin:28px auto;
  padding:24px;
  display:flex;
  flex-direction:column;
  gap:20px;
}

/* Heading */
h2{
  font-size:22px;
  font-weight:700;
  margin:0 0 6px;
  color:var(--text);
  letter-spacing: -0.2px;
}

/* Grid */
.grid{
  display:flex;
  flex-wrap:wrap;
  gap:20px;
}

/* Card */
.card{
  background:var(--card-bg);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  padding:16px;
  width:320px;
  transition:transform .18s ease,box-shadow .18s ease;
  border:1px solid var(--glass);
  display:flex;
  flex-direction:column;
  gap:8px;
}
.card:hover{
  transform:translateY(-6px);
  box-shadow:0 18px 40px rgba(2,6,23,0.08);
}

/* Date/time */
.card .date-time{
  font-size:13px;
  color:var(--muted);
  margin-bottom:2px;
}

/* Status badge */
.card .status{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:8px 12px;
  border-radius:999px;
  font-size:15px;
  font-weight:700;
  width:fit-content;
  box-shadow:0 6px 18px rgba(37,99,235,0.06);
}

/* specific status colors (text use dark for contrast) */
.status-hadir{
  background:linear-gradient(90deg, rgba(22,163,74,0.12), rgba(22,163,74,0.06));
  color:var(--success);
  border:1px solid rgba(22,163,74,0.12);
}
.status-sakit{
  background:linear-gradient(90deg, rgba(245,158,11,0.10), rgba(245,158,11,0.04));
  color:var(--warning);
  border:1px solid rgba(245,158,11,0.08);
}
.status-izin{
  background:linear-gradient(90deg, rgba(37,99,235,0.10), rgba(6,182,212,0.04));
  color:var(--accent);
  border:1px solid rgba(37,99,235,0.08);
}
.status-alpa{
  background:linear-gradient(90deg, rgba(239,68,68,0.10), rgba(239,68,68,0.04));
  color:var(--danger);
  border:1px solid rgba(239,68,68,0.08);
}

/* Keterangan */
.card .keterangan{
  margin-top:6px;
  color:var(--muted);
  font-size:14px;
  line-height:1.3;
}

/* Responsive: make cards full width on small screens */
@media (max-width:980px){
  .card{width:100%}
  .grid{flex-direction:column}
}

/* Pagination */
.pagination{margin-top:12px; color:var(--muted)}
.pagination nav a{color:var(--accent)}
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
