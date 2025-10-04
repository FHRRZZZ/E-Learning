@extends('layouts.user')

@section('header', 'Detail Tugas')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">{{ $tugas->judul }}</h2>
    <p><strong>Kelas:</strong> {{ $tugas->kelas }}</p>
    <p class="mb-4">
        <a href="{{ Storage::url($tugas->file) }}" target="_blank" class="text-blue-600 underline">
            Download File Tugas
        </a>
    </p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <h3 class="text-xl font-semibold mb-2">Tugas yang sudah kamu kumpulkan:</h3>
    @if($tugasKumpul)
        <p>File tugas yang kamu upload:
            <a href="{{ Storage::url($tugasKumpul->file) }}" target="_blank" class="text-blue-600 underline">Download</a>
        </p>
        <p>Nilai: {{ $tugasKumpul->nilai ?? 'Belum dinilai' }}</p>
        <p>Komentar: {{ $tugasKumpul->komentar ?? '-' }}</p>
        <p class="mt-4 font-semibold">Kamu bisa mengupload ulang jika ingin mengganti file tugas.</p>
    @else
        <p>Belum ada tugas yang kamu kumpulkan.</p>
    @endif

    <form action="{{ route('user.tugas.upload', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="mt-6">
        @csrf
        <label for="file" class="block mb-2 font-medium">Upload / Update Tugas (PDF, DOC, DOCX, ZIP max 5MB):</label>
        <input type="file" name="file" id="file" class="border rounded px-3 py-2 w-full @error('file') border-red-500 @enderror" required>
        @error('file')
            <p class="text-red-600 mt-1">{{ $message }}</p>
        @enderror

        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Upload
        </button>
    </form>
</div>
@endsection
