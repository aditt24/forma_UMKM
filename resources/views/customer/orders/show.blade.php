@extends('layouts.customer')
@section('title','Detail Pesanan')
@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $order->id_penjualan }}</h1>
            <p class="muted">{{ $order->tanggal_penjualan }}</p>
        </div>
        <div>
            <span class="badge">{{ $order->status_pembayaran }}</span>
            <span class="badge">{{ $order->status_penjualan }}</span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
            @foreach($details as $d)
                <tr>
                    <td>{{ $d->nama_produk }}</td>
                    <td>{{ $d->ukuran }} / {{ $d->warna }}</td>
                    <td>{{ $d->jumlah }}</td>
                    <td>Rp{{ number_format($d->harga_satuan,0,',','.') }}</td>
                    <td>Rp{{ number_format($d->jumlah*$d->harga_satuan,0,',','.') }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="card" style="margin-top:18px">
        <p>Diskon poin: Rp{{ number_format($order->diskon_poin,0,',','.') }}</p>
        <h2>Total: Rp{{ number_format($order->nominal_bayar,0,',','.') }}</h2>
        <p>Poin didapat: {{ $order->poin_didapat }} · digunakan: {{ $order->poin_digunakan }}</p>
        @if($order->status_pembayaran==='MENUNGGU' && $order->status_penjualan==='BARU')
            <div style="display:flex;gap:10px">
                <a class="btn green" href="{{ route('customer.payment',$order->id_penjualan) }}">Bayar</a>
                <form method="POST" action="{{ route('customer.orders.cancel',$order->id_penjualan) }}">
                    @csrf
                    @method('PATCH')
                    <button class="btn red">Batalkan Pesanan</button>
                </form>
            </div>
        @endif
    </div>
@endsection
