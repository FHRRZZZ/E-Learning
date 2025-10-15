@extends('layouts.user')

@section('content')
<style>
:root{
  --bg-1:#e0f2fe;
  --bg-2:#bfdbfe;
  --card:#ffffff;
  --glass-border:rgba(59,130,246,0.2);
  --text-heading:#1e3a8a;
  --text-body:#1e293b;
  --text-muted:#475569;
  --accent:#3b82f6;
  --accent-2:#2563eb;
  --success:#16a34a;
  --danger:#dc2626;
  --radius:14px;
  --shadow:0 10px 30px rgba(59,130,246,0.15);
  --transition:.18s cubic-bezier(.2,.9,.3,1);
}

body{
  background:linear-gradient(180deg,var(--bg-1),var(--bg-2));
  color:var(--text-body);
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Arial;
  margin:0;padding:0;
}

h1{
  font-size:28px;
  font-weight:700;
  color:var(--text-heading);
  margin:28px auto 24px;
  max-width:1200px;
  padding:0 20px;
}

.grid-tugas{
  max-width:1200px;
  margin:0 auto 40px;
  padding:0 20px;
  display:grid;
  gap:20px;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
}

.card{
  background:var(--card);
  border:1px solid var(--glass-border);
  border-radius:12px;
  padding:18px;
  backdrop-filter:blur(8px);
  box-shadow:var(--shadow);
  transition:transform var(--transition),box-shadow var(--transition);
}
.card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(2,6,23,0.4)}
.card h2{font-size:18px;margin:0 0 12px;color:var(--text-heading);}
.card p{font-size:14px;color:var(--text-body);margin:0 0 8px}

a.download-link{
  display:inline-block;
  padding:8px 12px;
  border-radius:8px;
  background:linear-gradient(90deg,var(--accent),var(--accent-2));
  color:#fff;
  font-weight:700;
  text-decoration:none;
  box-shadow:0 6px 18px rgba(40,166,255,0.15);
  transition:transform .2s, box-shadow .2s;
}
a.download-link:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(40,166,255,0.25)}

.upload-form{display:flex;gap:10px;align-items:center;margin-top:8px;flex-wrap:wrap}
.upload-form input[type="file"]{
  background:rgba(255,255,255,0.08);
  border:1px dashed rgba(255,255,255,0.3);
  padding:10px;
  border-radius:8px;
  color:#000;
}
.upload-btn{
  background:linear-gradient(90deg,var(--accent),var(--accent-2));
  border:none;color:#fff;
  padding:10px 14px;border-radius:10px;
  font-weight:700;cursor:pointer;
  box-shadow:0 10px 24px rgba(44,163,255,0.25);
  transition:transform .2s;
}
.upload-btn:hover{transform:translateY(-3px)}

.status-success{color:var(--success);font-weight:700;margin-top:6px}
.error-msg{color:var(--danger);font-weight:700;margin-top:6px}

.pagination{
  margin:30px auto 0;
  max-width:1200px;
  padding:0 20px;
  color:var(--text-body);
}
.pagination nav a{color:#1e3a8a;text-decoration:none;}
.pagination nav a:hover{text-decoration:underline;}

.card-chart{
  max-width:1200px;
  margin:40px auto;
  background:var(--card);
  border:1px solid var(--glass-border);
  border-radius:12px;
  padding:24px;
  backdrop-filter:blur(10px);
  box-shadow:var(--shadow);
}
.card-chart h2{
  color:var(--text-heading);
  font-size:20px;
  margin-bottom:16px;
}
canvas{width:100%!important;height:auto!important}
</style>

<h1>Daftar Tugas Kelas {{ auth()->user()->kelas }}</h1>

{{-- === Grafik Ditaruh di Atas === --}}
<div class="card-chart">
    <h2>Grafik Nilai Tugas</h2>
    <canvas id="activityChart" height="100"></canvas>
</div>

<div class="grid-tugas">
@foreach ($tugasList as $tugas)
  <div class="card">
      <h2>{{ $tugas->judul }}</h2>
      <p>Diupload: {{ $tugas->created_at->format('d M Y') }}</p>

      <a href="{{ url('tugas_files/' . basename($tugas->file)) }}" target="_blank" class="download-link">Download File</a>

      @if(isset($tugasKumpulUser[$tugas->id]))
          @php $kumpul = $tugasKumpulUser[$tugas->id]; @endphp
          <p class="status-success">Status: Sudah Mengumpulkan</p>

          @if(!is_null($kumpul->nilai))
              @php
                  $n = (float) $kumpul->nilai;
                  if ($n >= 85) { $label = 'Sangat Baik'; $color='#16a34a'; }
                  elseif ($n >= 70) { $label = 'Baik'; $color='#2563eb'; }
                  elseif ($n >= 50) { $label = 'Perlu Perbaikan'; $color='#f59e0b'; }
                  else { $label = 'Kurang'; $color='#ef4444'; }
              @endphp
              <p style="color:{{ $color }};font-weight:700;margin-top:6px;">Nilai: {{ $n }}% - {{ $label }}</p>
          @endif

          @if($kumpul->komentar)
              <p style="color:var(--text-muted)"><strong>Komentar:</strong> {{ $kumpul->komentar }}</p>
          @endif
      @else
          <form action="{{ route('user.tugas.upload', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="upload-form">
              @csrf
              <input type="file" name="file" accept=".pdf,.doc,.docx,.zip" required>
              <button type="submit" class="upload-btn">Upload Tugas</button>
          </form>
      @endif

      @error('file')
          <p class="error-msg">{{ $message }}</p>
      @enderror
  </div>
@endforeach
</div>

<div class="pagination">
    {{ $tugasList->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === Chart.js ===
    const tugasData = @json(
        $tugasList->map(fn($t) => [
            'judul' => $t->judul,
            'nilai' => $tugasKumpulUser[$t->id]->nilai ?? 0
        ])
    );

    const labels = tugasData.map(t => t.judul);
    const dataNilai = tugasData.map(t => t.nilai);

    new Chart(document.getElementById('activityChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Nilai Tugas',
                data: dataNilai,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.2)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: {
              legend: { display: false }
            },
            scales: {
                x: {
                    ticks: { color: '#1e293b', font: { size: 12 } },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                y: {
                    min: 0, max: 100,
                    ticks: { stepSize: 10, color: '#1e293b', font: { size: 12 } },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
});
</script>
@endsection
