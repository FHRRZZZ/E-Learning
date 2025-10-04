@extends('layouts.admin')

@section('title', 'Upload Tugas')

@section('content')
<style>
    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 24px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }

    h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1e3a8a;
    }

    label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
        color: #111827;
    }

    input[type="text"], select, input[type="file"] {
        width: 100%;
        padding: 8px 12px;
        margin-bottom: 16px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }

    .btn-upload {
        background-color: #2563eb;
        color: white;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .btn-upload:hover {
        background-color: #1d4ed8;
    }

    .error-box {
        background-color: #fee2e2;
        color: #b91c1c;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .error-box ul {
        margin: 0;
        padding-left: 20px;
    }
</style>

<div class="container">
    <h1>Upload Tugas Baru</h1>

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tugas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="kelas">Pilih Kelas</label>
        <select name="kelas" id="kelas" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach ($kelasOptions as $kelas)
                <option value="{{ $kelas }}" {{ old('kelas') == $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
            @endforeach
        </select>

        <label for="judul">Judul Tugas</label>
        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required>

        <label for="file">File Tugas (PDF, DOC, ZIP max 5MB)</label>
        <input type="file" name="file" id="file" required>

        <button type="submit" class="btn-upload">Upload</button>
    </form>
</div>
@endsection
