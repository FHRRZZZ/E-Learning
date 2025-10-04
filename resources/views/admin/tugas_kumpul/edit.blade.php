@extends('layouts.admin')

@section('title', 'Edit Nilai Tugas')

@section('content')
<style>
    .container {
        max-width: 500px;
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

    input[type="text"], textarea {
        width: 100%;
        padding: 8px 12px;
        margin-bottom: 16px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }

    textarea {
        resize: vertical;
    }

    .btn-submit {
        background-color: #2563eb;
        color: white;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #1d4ed8;
    }

    .btn-cancel {
        margin-left: 16px;
        color: #6b7280;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-cancel:hover {
        text-decoration: underline;
    }

    .error-text {
        color: #b91c1c;
        font-size: 13px;
        margin-top: 4px;
    }
</style>

<div class="container">
    <h1>Edit Nilai dan Komentar Tugas</h1>

    <form action="{{ route('admin.tugas_kumpul.update', $tugasKumpul->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="nilai">Nilai</label>
            <input type="text" name="nilai" id="nilai" value="{{ old('nilai', $tugasKumpul->nilai) }}" placeholder="Contoh: A, 90, 85">
            @error('nilai')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="komentar">Komentar</label>
            <textarea name="komentar" id="komentar" rows="4">{{ old('komentar', $tugasKumpul->komentar) }}</textarea>
            @error('komentar')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Simpan</button>
        <a href="{{ route('admin.tugas_kumpul.index') }}" class="btn-cancel">Batal</a>
    </form>
</div>
@endsection
