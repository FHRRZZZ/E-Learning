@extends('layouts.admin')

@section('title', 'Daftar Presensi')

@section('content')
<style>
    /* Tombol utama */
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
        transition: background-color 0.2s ease-in-out;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        font-weight: 500;
    }
    .btn-blue {
        background-color: #2563eb;
        color: white;
    }
    .btn-blue:hover {
        background-color: #1d4ed8;
    }
    .btn-green {
        background-color: #16a34a;
        color: white;
    }
    .btn-green:hover {
        background-color: #15803d;
    }
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Container judul dan tombol */
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .header-container h1 {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
        color: #1e3a8a;
    }

    /* Form filter */
    .filter-form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }
    .filter-form label {
        font-weight: 600;
    }
    .filter-form select,
    .filter-form input[type="date"] {
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        padding: 6px 8px;
        font-size: 14px;
    }

    /* Tabel */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0px 2px 8px rgba(0,0,0,0.05);
    }
    thead {
        background-color: #f3f4f6;
    }
    thead th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #1e40af;
        border-bottom: 2px solid #e5e7eb;
    }
    tbody td {
        padding: 10px 12px;
        border-top: 1px solid #e5e7eb;
    }
    tbody tr:hover {
        background-color: #f9fafb;
    }
    .text-center {
        text-align: center;
    }
    .text-gray {
        color: #6b7280;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: flex-start;
        }
        .filter-form {
            flex-direction: column;
            align-items: flex-start;
        }
        .filter-form select,
        .filter-form input[type="date"],
        .filter-form button,
        .filter-form a {
            width: 100%;
        }
    }
</style>

<div class="header-container">
    <h1>Daftar Presensi</h1>
    <a href="{{ route('admin.absensi.create') }}" class="btn btn-blue">
        Tambah Presensi
    </a>
</div>

{{-- Filter Kelas dan Tanggal --}}
<form action="{{ route('admin.absensi.index') }}" method="GET" class="filter-form">
    <label for="kelas">Filter Kelas:</label>
    <select name="kelas" id="kelas">
        <option value="">Semua Kelas</option>
        @foreach ($kelasOptions as $kelasOption)
            <option value="{{ $kelasOption }}" {{ request('kelas') == $kelasOption ? 'selected' : '' }}>
                {{ $kelasOption }}
            </option>
        @endforeach
    </select>

    <label for="tanggal">Filter Tanggal:</label>
    <input type="date" id="tanggal" name="tanggal" value="{{ request('tanggal') }}" />

    <button type="submit" class="btn btn-blue">Filter</button>

    {{-- Tombol Export ke Excel --}}
    <a href="{{ route('admin.absensi.export', ['kelas' => request('kelas'), 'tanggal' => request('tanggal')]) }}" class="btn btn-green">
        Export Excel
    </a>
</form>

<div class="overflow-x-auto">
    <table>
        <thead>
            <tr>
                <th>Nama User</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Aksi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($presensis as $p)
                <tr>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->user->kelas ?? 'Belum ada' }}</td>
                    <td>{{ $p->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $p->jam }}</td>
                    <td class="capitalize">{{ $p->aksi }}</td>
                    <td>{{ $p->keterangan ?? 'Belum ada' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray">Belum ada data presensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 16px;">
    {{ $presensis->appends(request()->except('page'))->links() }}
</div>
@endsection
