<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FORMA</title>

    <link rel="stylesheet" href="{{ asset('css/forma.css') }}">

    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                linear-gradient(
                    rgba(247, 245, 241, 0.88),
                    rgba(247, 245, 241, 0.88)
                ),
                url("{{ asset('images/backgrounds/hero-bg-1.jpg') }}") center / cover;
        }

        .box {
            width: min(430px, 92vw);
            padding: 40px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.07);
        }

        .box .brand {
            margin-bottom: 30px;
            text-align: center;
            font-size: 29px;
        }

        .box button {
            width: 100%;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="brand">FORMA</div>

        <h2>Masuk ke FORMA</h2>

        @if ($errors->any())
            <div class="errors">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <label for="login">ID Akun / Username / Email</label>
            <input
                id="login"
                name="login"
                value="{{ old('login') }}"
                required
                autofocus
            >

            <br><br>

            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
            >

            <br><br>

            <button type="submit" class="btn">Masuk</button>
        </form>

        <p class="muted" style="text-align: center">
            Customer baru?
            <a href="{{ route('register') }}"><strong>Daftar di sini</strong></a>
        </p>

        <p style="text-align: center">
            <a href="{{ route('home') }}">← Beranda</a>
        </p>
    </div>
</body>
</html>
