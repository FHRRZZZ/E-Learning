@extends('layouts.user')

@section('content')
<style>
:root{
  --bg-1:#071426;
  --bg-2:#071e3a;
  --card:rgba(255,255,255,0.03);
  --glass-border:rgba(255,255,255,0.06);

  --text-heading:#0b1220;
  --text-body:#0f1724;
  --text-muted:#6b7280;

  --accent:#28a6ff;
  --accent-2:#7c3aed;
  --success:#16a34a;
  --danger:#ef4444;
  --radius:14px;
  --shadow:0 10px 30px rgba(2,6,23,0.06);
  --transition:.18s cubic-bezier(.2,.9,.3,1);
}

body{
  background:linear-gradient(180deg,var(--bg-1),var(--bg-2));
  color:var(--text-body);
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Arial;
  margin:0;padding:0;
}

h1{
  font-size:28px;font-weight:700;
  color:var(--text-heading);
  margin:28px auto 24px;
  max-width:1200px;
  padding:0 20px;
}

/* Grid container */
.grid-tugas{
  max-width:1200px;
  margin:0 auto 40px;
  padding:0 20px;
  display:grid;
  gap:20px;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
}

/* Card */
.card{
  background:var(--card);
  border:1px solid var(--glass-border);
  border-radius:12px;
  padding:18px;
  backdrop-filter:blur(6px);
  box-shadow:var(--shadow);
  transition:transform var(--transition),box-shadow var(--transition);
}
.card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(2,6,23,0.08)}
.card h2{font-size:18px;margin:0 0 12px;color:var(--text-heading);}
.card p{font-size:13px;color:var(--text-muted);margin:0 0 8px}

a.download-link{
  display:inline-block;
  padding:8px 12px;
  border-radius:8px;
  background:linear-gradient(90deg,var(--accent),var(--accent-2));
  color:#fff;
  font-weight:700;text-decoration:none;
  box-shadow:0 6px 18px rgba(40,166,255,0.08);
  transition:transform .2s;
}
a.download-link:hover{transform:translateY(-2px)}

.upload-form{display:flex;gap:10px;align-items:center;margin-top:8px;flex-wrap:wrap}
.upload-form input[type="file"]{
  background:transparent;border:1px dashed rgba(255,255,255,0.08);
  padding:10px;border-radius:8px;color:var(--text-heading);
}
.upload-btn{
  background:linear-gradient(90deg,var(--accent),var(--accent-2));
  border:none;color:#04293b;
  padding:10px 14px;border-radius:10px;
  font-weight:700;cursor:pointer;
  box-shadow:0 10px 24px rgba(44,163,255,0.12);
  transition:transform .2s;
}
.upload-btn:hover{transform:translateY(-3px)}

.status-success{color:var(--success);font-weight:700;margin-top:6px}
.error-msg{color:var(--danger);font-weight:700;margin-top:6px}

.pagination{margin:30px auto 0;max-width:1200px;padding:0 20px;color:var(--text-muted)}
.pagination nav a{color:var(--text-heading)}
</style>

<h1>Daftar Tugas Kelas {{ auth()->user()->kelas }}</h1>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.upload-form').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            const f = form.querySelector('input[name="file"]');
            if (!f.value) {
                Swal.fire({icon:'error',title:'Oops...',text:'Silakan pilih file terlebih dahulu!'});
                return;
            }
            Swal.fire({title:'Mengunggah...',text:'Tugas sedang diupload',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});
            form.submit();
        });
    });
    @if(session('success'))
      Swal.fire({icon:'success',title:'Berhasil!',text:'{{ session('success') }}'});
    @endif
    @if(session('error'))
      Swal.fire({icon:'error',title:'Gagal!',text:'{{ session('error') }}'});
    @endif
});
</script>
@endsection
