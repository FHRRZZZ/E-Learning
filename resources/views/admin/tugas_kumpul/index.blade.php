@extends('layouts.admin')

@section('title', 'Daftar Tugas Kumpul')

@section('content')
<style>
    /* Judul */
    h1 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #1f2937;
    }

    /* Filter dropdown */
    select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 12px;
        background-color: white;
        transition: border-color 0.3s ease;
    }
    select:hover {
        border-color: #4f46e5;
    }

    /* Tombol Export */
    .btn-export {
        background-color: #10b981;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 16px;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }
    .btn-export:hover {
        background-color: #0f766e;
    }

    /* Tombol Nilai */
    .btn-nilai {
        background-color: #4f46e5;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    .btn-nilai:hover {
        background-color: #3730a3;
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    thead {
        background-color: #f3f4f6;
    }
    thead th {
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
    }
    tbody td {
        padding: 12px 16px;
        font-size: 14px;
        color: #111827;
        border-top: 1px solid #e5e7eb;
    }
    tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Link Lihat File */
    .link-file {
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
    }
    .link-file:hover {
        text-decoration: underline;
    }

    /* Pagination */
    .pagination {
        margin-top: 20px;
    }
</style>

<h1>Daftar Tugas yang Sudah Dikumpulkan</h1>

<form method="GET" action="{{ route('admin.tugas_kumpul.index') }}">
    <select name="kelas" onchange="this.form.submit()" aria-label="Filter berdasarkan kelas">
        <option value="">-- Filter berdasarkan kelas --</option>
        @foreach ($kelasOptions as $kelas)
            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                Kelas {{ $kelas }}
            </option>
        @endforeach
    </select>
</form>

<a href="{{ route('admin.tugas_kumpul.export', ['kelas' => request('kelas')]) }}" class="btn-export">
    Export ke Excel
</a>

<div style="overflow-x:auto;">
    <table>
        <thead>
            <tr>
                <th>Judul Tugas</th>
                <th>Nama Siswa</th>
                <th>File Tugas</th>
                <th>Nilai</th>
                <th>Komentar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kumpuls as $kumpul)
            <tr>
                <td>{{ $kumpul->tugas->judul }}</td>
                <td>{{ $kumpul->user->name }}</td>
                <td>
                    <a href="{{ route('tugas_files.show', basename($kumpul->file)) }}" target="_blank" class="link-file">
                        Lihat File
                    </a>
                </td>
                <td>{{ $kumpul->nilai ?? '-' }}</td>
                <td>{{ $kumpul->komentar ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.tugas_kumpul.edit', $kumpul->id) }}" class="btn-nilai">Nilai</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:20px; color:#6b7280;">Belum ada tugas yang dikumpulkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">
    {{ $kumpuls->links() }}
</div>
@endsection
