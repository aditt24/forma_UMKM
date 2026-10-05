@extends('layouts.kasir')

@section('title', 'Dashboard Kasir')

@section('content')

<style>
    /* =========================================
       HEADER
    ========================================== */

    .cashier-welcome {
        margin-bottom: 24px;
        padding: 34px 38px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 28px;

        background: #eee9e1;

        border: 1px solid #e7e1d9;
        border-radius: 16px;
    }

    .cashier-welcome-content {
        max-width: 650px;
    }

    .cashier-label {
        display: block;

        margin-bottom: 8px;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;

        color: #716b65;
    }

    .cashier-welcome h1 {
        margin: 0 0 8px;

        font-family: Georgia, serif;

        font-size: clamp(
            30px,
            4vw,
            42px
        );

        font-weight: 500;
        line-height: 1.1;

        color: #171717;
    }

    .cashier-welcome p {
        margin: 0;

        font-size: 14px;
        line-height: 1.6;

        color: #716b65;
    }

    .cashier-info {
        margin-top: 15px;

        display: flex;
        align-items: center;
        gap: 8px;

        flex-wrap: wrap;
    }

    .cashier-id {
        padding: 7px 11px;

        background: rgba(255, 255, 255, .7);

        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 8px;

        font-size: 12px;
        font-weight: 600;
    }

    .active-status {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        padding: 7px 11px;

        background: #ffffff;

        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 8px;

        font-size: 12px;
        font-weight: 600;
    }

    .active-dot {
        width: 7px;
        height: 7px;

        background: #4c7b55;

        border-radius: 50%;
    }

    .cashier-welcome-action {
        flex-shrink: 0;
    }

    .pos-button {
        min-width: 142px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 13px 18px;
    }

    /* =========================================
       STATS
    ========================================== */

    .cashier-stats {
        margin-bottom: 38px;

        display: grid;
        grid-template-columns: repeat(3, 1fr);

        gap: 16px;
    }

    .cashier-stat {
        min-height: 132px;

        padding: 23px 25px;

        display: flex;
        flex-direction: column;
        justify-content: space-between;

        background: #ffffff;

        border: 1px solid #e9e4dd;
        border-radius: 14px;
    }

    .cashier-stat-label {
        font-size: 11px;
        font-weight: 700;

        letter-spacing: 1.6px;

        color: #85807a;
    }

    .cashier-stat-value {
        margin-top: 16px;

        font-size: 25px;
        font-weight: 700;

        color: #171717;
    }

    .cashier-stat-note {
        margin-top: 5px;

        font-size: 12px;

        color: #8a847e;
    }

    /* =========================================
       RIWAYAT TRANSAKSI
    ========================================== */

    .recent-section {
        margin-top: 4px;
    }

    .recent-head {
        margin-bottom: 17px;

        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        gap: 20px;
    }

    .recent-label {
        display: block;

        margin-bottom: 5px;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2.5px;

        color: #77726c;
    }

    .recent-head h2 {
        margin: 0;

        font-size: 26px;
    }

    .recent-head p {
        margin: 6px 0 0;

        font-size: 13px;

        color: #817b75;
    }

    /* =========================================
       TABLE
    ========================================== */

    .cashier-table-wrap {
        overflow: hidden;

        background: #ffffff;

        border: 1px solid #e9e4dd;
        border-radius: 14px;
    }

    .cashier-table-wrap table {
        width: 100%;

        border-collapse: collapse;
    }

    .cashier-table-wrap th {
        padding: 13px 17px;

        text-align: left;

        background: #f7f4ef;

        border-bottom: 1px solid #ebe6df;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: .7px;

        color: #77726c;
    }

    .cashier-table-wrap td {
        padding: 15px 17px;

        border-bottom: 1px solid #f0ece6;

        font-size: 13px;
    }

    .cashier-table-wrap tr:last-child td {
        border-bottom: none;
    }

    .transaction-id {
        color: #171717;

        font-weight: 700;

        text-decoration: none;
    }

    .transaction-id:hover {
        text-decoration: underline;
    }

    .payment-method {
        display: inline-block;

        padding: 5px 9px;

        background: #f3f0eb;

        border-radius: 7px;

        font-size: 11px;
        font-weight: 600;
    }

    .payment-status {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        padding: 5px 9px;

        border-radius: 7px;

        font-size: 11px;
        font-weight: 700;
    }

    .payment-status.success {
        background: #edf4ee;

        color: #41694a;
    }

    .payment-status.pending {
        background: #f7f0df;

        color: #836b2e;
    }

    .payment-status.failed {
        background: #f5e9e7;

        color: #8b4740;
    }

    .status-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: currentColor;
    }

    .empty-transactions {
        padding: 46px 24px;

        text-align: center;
    }

    .empty-transactions strong {
        display: block;

        margin-bottom: 5px;

        font-size: 15px;
    }

    .empty-transactions span {
        font-size: 13px;

        color: #817b75;
    }

    /* =========================================
       CTA
    ========================================== */

    .cashier-cta {
        margin-top: 28px;

        padding: 25px 28px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 22px;

        background: #202020;
        color: #ffffff;

        border-radius: 14px;
    }

    .cashier-cta h3 {
        margin: 0 0 5px;

        font-family: Georgia, serif;

        font-size: 21px;
        font-weight: 500;
    }

    .cashier-cta p {
        margin: 0;

        font-size: 13px;

        color: #c7c7c7;
    }

    .cashier-cta .btn {
        flex-shrink: 0;

        background: #ffffff;
        color: #171717;
    }

    /* =========================================
       TABLET
    ========================================== */

    @media (max-width: 900px) {
        .cashier-stats {
            grid-template-columns: 1fr 1fr;
        }

        .cashier-stat:last-child {
            grid-column: 1 / -1;
        }
    }

    /* =========================================
       MOBILE
    ========================================== */

    @media (max-width: 700px) {
        .cashier-welcome {
            padding: 30px 27px;

            align-items: flex-start;
            flex-direction: column;
        }

        .cashier-welcome h1 {
            font-size: 32px;
        }

        .cashier-welcome-action {
            width: 100%;
        }

        .pos-button {
            width: 100%;
        }

        .cashier-stats {
            grid-template-columns: 1fr;
        }

        .cashier-stat:last-child {
            grid-column: auto;
        }

        .recent-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .cashier-table-wrap {
            overflow-x: auto;
        }

        .cashier-table-wrap table {
            min-width: 700px;
        }

        .cashier-cta {
            padding: 24px;

            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>


@php
    $averageTransaction = $todayCount > 0
        ? $todaySales / $todayCount
        : 0;
@endphp


{{-- =========================================
     HEADER
========================================== --}}

<section class="cashier-welcome">

    <div class="cashier-welcome-content">

        <span class="cashier-label">
            KASIR FORMA
        </span>

        <h1>
            Penjualan hari ini
        </h1>

        <p>
            Kelola transaksi penjualan pakaian
            langsung melalui POS.
        </p>


        <div class="cashier-info">

            <span class="cashier-id">
                ID {{ auth()->user()->id_akun }}
            </span>

            <span class="active-status">

                <span class="active-dot"></span>

                Aktif

            </span>

        </div>

    </div>


    <div class="cashier-welcome-action">

        <a
            class="btn pos-button"
            href="{{ route('kasir.pos') }}"
        >
            Buka POS →
        </a>

    </div>

</section>


{{-- =========================================
     RINGKASAN HARI INI
========================================== --}}

<section class="cashier-stats">

    <div class="cashier-stat">

        <span class="cashier-stat-label">
            PENJUALAN HARI INI
        </span>

        <div>

            <div class="cashier-stat-value">
                Rp{{ number_format(
                    $todaySales,
                    0,
                    ',',
                    '.'
                ) }}
            </div>

            <div class="cashier-stat-note">
                Total penjualan yang sudah dibayar
            </div>

        </div>

    </div>


    <div class="cashier-stat">

        <span class="cashier-stat-label">
            TRANSAKSI HARI INI
        </span>

        <div>

            <div class="cashier-stat-value">
                {{ number_format($todayCount) }}
            </div>

            <div class="cashier-stat-note">
                Jumlah transaksi yang sudah selesai
            </div>

        </div>

    </div>


    <div class="cashier-stat">

        <span class="cashier-stat-label">
            RATA-RATA BELANJA
        </span>

        <div>

            <div class="cashier-stat-value">
                Rp{{ number_format(
                    $averageTransaction,
                    0,
                    ',',
                    '.'
                ) }}
            </div>

            <div class="cashier-stat-note">
                Rata-rata belanja per transaksi
            </div>

        </div>

    </div>

</section>


{{-- =========================================
     RIWAYAT TRANSAKSI
========================================== --}}

<section class="recent-section">

    <div class="recent-head">

        <div>

            <span class="recent-label">
                TRANSAKSI TERBARU
            </span>

            <h2>
                Riwayat transaksi
            </h2>

            <p>
                Daftar transaksi terakhir yang
                diproses melalui kasir.
            </p>

        </div>

    </div>


    <div class="cashier-table-wrap">

        @if($recent->count() > 0)

            <table>

                <thead>

                    <tr>
                        <th>ID TRANSAKSI</th>
                        <th>WAKTU</th>
                        <th>PEMBAYARAN</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                    </tr>

                </thead>


                <tbody>

                    @foreach($recent as $s)

                        <tr>

                            <td>

                                <a
                                    class="transaction-id"
                                    href="{{ route(
                                        'kasir.receipt',
                                        $s->id_penjualan
                                    ) }}"
                                >
                                    {{ $s->id_penjualan }}
                                </a>

                            </td>


                            <td>
                                {{ $s->tanggal_penjualan }}
                            </td>


                            <td>

                                <span class="payment-method">
                                    {{ $s->metode_pembayaran }}
                                </span>

                            </td>


                            <td>
                                <strong>
                                    Rp{{ number_format(
                                        $s->nominal_bayar,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </strong>
                            </td>


                            <td>

                                @php
                                    $statusClass = match(
                                        $s->status_pembayaran
                                    ) {
                                        'BERHASIL' => 'success',
                                        'GAGAL' => 'failed',
                                        default => 'pending'
                                    };
                                @endphp


                                <span
                                    class="payment-status {{ $statusClass }}"
                                >

                                    <span class="status-dot"></span>

                                    {{ $s->status_pembayaran }}

                                </span>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-transactions">

                <strong>
                    Belum ada transaksi.
                </strong>

                <span>
                    Transaksi dari POS akan tampil
                    di sini setelah pembayaran selesai.
                </span>

            </div>

        @endif

    </div>

</section>


{{-- =========================================
     BUKA POS
========================================== --}}

<section class="cashier-cta">

    <div>

        <h3>
            Mulai transaksi
        </h3>

        <p>
            Pilih produk yang dibeli lalu proses
            pembayaran melalui POS.
        </p>

    </div>


    <a
        class="btn"
        href="{{ route('kasir.pos') }}"
    >
        Buka POS
    </a>

</section>

@endsection