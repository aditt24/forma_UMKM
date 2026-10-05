@extends('layouts.customer')
@section('title','Checkout')
@section('content')
    <div class="page-head">
        <div>
            <h1>Checkout</h1>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1.3fr .7fr;gap:20px">
        <form class="card" method="POST" action="{{ route('customer.checkout.store') }}">
            @csrf
            <label>Alamat Pengiriman</label>
            <textarea name="alamat" required>{{ old('alamat',auth()->user()->alamat) }}</textarea>
            <br>
            <br>
            <label>Gunakan Poin</label>
            <input type="number" min="0" max="{{ $pointBalance }}" name="poin_digunakan" value="0">
            <small class="muted">
                Saldo {{ $pointBalance }} poin · 1 poin = Rp500.
            </small>
            <br>
            <br>
            <button class="btn">Lanjut ke QR Payment</button>
        </form>
        <div class="card">
            <h3>Ringkasan</h3>
            @foreach($summary['items'] as $i)
                <p>
                    {{ $i['variant']->nama_produk }} × {{ $i['qty'] }}
                    <span style="float: right">
                        Rp{{ number_format($i['line_total'], 0, ',', '.') }}
                    </span>
                </p>
            @endforeach
            <hr>
            <p>Subtotal <b style="float:right">Rp{{ number_format($summary['subtotal'],0,',','.') }}</b>
            </p>
        </div>
    </div>
@endsection
