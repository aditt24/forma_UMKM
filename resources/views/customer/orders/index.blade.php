@extends('layouts.customer')
@section('title','Pesanan')
@section('content')
    <div class="page-head">
        <div>
            <h1>Pesanan Saya</h1>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Total</th>
                <th>Poin</th>
                <th>
                </th>
            </tr>
            @foreach($orders as $o)
                <tr>
                    <td>{{ $o->id_penjualan }}</td>
                    <td>{{ $o->tanggal_penjualan }}</td>
                    <td>{{ $o->status_pembayaran }}</td>
                    <td>{{ $o->status_penjualan }}</td>
                    <td>Rp{{ number_format($o->nominal_bayar,0,',','.') }}</td>
                    <td>+{{ $o->poin_didapat }} / -{{ $o->poin_digunakan }}</td>
                    <td>
                        <a class="btn sm" href="{{ route('customer.orders.show',$o->id_penjualan) }}">Detail</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
