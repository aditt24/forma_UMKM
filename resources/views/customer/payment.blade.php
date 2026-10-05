@extends('layouts.customer')

@section('title', 'QR Payment')

@section('content')
    <div class="card" style="max-width: 650px; margin: auto; text-align: center">
        <h1>QR Payment</h1>
        <p>
            Transaksi <strong>{{ $sale->id_penjualan }}</strong>
        </p>

        <img
            src="{{ asset('images/payments/qris-sample.png') }}"
            alt="QR pembayaran simulasi"
            style="width: 240px; max-width: 70%"
        >

        <h2>Rp{{ number_format($sale->nominal_bayar, 0, ',', '.') }}</h2>
        <p class="muted">
            Status pembayaran: {{ $sale->status_pembayaran }}.
        </p>

        @if ($sale->status_pembayaran === 'MENUNGGU' && $sale->status_penjualan !== 'BATAL')
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap">
                <form
                    method="POST"
                    action="{{ route('customer.payment.pay', $sale->id_penjualan) }}"
                >
                    @csrf
                    <button type="submit" class="btn green">
                        Pembayaran Berhasil
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('customer.payment.fail', $sale->id_penjualan) }}"
                >
                    @csrf
                    <button type="submit" class="btn red">
                        Pembayaran Gagal
                    </button>
                </form>
            </div>
        @elseif ($sale->status_pembayaran === 'GAGAL')
            <span class="badge bad">Pembayaran gagal · pesanan dibatalkan</span>
        @elseif ($sale->status_penjualan === 'BATAL')
            <span class="badge bad">Pesanan dibatalkan</span>
        @else
            <a
                class="btn"
                href="{{ route('customer.orders.show', $sale->id_penjualan) }}"
            >
                Lihat Pesanan
            </a>
        @endif
    </div>
@endsection
