@extends('layouts.customer')
@section('title','Keranjang')
@section('content')
    <div class="page-head">
        <div>
            <h1>Keranjang</h1>
        </div>
        @if($summary['items'])
            <form method="POST" action="{{ route('customer.cart.clear') }}">
                @csrf
                @method('DELETE')
                <button class="btn red">Kosongkan</button>
            </form>
        @endif
    </div>
    @if(!$summary['items'])
        <div class="card">Keranjang masih kosong. <a href="{{ route('customer.catalog') }}">
            <b>Lihat katalog</b>
        </a>
    </div>
@else
    <div class="table-wrap">
        <table>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>
                </th>
            </tr>
            @foreach($summary['items'] as $i)
                <tr>
                    <td>{{ $i['variant']->nama_produk }}</td>
                    <td>{{ $i['variant']->ukuran }} / {{ $i['variant']->warna }}</td>
                    <td>Rp{{ number_format($i['unit_price'],0,',','.') }}
                        @if($i['discount']>0)
                            <span class="badge bad">-{{ $i['discount'] }}%</span>
                        @endif
                    </td>
                    <td>
                        <form
                            method="POST"
                            action="{{ route('customer.cart.update', $i['variant']->id_varian) }}"
                            style="display: flex; gap: 5px"
                        >
                            @csrf
                            @method('PATCH')
                            <input style="width:75px" type="number" name="jumlah" min="1" value="{{ $i['qty'] }}">
                            <button class="btn sm">Update</button>
                        </form>
                    </td>
                    <td>Rp{{ number_format($i['line_total'],0,',','.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('customer.cart.remove',$i['variant']->id_varian) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn sm red">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="card" style="margin-top:18px;text-align:right">
        <p>Subtotal</p>
        <h2>Rp{{ number_format($summary['subtotal'],0,',','.') }}</h2>
        <a class="btn" href="{{ route('customer.checkout') }}">Checkout →</a>
    </div>
@endif
@endsection
