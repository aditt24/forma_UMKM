@extends('layouts.customer')
@section('title', 'Promo')
@section('content')
    <div class="page-head">
        <div>
            <h1>Promo Aktif</h1>
            <p class="muted">Produk dengan promo aktif saat ini.</p>
        </div>
    </div>
    @if($products->isEmpty())
        <div class="card">
            <h3>Belum ada promo aktif</h3>
            <p class="muted">Cek kembali nanti atau lihat seluruh katalog.</p>
            <a class="btn" href="{{ route('customer.catalog') }}">Lihat Katalog</a>
        </div>
    @else
        <div class="product-grid">
            @foreach($products as $product)
                <a
                class="product-card"
                style="text-decoration:none"
                href="{{ route('customer.product', $product->id_produk) }}"
                >
                <img
                src="{{ asset('images/products/'.($product->gambar_produk ?: 'basic-tshirt.jpg')) }}"
                alt="{{ $product->nama_produk }}"
                >
                <div class="body">
                    <span class="badge bad">-{{ number_format($product->discount, 0) }}%</span>
                    <h3>{{ $product->nama_produk }}</h3>
                    <span class="old-price">Rp{{ number_format($product->harga_jual, 0, ',', '.') }}</span>
                    <div class="price">
                        Rp{{ number_format($product->harga_jual * (1 - $product->discount / 100), 0, ',', '.') }}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
