@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="page-head">
        <div>
            <h1>Dashboard</h1>
            <p class="muted">
                Ringkasan data utama FORMA.
            </p>
        </div>
    </div>


    {{-- Ringkasan Data --}}
    <div class="stats">

        <div class="card stat">
            Total Penjualan
            <strong>
                Rp{{ number_format($totalSales, 0, ',', '.') }}
            </strong>
        </div>

        <div class="card stat">
            Jumlah Transaksi
            <strong>
                {{ $transactions }}
            </strong>
        </div>

        <div class="card stat">
            Total Produk
            <strong>
                {{ $products }}
            </strong>
        </div>

        <div class="card stat">
            Total Pelanggan
            <strong>
                {{ $customers }}
            </strong>
        </div>

    </div>


    {{-- Stok Hampir Habis --}}
    <div class="card" style="margin-top:20px;">

        <div class="page-head">
            <div>
                <h3>Stok Hampir Habis</h3>
            </div>
        </div>

        @forelse($lowStock as $v)

            <div
                style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    border-bottom:1px solid #eee;
                    padding:10px 0;
                    gap:12px;
                "
            >

                <span>
                    {{ $v->nama_produk }}
                    <br>

                    <small>
                        {{ $v->ukuran }}
                        /
                        {{ ucfirst($v->warna) }}
                        · Minimum {{ $v->stok_minimum }}
                    </small>
                </span>

                @if($v->stok == 0)

                    <span class="badge bad">
                        HABIS
                    </span>

                @else

                    <span class="badge bad">
                        Stok {{ $v->stok }}
                    </span>

                @endif

            </div>

        @empty

            <p class="muted">
                Tidak ada produk dengan stok rendah.
            </p>

        @endforelse

    </div>


    {{-- Transaksi Terbaru --}}
    <div style="margin-top:28px;">

        <div class="page-head">
            <div>
                <h3>Transaksi Terbaru</h3>
            </div>
        </div>

        <div class="table-wrap">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Pelaksana</th>
                    <th>Kanal</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>

                @forelse($recent as $s)

                    <tr>

                        <td>
                            <a
                                href="{{ route(
                                    'admin.sales.show',
                                    $s->id_penjualan
                                ) }}"
                            >
                                {{ $s->id_penjualan }}
                            </a>
                        </td>

                        <td>
                            {{ $s->nama }}
                        </td>

                        <td>
                            {{ $s->kanal_penjualan }}
                        </td>

                        <td>
                            Rp{{ number_format(
                                $s->nominal_bayar,
                                0,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td>
                            {{ $s->status_pembayaran }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="muted"
                            style="text-align:center;"
                        >
                            Belum ada transaksi.
                        </td>
                    </tr>

                @endforelse

            </table>

        </div>

    </div>

@endsection