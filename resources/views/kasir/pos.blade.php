@extends('layouts.kasir')

@section('title', 'Point of Sale')

@section('content')

<div class="page-head">
    <div>
        <h1>Point of Sale</h1>
        <p class="muted">
            Pilih produk, varian, dan jumlah.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('kasir.pos.clear') }}"
    >
        @csrf
        @method('DELETE')

        <button class="btn red">
            Hapus Semua
        </button>
    </form>
</div>


{{-- ERROR --}}
@if($errors->any())
    <div
        class="flash"
        style="
            margin-bottom:16px;
            background:#fff0f0;
        "
    >
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif


{{-- SUCCESS --}}
@if(session('success'))
    <div
        class="flash"
        style="margin-bottom:16px"
    >
        {{ session('success') }}
    </div>
@endif


<div
    style="
        display:grid;
        grid-template-columns:minmax(0,1.4fr) minmax(330px,.8fr);
        gap:22px;
        align-items:start;
    "
>

    {{-- =========================================================
         KIRI - DAFTAR PRODUK
    ========================================================== --}}
    <section>

        {{-- SEARCH --}}
        <form
            method="GET"
            class="card"
            style="
                display:grid;
                grid-template-columns:1fr 1fr auto;
                gap:10px;
                margin-bottom:16px;
            "
        >
            <input
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari produk..."
            >

            <select name="kategori">
                <option value="">
                    Semua Kategori
                </option>

                @foreach($categories as $c)
                    <option
                        value="{{ $c->id_kategori }}"
                        @selected(
                            request('kategori')
                            == $c->id_kategori
                        )
                    >
                        {{ $c->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <button class="btn">
                Cari
            </button>
        </form>


        {{-- PRODUCT GRID --}}
        <div
            class="product-grid"
            style="
                grid-template-columns:
                    repeat(3,minmax(0,1fr));
                gap:14px;
            "
        >

            @forelse($products as $product)

                <div class="product-card">

                    <img
                        src="{{ asset(
                            'images/products/'.
                            (
                                $product['gambar_produk']
                                ?: 'basic-tshirt.jpg'
                            )
                        ) }}"
                        alt="{{ $product['nama_produk'] }}"
                    >

                    <div class="body">

                        <div class="muted">
                            {{ $product['nama_kategori'] }}
                        </div>

                        <b
                            style="
                                display:block;
                                margin-top:4px;
                            "
                        >
                            {{ $product['nama_produk'] }}
                        </b>


                        @if($product['discount'] > 0)

                            <div
                                style="
                                    margin-top:7px;
                                    display:flex;
                                    align-items:center;
                                    gap:7px;
                                "
                            >

                                <span
                                    class="muted"
                                    style="
                                        text-decoration:
                                            line-through;
                                    "
                                >
                                    Rp{{ number_format(
                                        $product['harga_jual'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </span>

                                <span class="badge bad">
                                    -{{ $product['discount'] }}%
                                </span>

                            </div>

                        @endif


                        <div
                            class="price"
                            style="margin-top:5px"
                        >
                            Rp{{ number_format(
                                $product['harga_final'],
                                0,
                                ',',
                                '.'
                            ) }}
                        </div>


                        <a
                            class="btn sm"
                            style="
                                display:block;
                                text-align:center;
                                width:100%;
                                margin-top:10px;
                            "
                            href="{{ route(
                                'kasir.pos',
                                array_filter([
                                    'q' =>
                                        request('q'),

                                    'kategori' =>
                                        request('kategori'),

                                    'produk' =>
                                        $product['id_produk'],
                                ])
                            ) }}"
                        >
                            Pilih Produk
                        </a>

                    </div>
                </div>

            @empty

                <div class="card">
                    Produk tidak ditemukan.
                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================================================
         KANAN
    ========================================================== --}}
    <aside>


        {{-- =====================================================
             PRODUK YANG DIPILIH
        ====================================================== --}}

        @if($selectedProduct)

            <div
                class="card"
                style="margin-bottom:16px"
            >

                <h3
                    style="
                        margin-top:0;
                        margin-bottom:4px;
                    "
                >
                    Tambah Produk
                </h3>

                <div
                    class="muted"
                    style="margin-bottom:12px"
                >
                    Pilih ukuran / warna dan jumlah.
                </div>


                <div
                    style="
                        display:flex;
                        gap:12px;
                        align-items:center;
                        margin-bottom:14px;
                    "
                >

                    <img
                        src="{{ asset(
                            'images/products/'.
                            (
                                $selectedProduct[
                                    'gambar_produk'
                                ]
                                ?: 'basic-tshirt.jpg'
                            )
                        ) }}"
                        style="
                            width:72px;
                            height:72px;
                            object-fit:cover;
                            border-radius:10px;
                        "
                    >

                    <div>

                        <b>
                            {{
                                $selectedProduct[
                                    'nama_produk'
                                ]
                            }}
                        </b>

                        <div class="muted">
                            {{
                                $selectedProduct[
                                    'nama_kategori'
                                ]
                            }}
                        </div>

                        <div
                            class="price"
                            style="margin-top:4px"
                        >
                            Rp{{ number_format(
                                $selectedProduct[
                                    'harga_final'
                                ],
                                0,
                                ',',
                                '.'
                            ) }}
                        </div>

                    </div>
                </div>


                <form
                    method="POST"
                    action="{{ route('kasir.pos.add') }}"
                >
                    @csrf

                    <label>
                        Varian
                    </label>

                    <select
                        name="id_varian"
                        required
                        style="margin-top:5px"
                    >

                        @foreach(
                            $selectedProduct['variants']
                            as $v
                        )

                            <option
                                value="{{ $v->id_varian }}"
                            >
                                {{ strtoupper($v->ukuran) }}
                                /
                                {{ strtoupper($v->warna) }}
                                · stok {{ $v->stok }}
                            </option>

                        @endforeach

                    </select>


                    <div style="margin-top:12px">

                        <label>
                            Jumlah
                        </label>

                        <input
                            type="number"
                            name="jumlah"
                            value="1"
                            min="1"
                            required
                            style="margin-top:5px"
                        >

                    </div>


                    <button
                        class="btn"
                        style="
                            width:100%;
                            margin-top:12px;
                        "
                    >
                        Tambah ke Keranjang
                    </button>

                </form>

            </div>

        @endif



        {{-- =====================================================
             KERANJANG
        ====================================================== --}}

        <div class="card">

            <h3 style="margin-top:0">
                Keranjang Transaksi
            </h3>


            @forelse(
                $summary['items']
                as $i
            )

                <div
                    style="
                        border-bottom:
                            1px solid #eee;
                        padding:12px 0;
                    "
                >

                    <div
                        style="
                            display:flex;
                            justify-content:
                                space-between;
                            gap:10px;
                        "
                    >

                        <div>

                            <b>
                                {{
                                    $i[
                                        'variant'
                                    ]->nama_produk
                                }}
                            </b>

                            <div class="muted">
                                {{
                                    strtoupper(
                                        $i[
                                            'variant'
                                        ]->ukuran
                                    )
                                }}
                                /
                                {{
                                    strtoupper(
                                        $i[
                                            'variant'
                                        ]->warna
                                    )
                                }}
                            </div>

                        </div>


                        <b>
                            Rp{{ number_format(
                                $i['line_total'],
                                0,
                                ',',
                                '.'
                            ) }}
                        </b>

                    </div>


                    <div
                        style="
                            display:flex;
                            gap:6px;
                            align-items:center;
                            margin-top:8px;
                        "
                    >

                        {{-- UPDATE --}}
                        <form
                            method="POST"
                            action="{{ route(
                                'kasir.pos.update',
                                $i['variant']->id_varian
                            ) }}"
                            style="
                                display:flex;
                                gap:5px;
                                flex:1;
                            "
                        >
                            @csrf
                            @method('PATCH')

                            <input
                                name="jumlah"
                                type="number"
                                min="1"
                                max="{{
                                    $i[
                                        'variant'
                                    ]->stok
                                }}"
                                value="{{ $i['qty'] }}"
                                style="
                                    width:75px;
                                "
                            >

                            <button
                                class="btn sm light"
                            >
                                Set
                            </button>

                        </form>


                        {{-- DELETE --}}
                        <form
                            method="POST"
                            action="{{ route(
                                'kasir.pos.remove',
                                $i[
                                    'variant'
                                ]->id_varian
                            ) }}"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                class="btn sm red"
                                title="Hapus"
                            >
                                ×
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div
                    class="muted"
                    style="
                        padding:15px 0;
                        text-align:center;
                    "
                >
                    Keranjang masih kosong.
                </div>

            @endforelse



            {{-- =================================================
                 MEMBER
            ================================================== --}}

            <hr>

            <h3>
                Member
            </h3>


            @if($member)

                <div class="flash">

                    <b>
                        {{ $member->nama }}
                    </b>

                    <br>

                    {{ $member->id_akun }}

                    · saldo

                    <b>
                        {{ $pointBalance }}
                    </b>

                    poin


                    <form
                        method="POST"
                        action="{{
                            route(
                                'kasir.pos.member.clear'
                            )
                        }}"
                        style="
                            margin-top:8px;
                        "
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            class="btn sm light"
                        >
                            Jadikan Guest
                        </button>

                    </form>

                </div>

            @else

                <form
                    method="POST"
                    action="{{
                        route(
                            'kasir.pos.member'
                        )
                    }}"
                >
                    @csrf

                    <input
                        name="member"
                        placeholder="
                            ID / username / email / no telp
                        "
                    >

                    <button
                        class="btn sm"
                        style="margin-top:8px"
                    >
                        Pilih Member
                    </button>

                </form>

            @endif



            {{-- =================================================
                 TOTAL
            ================================================== --}}

            <hr>

            <p>
                Subtotal

                <b style="float:right">
                    Rp{{ number_format(
                        $summary['subtotal'],
                        0,
                        ',',
                        '.'
                    ) }}
                </b>
            </p>


            {{-- =================================================
                 CHECKOUT
            ================================================== --}}

            <form
                method="POST"
                action="{{
                    route(
                        'kasir.pos.checkout'
                    )
                }}"
            >
                @csrf


                <label>
                    Metode Pembayaran
                </label>

                <select
                    name="metode_pembayaran"
                    style="margin-top:5px"
                >
                    <option value="TUNAI">
                        TUNAI
                    </option>

                    <option value="QR">
                        QR
                    </option>
                </select>


                @if($member)

                    <div
                        style="margin-top:12px"
                    >

                        <label>
                            Gunakan Poin
                        </label>

                        <input
                            type="number"
                            name="poin_digunakan"
                            min="0"
                            max="{{ $pointBalance }}"
                            value="0"
                            style="margin-top:5px"
                        >

                        <div
                            class="muted"
                            style="
                                margin-top:5px;
                                font-size:12px;
                            "
                        >
                            1 poin = Rp500
                        </div>

                    </div>

                @endif


                <button
                    class="btn green"
                    style="
                        width:100%;
                        margin-top:16px;
                    "
                    @disabled(
                        empty(
                            $summary['items']
                        )
                    )
                >
                    Bayar & Simpan Transaksi
                </button>

            </form>

        </div>

    </aside>

</div>

@endsection