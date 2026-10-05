@extends('layouts.customer')
@section('title',$product->nama_produk)
@section('content')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:35px">
        <div class="card">
            <img
                style="width: 100%; border-radius: 10px"
                src="{{ asset('images/products/'.($product->gambar_produk ?: 'basic-tshirt.jpg')) }}"
                alt="{{ $product->nama_produk }}"
            >
        </div>
        <div>
            <small>{{ $product->nama_kategori }}</small>
            <h1>{{ $product->nama_produk }}</h1>
            @if($discount>0)
                <p>
                    <span class="old-price">Rp{{ number_format($product->harga_jual,0,',','.') }}</span>
                    <span class="badge bad">Promo {{ $discount }}%</span>
                </p>
            @endif
            <h2>Rp{{ number_format($final,0,',','.') }}</h2>
            <p class="muted">{{ $product->deskripsi }}</p>
            <form method="POST" action="{{ route('customer.cart.add') }}" class="card">
                @csrf
                <label>Varian</label>
                <select name="id_varian" required>
                    @foreach($variants as $v)
                        <option
                            value="{{ $v->id_varian }}"
                            @disabled($v->stok < 1)
                        >
                            {{ strtoupper($v->warna) }} · {{ $v->ukuran }}
                            · stok {{ $v->stok }}
                        </option>
                    @endforeach
                </select>
                <br>
                <br>
                <label>Jumlah</label>
                <input type="number" name="jumlah" min="1" value="1" required>
                <br>
                <br>
                <button class="btn">Tambah ke Keranjang</button>
            </form>
        </div>
    </div>
@endsection
