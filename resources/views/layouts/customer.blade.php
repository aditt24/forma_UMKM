<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FORMA')</title>

    <link rel="stylesheet" href="{{ asset('css/forma.css') }}">

    <style>
        .customer-top {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 30px;
            min-height: 72px;
            padding: 10px 5%;
            background: rgba(255, 255, 255, 0.97);
            border-bottom: 1px solid #ebe7e0;
        }

        .customer-top .brand {
            flex: 0 0 auto;
            color: #171717;
            text-decoration: none;
        }

        .customer-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .customer-nav a {
            color: #393631;
            text-decoration: none;
            font-size: 14px;
            white-space: nowrap;
        }

        .customer-nav a:hover,
        .customer-nav a.active {
            font-weight: 700;
            color: #111;
        }

        .customer-search {
            flex: 1;
            max-width: 360px;
            margin-left: auto;
        }

        .customer-search input {
            padding: 10px 14px;
            background: #faf9f7;
        }

        .customer-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 12px;
            border: 1px solid #e1ddd6;
            border-radius: 999px;
            background: #fff;
            text-decoration: none;
            font-size: 13px;
        }

        .profile-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #222;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
        }

        @media (max-width: 980px) {
            .customer-top {
                flex-wrap: wrap;
                gap: 12px;
            }

            .customer-search {
                order: 3;
                width: 100%;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <header class="customer-top">
        <a href="{{ route('customer.dashboard') }}" class="brand">
            FORMA
        </a>

        <nav class="customer-nav">
            <a
                href="{{ route('customer.dashboard') }}"
                class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
            >
                Beranda
            </a>
            <a
                href="{{ route('customer.catalog') }}"
                class="{{ request()->routeIs('customer.catalog', 'customer.product') ? 'active' : '' }}"
            >
                Katalog
            </a>
            <a
                href="{{ route('customer.promotions') }}"
                class="{{ request()->routeIs('customer.promotions') ? 'active' : '' }}"
            >
                Promo
            </a>
            <a
                href="{{ route('customer.orders.index') }}"
                class="{{ request()->routeIs('customer.orders.*') ? 'active' : '' }}"
            >
                Pesanan
            </a>
        </nav>

        <form
            class="customer-search"
            method="GET"
            action="{{ route('customer.catalog') }}"
        >
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari produk atau gaya..."
                aria-label="Cari produk"
            >
        </form>

        <div class="customer-actions">
            <a class="cart-link" href="{{ route('customer.cart') }}">
                🛒 {{ array_sum(session('cart', [])) }}
            </a>

            <a
                class="profile-chip"
                href="{{ route('customer.profile') }}"
                title="{{ auth()->user()->nama }}"
            >
                {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn sm light">
                    Logout
                </button>
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
