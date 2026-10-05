@extends('layouts.kasir')
@section('title','Struk')
@section('content')
    <div class="card" style="max-width:650px;margin:auto">
        <div style="text-align:center">
            <div class="brand">FORMA</div>
            <h2>STRUK PENJUALAN</h2>
            <p>{{ $sale->id_penjualan }} · {{ $sale->tanggal_penjualan }}</p>
        </div>
        <hr>
        @foreach($details as $d)
            <p>
                {{ $d->nama_produk }} {{ $d->ukuran }}/{{ $d->warna }} × {{ $d->jumlah }}
                <b style="float: right">
                    Rp{{ number_format($d->harga_satuan * $d->jumlah, 0, ',', '.') }}
                </b>
            </p>
        @endforeach
        <hr>
        @if($member)
            <p>Member: <b>{{ $member->nama }} ({{ $member->id_akun }})</b>
            </p>
        @else
            <p>Customer: <b>GUEST</b>
            </p>
        @endif
        <p>Diskon poin <b style="float:right">Rp{{ number_format($sale->diskon_poin,0,',','.') }}</b>
        </p>
        <h2>Total <span style="float:right">Rp{{ number_format($sale->nominal_bayar,0,',','.') }}</span>
        </h2>
        <p>Metode: {{ $sale->metode_pembayaran }}</p>
        <p>Poin didapat: {{ $sale->poin_didapat }} · digunakan: {{ $sale->poin_digunakan }}</p>
        <div style="text-align:center;margin-top:25px">
            <a class="btn" href="{{ route('kasir.pos') }}">Transaksi Baru</a>
        </div>
    </div>
@endsection
