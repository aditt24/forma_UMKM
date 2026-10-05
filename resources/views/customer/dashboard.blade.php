@extends('layouts.customer')

@section('title', 'Beranda Customer')

@section('content')

<style>
    /* =========================================
       WELCOME
    ========================================== */

    .customer-welcome {
        padding: 38px 42px;
        margin-bottom: 22px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 24px;

        background: #eee9e1;

        border: 1px solid #e7e1d9;
        border-radius: 16px;
    }

    .customer-welcome-copy {
        max-width: 650px;
    }

    .customer-welcome-label {
        display: block;

        margin-bottom: 8px;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;

        color: #66615b;
    }

    .customer-welcome h1 {
        margin: 0 0 8px;

        font-family: Georgia, serif;

        font-size: clamp(
            30px,
            4vw,
            42px
        );

        font-weight: 500;
        line-height: 1.12;

        color: #171717;
    }

    .customer-welcome p {
        margin: 0;

        max-width: 540px;

        font-size: 14px;
        line-height: 1.6;

        color: #716b65;
    }

    .customer-welcome-action {
        flex-shrink: 0;
    }

    /* =========================================
       SUMMARY
    ========================================== */

    .customer-summary {
        display: grid;
        grid-template-columns: 1fr 1fr;

        gap: 18px;

        margin-bottom: 42px;
    }

    .summary-card {
        min-height: 145px;

        padding: 25px 27px;

        display: flex;
        flex-direction: column;
        justify-content: center;

        background: #ffffff;

        border: 1px solid #e9e4dd;
        border-radius: 14px;
    }

    .summary-label {
        display: block;

        margin-bottom: 10px;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;

        color: #85807a;
    }

    .summary-value {
        margin-bottom: 5px;

        font-size: 30px;
        font-weight: 700;

        color: #171717;
    }

    .summary-description {
        font-size: 13px;
        line-height: 1.5;

        color: #77726c;
    }

    /* =========================================
       BEST SELLER
    ========================================== */

    .best-seller-section {
        margin-top: 4px;
    }

    .best-seller-head {
        margin-bottom: 20px;

        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        gap: 20px;
    }

    .best-seller-label {
        display: block;

        margin-bottom: 6px;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;

        color: #77726c;
    }

    .best-seller-head h2 {
        margin: 0;

        font-size: 27px;
    }

    .best-seller-head p {
        margin: 6px 0 0;

        font-size: 13px;

        color: #817b75;
    }

    .catalog-link {
        color: #222222;

        font-size: 13px;
        font-weight: 600;

        text-decoration: none;
    }

    .catalog-link:hover {
        text-decoration: underline;
    }

    /* =========================================
       PRODUCT GRID
    ========================================== */

    .best-seller-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);

        gap: 18px;
    }

    .best-product-card {
        overflow: hidden;

        color: inherit;
        text-decoration: none;

        background: #ffffff;

        border: 1px solid #e8e3dc;
        border-radius: 14px;

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }

    .best-product-card:hover {
        transform: translateY(-3px);

        box-shadow:
            0 12px 26px
            rgba(0, 0, 0, .07);
    }

    .best-product-card img {
        width: 100%;

        aspect-ratio: 1 / 1;

        display: block;

        object-fit: cover;

        background: #f1eee9;
    }

    .best-product-body {
        padding: 16px 17px 18px;
    }

    .best-product-top {
        margin-bottom: 7px;

        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        gap: 10px;
    }

    .best-product-name {
        font-size: 15px;
        font-weight: 700;

        color: #171717;
    }

    .sold-count {
        margin-top: 5px;

        font-size: 12px;

        color: #817b75;
    }

    .normal-price {
        margin-top: 6px;

        font-size: 12px;

        color: #99938c;

        text-decoration: line-through;
    }

    .best-product-price {
        margin-top: 5px;

        font-size: 17px;
        font-weight: 700;

        color: #171717;
    }

    /* =========================================
       PROMO
    ========================================== */

    .promo-shortcut {
        margin-top: 34px;

        padding: 24px 28px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        background: #202020;
        color: #ffffff;

        border-radius: 14px;
    }

    .promo-shortcut h3 {
        margin: 0 0 5px;

        font-family: Georgia, serif;

        font-size: 21px;
        font-weight: 500;
    }

    .promo-shortcut p {
        margin: 0;

        font-size: 13px;

        color: #c7c7c7;
    }

    .promo-shortcut .btn {
        flex-shrink: 0;

        background: #ffffff;
        color: #171717;
    }

    /* =========================================
       TABLET
    ========================================== */

    @media (max-width: 800px) {

        .customer-welcome {
            padding: 32px 28px;

            align-items: flex-start;
            flex-direction: column;
        }

        .customer-summary {
            grid-template-columns: 1fr;
        }

        .best-seller-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* =========================================
       MOBILE
    ========================================== */

    @media (max-width: 560px) {

        .customer-welcome h1 {
            font-size: 32px;
        }

        .best-seller-head {
            align-items: flex-start;
            flex-direction: column;

            gap: 8px;
        }

        .best-seller-grid {
            grid-template-columns: 1fr;
        }

        .promo-shortcut {
            padding: 24px;

            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>


{{-- =========================================
     WELCOME
========================================== --}}

<section class="customer-welcome">

    <div class="customer-welcome-copy">

        <span class="customer-welcome-label">
            BERANDA
        </span>

        <h1>
            Halo, {{ ucwords(auth()->user()->nama) }}.
        </h1>

        <p>
            Cek poin, lihat produk terlaris,
            atau lanjut belanja koleksi FORMA.
        </p>

    </div>


    <div class="customer-welcome-action">

        <a
            class="btn"
            href="{{ route('customer.catalog') }}"
        >
            Lihat Katalog →
        </a>

    </div>

</section>


{{-- =========================================
     RINGKASAN CUSTOMER
========================================== --}}

<section class="customer-summary">

    {{-- SALDO POIN --}}
    <div class="summary-card">

        <span class="summary-label">
            SALDO POIN
        </span>

        <div class="summary-value">
            {{ number_format($pointBalance) }}
            poin
        </div>

        <div class="summary-description">
            Bisa digunakan untuk potongan belanja
            hingga Rp{{ number_format(
                $pointBalance * 500,
                0,
                ',',
                '.'
            ) }}.
        </div>

    </div>


    {{-- PESANAN ONLINE --}}
    <div class="summary-card">

        <span class="summary-label">
            PESANAN ONLINE
        </span>

        <div class="summary-value">
            {{ number_format($orderCount) }}
            pesanan
        </div>

        <div class="summary-description">
            Jumlah pesanan melalui website FORMA.
        </div>

    </div>

</section>


{{-- =========================================
     BEST SELLER
========================================== --}}

<section class="best-seller-section">

    <div class="best-seller-head">

        <div>

            <span class="best-seller-label">
                BEST SELLER
            </span>

            <h2>
                Produk terlaris
            </h2>

            <p>
                Produk yang paling banyak dibeli
                pelanggan FORMA.
            </p>

        </div>


        <a
            class="catalog-link"
            href="{{ route('customer.catalog') }}"
        >
            Lihat Semua →
        </a>

    </div>


    <div class="best-seller-grid">

        @forelse($products as $product)

            <a
                class="best-product-card"
                href="{{ route(
                    'customer.product',
                    $product->id_produk
                ) }}"
            >

                <img
                    src="{{ asset(
                        'images/products/' .
                        (
                            $product->gambar_produk
                            ?: 'basic-tshirt.jpg'
                        )
                    ) }}"
                    alt="{{ $product->nama_produk }}"
                >


                <div class="best-product-body">

                    <div class="best-product-top">

                        <div>

                            <div class="best-product-name">
                                {{ $product->nama_produk }}
                            </div>

                            <div class="sold-count">

                                {{ number_format(
                                    $product->total_terjual
                                ) }}
                                terjual

                            </div>

                        </div>


                        @if($product->discount > 0)

                            <span class="badge bad">
                                -{{ $product->discount }}%
                            </span>

                        @endif

                    </div>


                    @if($product->discount > 0)

                        <div class="normal-price">

                            Rp{{ number_format(
                                $product->harga_jual,
                                0,
                                ',',
                                '.'
                            ) }}

                        </div>

                    @endif


                    <div class="best-product-price">

                        Rp{{ number_format(
                            $product->harga_jual
                                * (
                                    1
                                    - $product->discount / 100
                                ),
                            0,
                            ',',
                            '.'
                        ) }}

                    </div>

                </div>

            </a>

        @empty

            <div class="card">
                Belum ada data produk terlaris.
            </div>

        @endforelse

    </div>

</section>


{{-- =========================================
     PROMO
========================================== --}}

<section class="promo-shortcut">

    <div>

        <h3>
            Cek promo yang sedang berjalan.
        </h3>

        <p>
            Lihat produk FORMA yang sedang
            mendapat potongan harga.
        </p>

    </div>


    <a
        class="btn"
        href="{{ route('customer.promotions') }}"
    >
        Lihat Promo
    </a>

</section>

@endsection