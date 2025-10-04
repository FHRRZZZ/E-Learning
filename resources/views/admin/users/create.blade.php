@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<style>
    form {
        max-width: 600px;
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        margin-top: 16px;
        color: #1e40af; /* biru gelap */
    }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
        width: 100%;
        padding: 10px 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
    }
    .btn-submit {
        margin-top: 24px;
        background-color: #2563eb;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: background-color 0.3s ease;
    }
    .btn-submit:hover {
        background-color: #1e40af;
    }
    .error-message {
        color: #dc2626;
        font-size: 13px;
        margin-top: 4px;
    }
</style>

<h1 style="font-size: 24px; font-weight: bold; margin-bottom: 24px; color:#1e40af;">Tambah User Baru</h1>

<form action="{{ route('admin.users.store') }}" method="POST" novalidate>
    @csrf

    <label for="name">Nama Lengkap</label>
    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
    @error('name')
        <div class="error-message">{{ $message }}</div>
    @enderror

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
    @error('email')
        <div class="error-message">{{ $message }}</div>
    @enderror

    <label for="role">Role</label>
    <select id="role" name="role" required>
        <option value="">Pilih Role</option>
        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    @error('role')
        <div class="error-message">{{ $message }}</div>
    @enderror

    <label for="nisn">NISN</label>
    <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}">
    @error('nisn')
        <div class="error-message">{{ $message }}</div>
    @enderror

    <label for="kelas">Kelas</label>
    <select id="kelas" name="kelas">
        <option value="">Pilih Kelas</option>
        @for ($i = 1; $i <= 6; $i++)
            <option value="{{ $i }}" {{ old('kelas') == $i ? 'selected' : '' }}>{{ $i }}</option>
        @endfor
    </select>
    @error('kelas')
        <div class="error-message">{{ $message }}</div>
    @enderror

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    @error('password')
        <div class="error-message">{{ $message }}</div>
    @enderror

    <label for="password_confirmation">Konfirmasi Password</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>

    <button type="submit" class="btn-submit">Simpan User</button>
</form>
@endsection
