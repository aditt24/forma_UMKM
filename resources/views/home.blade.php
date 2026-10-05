<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FORMA Fashion</title>

    <link rel="stylesheet" href="{{ asset('css/forma.css') }}">

    <style>
        body {
            margin: 0;
            background: #f8f6f2;
            color: #171717;
        }

        /* =========================
           NAVBAR
        ========================== */

        .home-nav {
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 6%;

            background: #ffffff;

            border-bottom: 1px solid #eeeae4;
        }

        .home-nav .brand {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 6px;
        }

        .home-nav .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* =========================
           CONTAINER
        ========================== */

        .home-container {
            width: calc(100% - 36px);
            max-width: 1200px;

            margin: 0 auto;
        }

        /* =========================
           HERO
        ========================== */

        .hero {
            margin-top: 28px;

            min-height: 455px;

            display: grid;
            grid-template-columns: 1.03fr .97fr;

            background: #ede8df;

            border: 1px solid #e8e2da;
            border-radius: 16px;

            overflow: hidden;
        }

        .hero-copy {
            padding: 64px 58px;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-eyebrow {
            margin-bottom: 14px;

            font-size: 11px;
            font-weight: 700;

            letter-spacing: 3.5px;

            color: #333333;
        }

        .hero h1 {
            margin: 0;

            font-family: Georgia, serif;

            font-size: clamp(
                42px,
                4.7vw,
                64px
            );

            font-weight: 500;
            line-height: 1.04;

            color: #171717;
        }

        .hero-description {
            max-width: 470px;

            margin: 20px 0 27px;

            font-size: 15px;
            line-height: 1.7;

            color: #69645e;
        }

        .hero-actions {
            display: flex;
            align-items: center;

            gap: 10px;
        }

        .hero-image {
            position: relative;

            min-height: 455px;

            background: #d9d2c8;

            overflow: hidden;
        }

        .hero-image img {
            width: 100%;
            height: 100%;

            position: absolute;
            inset: 0;

            display: block;

            object-fit: cover;
            object-position: center;
        }

        /* =========================
           BENEFITS
        ========================== */

        .benefits {
            margin-top: 20px;

            display: grid;
            grid-template-columns: repeat(4, 1fr);

            background: #ffffff;

            border: 1px solid #e9e5df;
            border-radius: 14px;

            overflow: hidden;
        }

        .benefit {
            padding: 21px 22px;

            display: flex;
            align-items: center;

            gap: 13px;

            border-right: 1px solid #eeeae4;
        }

        .benefit:last-child {
            border-right: none;
        }

        .benefit-mark {
            width: 39px;
            height: 39px;

            flex: 0 0 39px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f2eee8;

            border-radius: 50%;

            font-size: 11px;
            font-weight: 700;

            color: #242424;
        }

        .benefit strong {
            display: block;

            margin-bottom: 3px;

            font-size: 13px;
        }

        .benefit span {
            display: block;

            font-size: 12px;
            line-height: 1.45;

            color: #77726c;
        }

        /* =========================
           PRODUCT SECTION
        ========================== */

        .product-section {
            margin: 48px 0 54px;
        }

        .section-head {
            margin-bottom: 20px;

            display: flex;
            align-items: flex-end;
            justify-content: space-between;

            gap: 20px;
        }

        .section-head h2 {
            margin: 0 0 5px;

            font-size: 27px;
        }

        .section-head p {
            margin: 0;

            font-size: 14px;

            color: #77726c;
        }

        .section-action {
            color: #252525;

            font-size: 13px;
            font-weight: 600;

            text-decoration: none;
        }

        .section-action:hover {
            text-decoration: underline;
        }

        /* =========================
           PRODUCT CARD
        ========================== */

        .product-link {
            color: inherit;
            text-decoration: none;
        }

        .product-card {
            height: 100%;

            overflow: hidden;

            background: #ffffff;

            border: 1px solid #e8e3dc;
            border-radius: 14px;

            transition:
                transform .18s ease,
                box-shadow .18s ease;
        }

        .product-card img {
            width: 100%;

            aspect-ratio: 1 / 1;

            display: block;

            object-fit: cover;

            background: #f1eee9;
        }

        .product-card .body {
            padding: 15px 16px 17px;
        }

        .product-meta {
            margin-bottom: 5px;

            font-size: 11px;

            color: #8a857f;
        }

        .product-card strong {
            display: block;

            margin-bottom: 8px;

            font-size: 15px;
        }

        .product-card .price {
            font-size: 17px;
            font-weight: 700;

            color: #171717;
        }

        .product-link:hover .product-card {
            transform: translateY(-4px);

            box-shadow:
                0 14px 30px
                rgba(0, 0, 0, .07);
        }

        /* =========================
           CTA BAWAH
        ========================== */

        .home-cta {
            margin-bottom: 52px;

            padding: 34px 40px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 30px;

            background: #202020;
            color: #ffffff;

            border-radius: 15px;
        }

        .home-cta h3 {
            margin: 0 0 7px;

            font-family: Georgia, serif;

            font-size: 26px;
            font-weight: 500;

            line-height: 1.2;
        }

        .home-cta p {
            max-width: 540px;

            margin: 0;

            font-size: 13px;
            line-height: 1.6;

            color: #c8c8c8;
        }

        .home-cta .btn {
            flex-shrink: 0;

            background: #ffffff;
            color: #171717;
        }

        /* =========================
           FOOTER
        ========================== */

        .home-footer {
            padding: 29px 6%;

            background: #ffffff;

            border-top: 1px solid #eeeae4;
        }

        .footer-inner {
            max-width: 1200px;

            margin: auto;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;
        }

        .footer-brand {
            font-size: 14px;
            font-weight: 800;

            letter-spacing: 5px;
        }

        .footer-text {
            font-size: 12px;

            color: #8a857f;
        }

        /* =========================
           TABLET
        ========================== */

        @media (max-width: 950px) {
            .hero {
                grid-template-columns: 1fr 1fr;
            }

            .hero-copy {
                padding: 50px 38px;
            }

            .benefits {
                grid-template-columns: repeat(2, 1fr);
            }

            .benefit:nth-child(2) {
                border-right: none;
            }

            .benefit:nth-child(1),
            .benefit:nth-child(2) {
                border-bottom: 1px solid #eeeae4;
            }
        }

        /* =========================
           MOBILE
        ========================== */

        @media (max-width: 700px) {
            .home-nav {
                height: auto;

                padding: 15px 18px;
            }

            .home-nav .brand {
                font-size: 14px;

                letter-spacing: 4px;
            }

            .home-nav .nav-actions {
                gap: 6px;
            }

            .home-container {
                width: calc(100% - 30px);
            }

            .hero {
                margin-top: 16px;

                grid-template-columns: 1fr;

                min-height: auto;
            }

            .hero-copy {
                padding: 44px 27px;
            }

            .hero h1 {
                font-size: 42px;
            }

            .hero-image {
                min-height: 290px;
            }

            .benefits {
                grid-template-columns: 1fr;
            }

            .benefit {
                border-right: none;
                border-bottom: 1px solid #eeeae4;
            }

            .benefit:last-child {
                border-bottom: none;
            }

            .product-section {
                margin-top: 38px;
            }

            .section-head {
                align-items: flex-start;
                flex-direction: column;

                gap: 8px;
            }

            .home-cta {
                padding: 28px 26px;

                align-items: flex-start;
                flex-direction: column;
            }

            .footer-inner {
                align-items: flex-start;
                flex-direction: column;

                gap: 7px;
            }
        }
    </style>
</head>


<body>

{{-- =========================
     NAVBAR
========================== --}}

<header class="home-nav">

    <div class="brand">
        FORMA
    </div>

    <div class="nav-actions">

        <a
            class="btn light"
            href="{{ route('login') }}"
        >
            Login
        </a>

        <a
            class="btn"
            href="{{ route('register') }}"
        >
            Register Customer
        </a>

    </div>

</header>


<main class="home-container">


    {{-- =========================
         HERO
    ========================== --}}

    <section class="hero">

        <div class="hero-copy">

            <div class="hero-eyebrow">
                EVERYDAY ESSENTIALS
            </div>

            <h1>
                Gaya sehari-hari,
                <br>
                dibuat lebih mudah.
            </h1>

            <p class="hero-description">
                Pilihan pakaian simpel dan nyaman
                untuk dipakai setiap hari.
            </p>

            <div class="hero-actions">

                <a
                    class="btn"
                    href="{{ route('login') }}"
                >
                    Lihat Koleksi →
                </a>

            </div>

        </div>


        <div class="hero-image">

            <img
                src="{{ asset('images/backgrounds/hero-home.png') }}"
                alt="Koleksi pakaian FORMA"
            >

        </div>

    </section>


    {{-- =========================
         BENEFITS
    ========================== --}}

    <section class="benefits">

        <div class="benefit">

            <div class="benefit-mark">
                01
            </div>

            <div>

                <strong>
                    Pilihan Produk
                </strong>

                <span>
                    Koleksi pakaian untuk kebutuhan
                    sehari-hari.
                </span>

            </div>

        </div>


        <div class="benefit">

            <div class="benefit-mark">
                02
            </div>

            <div>

                <strong>
                    Promo
                </strong>

                <span>
                    Nikmati promo yang sedang tersedia.
                </span>

            </div>

        </div>


        <div class="benefit">

            <div class="benefit-mark">
                03
            </div>

            <div>

                <strong>
                    Poin Member
                </strong>

                <span>
                    Kumpulkan poin setiap kali berbelanja.
                </span>

            </div>

        </div>


        <div class="benefit">

            <div class="benefit-mark">
                04
            </div>

            <div>

                <strong>
                    Belanja Fleksibel
                </strong>

                <span>
                    Belanja online atau langsung
                    melalui kasir.
                </span>

            </div>

        </div>

    </section>


    {{-- =========================
         PRODUCTS
    ========================== --}}

    <section class="product-section">

        <div class="section-head">

            <div>

                <h2>
                    Produk Pilihan
                </h2>

                <p>
                    Beberapa koleksi favorit dari FORMA.
                </p>

            </div>


            <a
                class="section-action"
                href="{{ route('login') }}"
            >
                Lihat Koleksi →
            </a>

        </div>


        <div class="product-grid">

            @forelse($products as $product)

                <a
                    class="product-link"
                    href="{{ route('login') }}"
                >

                    <div class="product-card">

                        <img
                            src="{{ asset(
                                'images/products/'.
                                (
                                    $product->gambar_produk
                                    ?: 'basic-tshirt.jpg'
                                )
                            ) }}"
                            alt="{{ $product->nama_produk }}"
                        >

                        <div class="body">

                            <div class="product-meta">
                                FORMA
                            </div>

                            <strong>
                                {{ $product->nama_produk }}
                            </strong>

                            <div class="price">
                                Rp{{ number_format(
                                    $product->harga_jual,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </div>

                        </div>

                    </div>

                </a>

            @empty

                <div class="card">
                    Belum ada produk yang tersedia.
                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================
         CTA
    ========================== --}}

    <section class="home-cta">

        <div>

            <h3>
                Lengkapi gaya sehari-harimu.
            </h3>

            <p>
                Lihat koleksi FORMA dan temukan
                pilihan yang sesuai untuk kebutuhanmu.
            </p>

        </div>


        <a
            class="btn"
            href="{{ route('login') }}"
        >
            Lihat Koleksi
        </a>

    </section>

</main>


{{-- =========================
     FOOTER
========================== --}}

<footer class="home-footer">

    <div class="footer-inner">

        <div class="footer-brand">
            FORMA
        </div>

        <div class="footer-text">
            Everyday fashion, made simple.
        </div>

    </div>

</footer>


</body>
</html>