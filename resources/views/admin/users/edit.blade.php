@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 24px;
    }

    h1 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 24px;
        color: #1e3a8a;
    }

    form.edit-form {
        background-color: #fff;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        transition: border 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
    }

    .error-msg {
        color: #dc2626;
        font-size: 13px;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 16px;
    }

    .btn-cancel,
    .btn-submit {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.2s ease-in-out;
    }

    .btn-cancel {
        border: 1px solid #ccc;
        color: #4a4a4a;
        background-color: transparent;
    }
    .btn-cancel:hover {
        background-color: #f0f0f0;
    }

    .btn-submit {
        background-color: #2563eb;
        color: white;
        border: none;
    }
    .btn-submit:hover {
        background-color: #1d4ed8;
    }
</style>

<div class="container">
    <h1>Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="edit-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
            </select>
            @error('role')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="nisn">NISN</label>
            <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $user->nisn) }}">
            @error('nisn')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="kelas">Kelas</label>
            <select name="kelas" id="kelas">
                @for ($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('kelas', $user->kelas) == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
            @error('kelas')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">Simpan</button>
        </div>
    </form>
</div>
@endsection
