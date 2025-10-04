<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Admin Dashboard')</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    aside {
      width: 250px;
      background: linear-gradient(180deg, #2563eb, #1e40af);
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 25px 15px;
      box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
    }

    aside h2 {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 20px;
      text-align: center;
      letter-spacing: 1px;
    }

    /* === Profile Photo & Initial === */
    .profile-photo {
      display: block;
      width: 120px;
      height: 120px;
      margin: 0 auto 20px;
      border-radius: 50%;
      border: 3px solid #fff;
      object-fit: cover;
      box-shadow: 0 4px 8px rgba(0,0,0,0.25);
      background-color: #fff;
    }
    .profile-initial {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 120px;
      height: 120px;
      margin: 0 auto 20px;
      border-radius: 50%;
      border: 3px solid #fff;
      background-color: #eaf1ff; /* biru muda */
      color: #2563eb;
      font-size: 48px;
      font-weight: bold;
      box-shadow: 0 4px 8px rgba(0,0,0,0.25);
    }

    nav {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    nav a {
      padding: 12px;
      border-radius: 8px;
      text-decoration: none;
      color: white;
      font-weight: 500;
      background-color: rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
    }

    nav a:hover {
      background-color: white;
      color: #1e40af;
      transform: translateX(5px);
    }

    /* Logout button */
    aside form button {
      width: 100%;
      padding: 12px;
      border: none;
      background-color: rgba(255, 255, 255, 0.2);
      color: white;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    aside form button:hover {
      background-color: #ef4444;
      color: white;
    }

    /* Main content */
    main {
      flex: 1;
      padding: 25px;
      background-color: #f9fafb;
    }

    /* Responsive foto */
    @media (max-width: 768px) {
      .profile-photo,
      .profile-initial {
        width: 90px;
        height: 90px;
        font-size: 36px;
      }
    }
  </style>
</head>
<body>

  {{-- Sidebar --}}
  <aside>
    <div>
      <h2>Admin Panel</h2>

      {{-- Foto profil admin, fallback inisial --}}
      @php
        $user = auth()->user();
        $initial = strtoupper(substr($user->name ?? 'A', 0, 1));
      @endphp

      @if($user && $user->profile_photo_path)
          <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
               alt="Foto Profil"
               class="profile-photo">
      @else
          <div class="profile-initial">{{ $initial }}</div>
      @endif

      {{-- Nama admin --}}
      <p style="margin-top:10px; font-weight:bold; text-align:center;">
        {{ $user->name ?? 'Admin' }}
      </p>

      <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.users.index') }}">Kelola User</a>
        <a href="{{ route('admin.absensi.index') }}">Absensi</a>
        <a href="{{ route('admin.absensi.scan') }}">Scan</a>
        <a href="{{ route('admin.tugas.index') }}">Tugas</a>
        <a href="{{ route('admin.tugas_kumpul.index') }}">Tugas Kumpul</a>
      </nav>
    </div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">Logout</button>
    </form>
  </aside>

  {{-- Main content --}}
  <main>
    @yield('content')
  </main>

</body>
</html>
