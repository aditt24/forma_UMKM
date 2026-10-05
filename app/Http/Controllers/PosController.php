<?php

namespace App\Http\Controllers;

use App\Services\PointService;
use App\Services\PricingService;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function __construct(
        private PricingService $pricing,
        private PointService $points,
        private SaleService $sales
    ) {}

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil semua varian yang masih tersedia
        |--------------------------------------------------------------------------
        */

        $query = DB::table('varian_produk as v')
            ->join(
                'produk as p',
                'v.id_produk',
                '=',
                'p.id_produk'
            )
            ->join(
                'kategori_produk as k',
                'p.id_kategori',
                '=',
                'k.id_kategori'
            )
            ->where('p.status_produk', 'AKTIF')
            ->where('v.stok', '>', 0)
            ->select(
                'v.*',
                'p.nama_produk',
                'p.harga_jual',
                'p.gambar_produk',
                'p.id_produk',
                'p.id_kategori',
                'k.nama_kategori'
            );

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('q')) {
            $query->where(
                'p.nama_produk',
                'like',
                '%'.$request->q.'%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter kategori
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kategori')) {
            $query->where(
                'p.id_kategori',
                $request->kategori
            );
        }

        $variants = $query
            ->orderBy('p.nama_produk')
            ->orderBy('v.warna')
            ->orderBy('v.ukuran')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Tambahkan harga promo
        |--------------------------------------------------------------------------
        */

        foreach ($variants as $variant) {
            $variant->discount =
                $this->pricing->activeDiscount(
                    $variant->id_produk
                );

            $variant->harga_final = round(
                $variant->harga_jual
                    * (
                        1
                        - $variant->discount / 100
                    ),
                2
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Kelompokkan berdasarkan PRODUK
        |--------------------------------------------------------------------------
        |
        | Sebelumnya 1 kartu = 1 varian.
        |
        | Sekarang:
        | Basic T-Shirt
        |   S / hitam
        |   M / hitam
        |   L / hitam
        |   XL / hitam
        |
        | hanya tampil sebagai 1 kartu produk.
        |
        */

        $products = $variants
            ->groupBy('id_produk')
            ->map(function ($productVariants) {
                $first = $productVariants->first();

                return [
                    'id_produk' =>
                        $first->id_produk,

                    'nama_produk' =>
                        $first->nama_produk,

                    'nama_kategori' =>
                        $first->nama_kategori,

                    'gambar_produk' =>
                        $first->gambar_produk,

                    'harga_jual' =>
                        $first->harga_jual,

                    'discount' =>
                        $first->discount,

                    'harga_final' =>
                        $first->harga_final,

                    'variants' =>
                        $productVariants->values(),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Produk yang sedang dipilih
        |--------------------------------------------------------------------------
        */

        $selectedProductId =
            $request->input('produk');

        $selectedProduct = null;

        if ($selectedProductId) {
            $selectedProduct = $products->first(
                function ($product) use (
                    $selectedProductId
                ) {
                    return $product['id_produk']
                        === $selectedProductId;
                }
            );
        }

        /*
         * Kalau belum memilih produk,
         * otomatis pilih produk pertama.
         */
        if (
            !$selectedProduct
            && $products->isNotEmpty()
        ) {
            $selectedProduct =
                $products->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Kategori
        |--------------------------------------------------------------------------
        */

        $categories = DB::table(
            'kategori_produk'
        )
            ->orderBy('nama_kategori')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Cart dan member
        |--------------------------------------------------------------------------
        */

        $cart = session(
            'pos_cart',
            []
        );

        $memberId = session(
            'pos_member'
        );

        $member = $memberId
            ? DB::table('akun')
                ->where(
                    'id_akun',
                    $memberId
                )
                ->first()
            : null;

        /*
        |--------------------------------------------------------------------------
        | Summary transaksi
        |--------------------------------------------------------------------------
        */

        $summary = $this->pricing->calculate(
            $cart,
            $memberId,
            0
        );

        $pointBalance = $memberId
            ? $this->points->balance(
                $memberId
            )
            : 0;

        return view(
            'kasir.pos',
            compact(
                'products',
                'categories',
                'selectedProduct',
                'summary',
                'member',
                'pointBalance'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Tambah ke keranjang
    |--------------------------------------------------------------------------
    */

    public function add(Request $request)
    {
        $validated = $request->validate([
            'id_varian' =>
                'required|exists:varian_produk,id_varian',

            'jumlah' =>
                'required|integer|min:1',
        ]);

        $cart = session(
            'pos_cart',
            []
        );

        $stock = (int) DB::table(
            'varian_produk'
        )
            ->where(
                'id_varian',
                $validated['id_varian']
            )
            ->value('stok');

        /*
         * Quantity yang sudah ada
         * dalam cart.
         */
        $currentQuantity =
            (int) (
                $cart[
                    $validated['id_varian']
                ] ?? 0
            );

        /*
         * Quantity baru ditambahkan
         * ke quantity lama.
         */
        $newQuantity =
            $currentQuantity
            + (int) $validated['jumlah'];

        if ($newQuantity > $stock) {
            return back()
                ->withErrors([
                    'stock' =>
                        'Jumlah melebihi stok tersedia. '
                        .'Stok tersedia: '
                        .$stock.'.',
                ])
                ->withInput();
        }

        /*
         * Karena key cart adalah id_varian,
         * varian yang sama otomatis digabung.
         *
         * Contoh:
         *
         * VAR0003 = 1
         * tambah VAR0003 = 2
         *
         * menjadi:
         *
         * VAR0003 = 3
         */

        $cart[
            $validated['id_varian']
        ] = $newQuantity;

        session([
            'pos_cart' => $cart,
        ]);

        return back()->with(
            'success',
            'Produk ditambahkan ke keranjang.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update jumlah item cart
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {
        $validated = $request->validate([
            'jumlah' =>
                'required|integer|min:1',
        ]);

        $stock = (int) DB::table(
            'varian_produk'
        )
            ->where(
                'id_varian',
                $id
            )
            ->value('stok');

        if (
            $validated['jumlah']
            > $stock
        ) {
            return back()->withErrors([
                'stock' =>
                    'Stok tidak mencukupi. '
                    .'Stok tersedia: '
                    .$stock.'.',
            ]);
        }

        $cart = session(
            'pos_cart',
            []
        );

        if (isset($cart[$id])) {
            $cart[$id] =
                (int) $validated['jumlah'];

            session([
                'pos_cart' => $cart,
            ]);
        }

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | Hapus satu item
    |--------------------------------------------------------------------------
    */

    public function remove(string $id)
    {
        $cart = session(
            'pos_cart',
            []
        );

        unset($cart[$id]);

        session([
            'pos_cart' => $cart,
        ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | Pilih member
    |--------------------------------------------------------------------------
    */

    public function member(Request $request)
    {
        $validated = $request->validate([
            'member' =>
                'required|string',
        ]);

        $raw = trim(
            $validated['member']
        );

        $member = DB::table('akun')
            ->where(
                'tipe_akun',
                'CUSTOMER'
            )
            ->where(
                'status_akun',
                'AKTIF'
            )
            ->where(
                function ($query) use (
                    $raw
                ) {
                    $query
                        ->where(
                            'id_akun',
                            strtoupper($raw)
                        )
                        ->orWhere(
                            'username',
                            $raw
                        )
                        ->orWhere(
                            'email',
                            $raw
                        )
                        ->orWhere(
                            'no_telp',
                            $raw
                        );
                }
            )
            ->first();

        if (!$member) {
            return back()->withErrors([
                'member' =>
                    'Member tidak ditemukan.',
            ]);
        }

        session([
            'pos_member' =>
                $member->id_akun,
        ]);

        return back()->with(
            'success',
            'Member '
                .$member->nama
                .' dipilih.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ubah menjadi guest
    |--------------------------------------------------------------------------
    */

    public function clearMember()
    {
        session()->forget(
            'pos_member'
        );

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | Hapus seluruh transaksi
    |--------------------------------------------------------------------------
    */

    public function clear()
    {
        session()->forget([
            'pos_cart',
            'pos_member',
        ]);

        return back()->with(
            'success',
            'Transaksi POS dibersihkan.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */

    public function checkout(
        Request $request
    ) {
        $validated =
            $request->validate([
                'metode_pembayaran' =>
                    'required|in:TUNAI,QR',

                'poin_digunakan' =>
                    'nullable|integer|min:0',
            ]);

        $saleId =
            $this->sales->checkoutOffline(
                Auth::id(),
                session(
                    'pos_cart',
                    []
                ),
                session(
                    'pos_member'
                ),
                $validated[
                    'metode_pembayaran'
                ],
                (int) (
                    $validated[
                        'poin_digunakan'
                    ] ?? 0
                )
            );

        session()->forget([
            'pos_cart',
            'pos_member',
        ]);

        return redirect()
            ->route(
                'kasir.receipt',
                $saleId
            )
            ->with(
                'success',
                'Transaksi berhasil.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Receipt
    |--------------------------------------------------------------------------
    */

    public function receipt(string $id)
    {
        $sale = DB::table(
            'penjualan'
        )
            ->where(
                'id_penjualan',
                $id
            )
            ->where(
                'id_akun',
                Auth::id()
            )
            ->where(
                'kanal_penjualan',
                'OFFLINE'
            )
            ->first();

        abort_if(
            !$sale,
            404
        );

        $member = DB::table(
            'penjualan_member as pm'
        )
            ->join(
                'akun as a',
                'pm.id_akun_customer',
                '=',
                'a.id_akun'
            )
            ->where(
                'pm.id_penjualan',
                $id
            )
            ->select(
                'a.*'
            )
            ->first();

        $details = DB::table(
            'detail_penjualan as d'
        )
            ->join(
                'varian_produk as v',
                'd.id_varian',
                '=',
                'v.id_varian'
            )
            ->join(
                'produk as p',
                'v.id_produk',
                '=',
                'p.id_produk'
            )
            ->where(
                'd.id_penjualan',
                $id
            )
            ->select(
                'd.*',
                'v.ukuran',
                'v.warna',
                'p.nama_produk'
            )
            ->get();

        return view(
            'kasir.receipt',
            compact(
                'sale',
                'member',
                'details'
            )
        );
    }
}