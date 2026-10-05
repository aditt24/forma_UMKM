@extends('layouts.customer')
@section('title','Katalog')
@section('content')
    <div class="page-head">
        <div>
            <h1>Katalog</h1>
            <p class="muted">Pilih produk, ukuran, dan warna.</p>
        </div>
    </div>
    <form class="card" method="GET" style="display:flex;gap:10px;margin-bottom:20px">
        <input name="q" value="{{ request('q') }}" placeholder="Cari produk...">
        <select name="kategori">
            <option value="">Semua Kategori</option>
            @foreach($categories as $c)
                <option
                    value="{{ $c->id_kategori }}"
                    @selected(request('kategori') == $c->id_kategori)
                >
                    {{ $c->nama_kategori }}
                </option>
            @endforeach
        </select>
        <button class="btn">Cari</button>
    </form>
    <div class="product-grid">
        @foreach($products as $p)
            <a class="product-card" style="text-decoration:none" href="{{ route('customer.product',$p->id_produk) }}">
                <img src="{{ asset('images/products/'.($p->gambar_produk ?: 'basic-tshirt.jpg')) }}">
                <div class="body">
                    <small>{{ $p->nama_kategori }}</small>
                    <h3>{{ $p->nama_produk }}</h3>
                    @if($p->discount>0)
                        <span class="old-price">Rp{{ number_format($p->harga_jual,0,',','.') }}</span>
                        <span class="badge bad">-{{ $p->discount }}%</span>
                    @endif
                    <div class="price">Rp{{ number_format($p->harga_jual*(1-$p->discount/100),0,',','.') }}</div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
