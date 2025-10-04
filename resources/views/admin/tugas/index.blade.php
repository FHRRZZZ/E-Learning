@extends('layouts.admin')

@section('title', 'Daftar Tugas')

@section('content')
<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    .btn-danger:hover {
        background-color: #a71d2a;
    }
    .filter-form {
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .filter-form label {
        font-weight: 600;
    }
    .filter-form select {
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        border-radius: 6px;
        overflow: hidden;
    }
    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    table thead {
        background-color: #f7f7f7;
    }
    table tbody tr:hover {
        background-color: #f0f8ff;
    }
    .empty-row td {
        text-align: center;
        color: #888;
        padding: 20px;
    }
</style>

<div class="header">
    <h1>Daftar Tugas</h1>
    <a href="{{ route('admin.tugas.create') }}" class="btn-primary">Tambah Tugas</a>
</div>

<form action="{{ route('admin.tugas.index') }}" method="GET" class="filter-form">
    <label for="kelas">Filter Kelas:</label>
    <select name="kelas" id="kelas">
        <option value="">Semua Kelas</option>
        @foreach ($kelasOptions as $kelasOption)
            <option value="{{ $kelasOption }}" {{ request('kelas') == $kelasOption ? 'selected' : '' }}>
                {{ $kelasOption }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary">Filter</button>
</form>

<table>
    <thead>
        <tr>
            <th>Judul</th>
            <th>Kelas</th>
            <th>File</th>
            <th>Tanggal Upload</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tugas as $item)
            <tr>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->kelas }}</td>
                <td><a href="{{ route('tugas_files.show', basename($item->file)) }}" target="_blank">Lihat File</a></td>
                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                <td>
                    <form action="{{ route('admin.tugas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr class="empty-row">
                <td colspan="5">Belum ada tugas.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 16px;">
    {{ $tugas->appends(request()->except('page'))->links() }}
</div>
@endsection
