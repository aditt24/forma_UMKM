@extends('layouts.kasir')
@section('title','Riwayat Kasir')
@section('content')
    <div class="page-head">
        <div>
            <h1>Riwayat Transaksi</h1>
            <p class="muted">Hanya transaksi OFFLINE yang diproses oleh {{ auth()->user()->nama }}.</p>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Metode</th>
                <th>Total</th>
                <th>Status</th>
                <th>
                </th>
            </tr>
            @foreach($sales as $s)
                <tr>
                    <td>{{ $s->id_penjualan }}</td>
                    <td>{{ $s->tanggal_penjualan }}</td>
                    <td>{{ $s->metode_pembayaran }}</td>
                    <td>Rp{{ number_format($s->nominal_bayar,0,',','.') }}</td>
                    <td>{{ $s->status_pembayaran }}</td>
                    <td>
                        <a class="btn sm" href="{{ route('kasir.receipt',$s->id_penjualan) }}">Struk</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
