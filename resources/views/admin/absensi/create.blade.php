@extends('layouts.admin')

@section('title', 'Tambah Presensi')

@section('content')
<style>
    .btn {
        background-color: #2563eb;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
        transition: background-color 0.2s ease-in-out;
        text-decoration: none;
        display: inline-block;
    }
    .btn:hover {
        background-color: #1d4ed8;
    }
    .btn-outline {
        background-color: white;
        border: 1px solid #d1d5db;
        color: #374151;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s ease-in-out;
    }
    .btn-outline:hover {
        background-color: #f3f4f6;
    }
    .form-input, .form-select, textarea {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 8px 12px;
        outline: none;
        transition: box-shadow 0.2s ease-in-out;
    }
    .form-input:focus, .form-select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }
    .form-group {
        margin-bottom: 16px;
    }
    .flex-end {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
</style>

<div style="max-width:600px; margin:auto;">
    <h1 style="font-size:28px; font-weight:700; margin-bottom:24px;">Tambah Presensi</h1>

    <form action="{{ route('admin.absensi.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <div class="form-group">
            <label for="kelas" class="font-semibold mb-1 block">Pilih Kelas</label>
            <select name="kelas" id="kelas" required class="form-select">
                <option value="">-- Pilih Kelas --</option>
                @foreach ($kelasOptions as $kelas)
                    <option value="{{ $kelas }}" {{ old('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                @endforeach
            </select>
            @error('kelas')<p style="color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="user_id" class="font-semibold mb-1 block">Nama User</label>
            <select name="user_id" id="user_id" required class="form-select">
                <option value="">-- Pilih User --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" data-kelas="{{ $user->kelas }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} (Kelas {{ $user->kelas }})
                    </option>
                @endforeach
            </select>
            @error('user_id')<p style="color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="tanggal" class="font-semibold mb-1 block">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="form-input" />
            @error('tanggal')<p style="color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="jam" class="font-semibold mb-1 block">Jam</label>
            <input type="time" name="jam" id="jam" value="{{ old('jam', date('H:i')) }}" required class="form-input" />
            @error('jam')<p style="color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="aksi" class="font-semibold mb-1 block">Aksi</label>
            <select name="aksi" id="aksi" required class="form-select">
                <option value="hadir" {{ old('aksi') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="sakit" {{ old('aksi') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="izin" {{ old('aksi') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="alpa" {{ old('aksi') == 'alpa' ? 'selected' : '' }}>Alpa</option>
            </select>
            @error('aksi')<p style="color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="keterangan" class="font-semibold mb-1 block">Keterangan (opsional)</label>
            <textarea name="keterangan" id="keterangan" rows="3" class="form-input">{{ old('keterangan') }}</textarea>
            @error('keterangan')<p style="color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
        </div>

        <div class="flex-end">
            <a href="{{ route('admin.absensi.index') }}" class="btn-outline">Batal</a>
            <button type="submit" class="btn">Simpan</button>
        </div>
    </form>
</div>

<script>
    const kelasSelect = document.getElementById('kelas');
    const userSelect = document.getElementById('user_id');

    function filterUsersByKelas(selectedKelas) {
        const options = userSelect.querySelectorAll('option[data-kelas]');
        userSelect.value = "";
        options.forEach(option => {
            option.style.display = (selectedKelas === "" || option.getAttribute('data-kelas') === selectedKelas) ? '' : 'none';
        });
    }

    kelasSelect.addEventListener('change', (e) => filterUsersByKelas(e.target.value));

    document.addEventListener('DOMContentLoaded', () => filterUsersByKelas(kelasSelect.value));
</script>
@endsection
