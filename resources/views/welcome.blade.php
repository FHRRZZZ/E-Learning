<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Selamat Datang - Aplikasi Saya</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(90deg, #3b82f6, #4f46e5);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
        }
        h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        p {
            font-size: 1.125rem;
            max-width: 600px;
            margin: 0 auto 2rem;
            text-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }
        .btn {
            display: inline-block;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            margin: 0 0.5rem;
        }
        .btn-primary {
            background-color: white;
            color: #3b82f6;
        }
        .btn-primary:hover {
            background-color: #e2e8f0;
        }
        .btn-secondary {
            background-color: transparent;
            border: 2px solid white;
            color: white;
        }
        .btn-secondary:hover {
            background-color: white;
            color: #3b82f6;
        }
        footer {
            margin-top: 5rem;
            font-size: 0.875rem;
            opacity: 0.7;
        }
        @media (max-width: 480px) {
            h1 {
                font-size: 2.25rem;
            }
            p {
                font-size: 1rem;
            }
            .btn {
                padding: 0.6rem 1.5rem;
                margin: 0.25rem;
            }
        }
    </style>
</head>
<body>

    <h1>Selamat Datang di Aplikasi E-learning</h1>
    <p>Aplikasi yang memudahkan pengelolaan absensi dan pembelajaran dengan mudah dan cepat.</p>

    <div>
        <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-secondary">Daftar</a>
    </div>

    <footer>
        &copy; {{ date('Y') }} Aplikasi E-learning. All rights reserved.
    </footer>

</body>
</html>
