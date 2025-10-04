@extends('layouts.user')

@section('title', 'Profile User')

@section('content')
<style>
    .profile-card {
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        font-family: Arial, sans-serif;
    }

    .profile-info {
        flex: 1;
        padding-right: 30px;
    }

    .profile-info h1 {
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #2563eb;
    }

    .profile-info p {
        font-size: 16px;
        margin-bottom: 12px;
        line-height: 1.5;
    }

    .profile-info p strong {
        color: #1e40af;
        width: 100px;
        display: inline-block;
    }

    .profile-photo {
        width: 180px;
        height: 220px;
        border-radius: 8px;
        border: 2px solid #2563eb;
        object-fit: cover;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    /* Responsive */
    @media(max-width: 768px) {
        .profile-card {
            flex-direction: column;
            text-align: center;
        }
        .profile-info {
            padding-right: 0;
            margin-bottom: 20px;
        }
        .profile-photo {
            width: 150px;
            height: 180px;
        }
    }
</style>

<div class="profile-card">
    <div class="profile-info">
        <h1>Profile User</h1>
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>NISN:</strong> {{ $user->nisn }}</p>
        <p><strong>Kelas:</strong> {{ $user->kelas ?? '-' }}</p>
    </div>

    @if($user->profile_photo_url)
        <img src="{{ $user->profile_photo_url }}" alt="Foto Profil" class="profile-photo">
    @else
        <img src="https://via.placeholder.com/180x220.png?text=No+Photo" alt="Foto Profil" class="profile-photo">
    @endif
</div>
@endsection
