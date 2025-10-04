<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'User Dashboard')</title>
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
      color: #ffffff !important;
    }

    /* === Profile Photo Bulat === */
    .profile-photo {
      display: block;
      width: 120px;          /* lebih kecil */
      height: 120px;
      margin: 0 auto 20px;   /* center + jarak bawah */
      border-radius: 50%;    /* bulat */
      border: 3px solid rgba(255,255,255,0.9);
      object-fit: cover;
      box-shadow: 0 4px 8px rgba(0,0,0,0.25);
      background-color: #fff;
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
      color: rgba(255, 255, 255, 0.95) !important;
      font-weight: 500;
      background-color: rgba(255, 255, 255, 0.06);
      transition: all 0.3s ease;
    }

    nav a:hover,
    nav a:focus {
      background-color: #ffffff !important;
      color: #1e40af !important;
      transform: translateX(5px);
    }

    /* Logout button */
    aside form button {
      width: 100%;
      padding: 12px;
      border: none;
      background-color: rgba(255, 255, 255, 0.12);
      color: #ffffff !important;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    aside form button:hover {
      background-color: #ef4444 !important;
      color: #ffffff !important;
    }

    /* Main content */
    main {
      flex: 1;
      padding: 25px;
      background-color: #f9fafb;
    }

    header h1 {
      font-size: 24px;
      font-weight: bold;
      color: #1e3a8a;
      margin-bottom: 20px;
    }

    /* Responsive ukuran foto */
    @media (max-width: 768px) {
      .profile-photo {
        width: 90px;
        height: 90px;
      }
    }
  </style>
</head>
<body>

  {{-- Sidebar --}}
  <aside>
    <div>
      <h2>User Panel</h2>

      {{-- Foto profil selalu pakai class profile-photo --}}
        @if(auth()->user() && auth()->user()->profile_photo_url)
            <a href="{{ route('profile.index') }}">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Foto Profil" class="profile-photo">
            </a>
        @else
            <img src="https://via.placeholder.com/120.png?text=No+Photo" alt="No Photo" class="profile-photo">
        @endif


      <nav>
        <a href="{{ route('user.dashboard') }}">Dashboard</a>
        <a href="{{ route('user.presensi.index') }}">Presensi Saya</a>
        <a href="{{ route('user.tugas.index') }}">Tugas Saya</a>
      </nav>
    </div>

    {{-- Logout di bawah --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">Logout</button>
    </form>
  </aside>

  {{-- Main content --}}
  <main>
    <header>
      <h1>@yield('header')</h1>
    </header>
    @yield('content')
  </main>

</body>
</html>
