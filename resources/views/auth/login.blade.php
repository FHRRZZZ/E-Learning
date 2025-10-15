<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: white;
            width: 380px;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in-out;
        }

        .login-container h2 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-size: 14px;
            color: #374151;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            outline: none;
            transition: all 0.2s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .remember {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #475569;
            margin-bottom: 1rem;
        }

        .remember input {
            margin-right: 8px;
        }

        .btn-login {
            width: 100%;
            background-color: #3b82f6;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #2563eb;
        }

        .forgot {
            text-align: right;
            margin-top: 0.5rem;
        }

        .forgot a {
            font-size: 14px;
            color: #2563eb;
            text-decoration: none;
        }

        .forgot a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        @if (session('status'))
            <p style="color: green; text-align:center; margin-bottom:10px;">
                {{ session('status') }}
            </p>
        @endif

        @if ($errors->any())
            <div style="color:red; font-size:14px; margin-bottom:1rem;">
                <ul style="list-style:none;">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="remember">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">Masuk</button>

            @if (Route::has('password.request'))
                <div class="forgot">
                    <a href="{{ route('password.request') }}">Lupa password?</a>
                </div>
            @endif
        </form>
    </div>

</body>
</html>
