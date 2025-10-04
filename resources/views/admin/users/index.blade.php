@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
<style>
    /* ==== Header ==== */
    .page-header {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        border-radius: 14px;
        padding: 22px 24px;
        color: #fff;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 6px 14px rgba(0,0,0,0.12);
    }
    .page-header h1 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }
    .page-header a.btn {
        background: #fff;
        color: #2563eb;
        font-weight: 600;
        border-radius: 8px;
    }
    .page-header a.btn:hover {
        background: #f3f4f6;
    }

    /* ==== Button ==== */
    .btn {
        background-color: #2563eb;
        color: #2563eb !important;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
    }
    .btn:hover {
        background-color: #1d4ed8;
    }
    .btn-green {
        background-color: #16a34a;
        padding: 8px 14px;
        color: white !important;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
        transition: background-color 0.2s;
    }
    .btn-green:hover {
        background-color: #15803d;
    }

    /* ==== Table ==== */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 3px 8px rgba(0,0,0,0.05);
        min-width: 850px;
    }
    thead {
        background-color: #f1f5f9;
        border-bottom: 2px solid #e2e8f0;
    }
    thead th {
        padding: 14px;
        font-weight: 600;
        color: #1e3a8a;
        text-align: left;
        font-size: 14px;
        white-space: nowrap;
    }
    tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }
    tbody tr:hover {
        background-color: #f9fafb;
    }

    /* ==== QR ==== */
    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 8px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    /* ==== Filter ==== */
    .filter-form {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .filter-form label {
        font-weight: 600;
        color: #374151;
    }
    .filter-form select {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 6px 10px;
        min-width: 150px;
    }

    /* ==== Pagination ==== */
    .pagination {
        margin-top: 20px;
    }

    /* ==== Responsive ==== */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }
        .filter-form select {
            width: 100%;
        }
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .btn1 {
        background-color: #2563eb;
        color: white !important;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
    }

    .btn2 {
        background-color: #2563eb;
        color: white !important;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
    }

    .btn1:hover {
        background-color: #1d4ed8;
    }
</style>

<div class="page-header">
    <h1>Kelola User</h1>
    <a href="{{ route('admin.users.create') }}" class="btn">+ Tambah User</a>
</div>

{{-- Filter --}}
<form action="{{ route('admin.users.index') }}" method="GET" class="filter-form">
    <label for="kelas">Filter Kelas:</label>
    <select name="kelas" id="kelas">
        <option value="">Semua Kelas</option>
        @foreach($kelasOptions as $kelasOption)
            <option value="{{ $kelasOption }}" {{ request('kelas') == $kelasOption ? 'selected' : '' }}>
                {{ $kelasOption }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn2">Terapkan</button>
</form>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>NISN</th>
                <th>Kelas</th>
                <th>QR Code</th>
                <th style="width: 140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td style="text-transform: capitalize;">{{ $user->role ?? '-' }}</td>
                <td style="font-family: monospace;">{{ $user->nisn ?? '-' }}</td>
                <td>{{ $user->kelas ?? '-' }}</td>
                <td>
                    <div class="qr-container">
                        <div id="qrcode-{{ $user->id }}"></div>
                        <button data-user-id="{{ $user->id }}" class="btn-green download-btn">Unduh</button>
                    </div>
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn1" style="padding:6px 12px; font-size:13px;">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn1" style="background:#ef4444; padding:6px 12px; font-size:13px;">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:20px; color:#6b7280;">Tidak ada user.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">
    {{ $users->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @foreach ($users as $user)
            new QRCode(document.getElementById("qrcode-{{ $user->id }}"), {
                text: "{{ $user->nisn ?? $user->id }}",
                width: 100,
                height: 100,
                colorDark: "#000000",
                colorLight: "#ffffff",
            });
        @endforeach

        document.querySelectorAll('.download-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const qrDiv = document.getElementById('qrcode-' + userId);
                const img = qrDiv.querySelector('img') || qrDiv.querySelector('canvas');
                if (!img) return alert('QR Code belum siap');

                let dataUrl = img.tagName === 'IMG' ? img.src : img.toDataURL('image/png');
                const a = document.createElement('a');
                a.href = dataUrl;
                a.download = 'qrcode-user-' + userId + '.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        });
    });
</script>
@endsection
