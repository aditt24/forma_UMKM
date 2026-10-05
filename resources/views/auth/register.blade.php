<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | FORMA</title>

    <link rel="stylesheet" href="{{ asset('css/forma.css') }}">

    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 30px;
            background: #f4f1ec;
        }

        .box {
            width: min(700px, 94vw);
            padding: 35px;
            background: #fff;
            border-radius: 14px;
        }

        .brand {
            margin-bottom: 25px;
            text-align: center;
            font-size: 27px;
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="brand">FORMA</div>

        <h2>Registrasi Customer</h2>

        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.process') }}">
            @csrf

            <div class="form-grid">
                <div>
                    <label for="nama">Nama</label>
                    <input id="nama" name="nama" value="{{ old('nama') }}" required>
                </div>

                <div>
                    <label for="username">Username</label>
                    <input
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        required
                    >
                </div>

                <div>
                    <label for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                    >
                </div>

                <div>
                    <label for="no_telp">No. Telepon</label>
                    <input
                        id="no_telp"
                        name="no_telp"
                        value="{{ old('no_telp') }}"
                        required
                    >
                </div>

                <div class="full">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat">{{ old('alamat') }}</textarea>
                </div>

                <div>
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div>
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                    >
                </div>
            </div>

            <button
                type="submit"
                class="btn"
                style="width: 100%; margin-top: 20px"
            >
                Daftar
            </button>
        </form>

        <p style="text-align: center">
            <a href="{{ route('login') }}">Sudah punya akun? Login</a>
        </p>
    </div>
</body>
</html>
