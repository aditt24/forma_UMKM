<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FORMA Kasir')</title>

    <link rel="stylesheet" href="{{ asset('css/forma.css') }}">

    <style>
        .kasir-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            padding: 0 4%;
            background: #fff;
            border-bottom: 1px solid #e9e5df;
        }

        .kasir-links {
            display: flex;
            align-items: center;
            gap: 12px;
        }
    </style>
</head>
<body>
    <header class="kasir-top">
        <div class="brand">FORMA</div>

        <div class="kasir-links">
            <a class="btn sm light" href="{{ route('kasir.dashboard') }}">
                Dashboard
            </a>
            <a class="btn sm light" href="{{ route('kasir.pos') }}">
                POS
            </a>
            <a class="btn sm light" href="{{ route('kasir.history') }}">
                Riwayat
            </a>

            <span>
                <strong>{{ auth()->user()->nama }}</strong>
                · {{ auth()->user()->id_akun }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn sm">Logout</button>
            </form>
        </div>
    </header>

    <main class="page">
        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
