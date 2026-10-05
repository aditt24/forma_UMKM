<?php

namespace App\Http\Controllers;

use App\Services\PointService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct(
        private PointService $points,
        private PricingService $pricing
    ) {}

    /**
     * Query dasar produk aktif yang masih memiliki stok.
     */
    private function inStockProductsQuery()
    {
        return DB::table('produk as p')
            ->where('p.status_produk', 'AKTIF')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('varian_produk as vs')
                    ->whereColumn(
                        'vs.id_produk',
                        'p.id_produk'
                    )
                    ->where('vs.stok', '>', 0);
            });
    }

    /**
     * Dashboard Customer.
     */
    public function dashboard()
    {
        $customerId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Saldo Poin
        |--------------------------------------------------------------------------
        */

        $pointBalance = $this->points->balance(
            $customerId
        );

        /*
        |--------------------------------------------------------------------------
        | Top 3 Best Seller
        |--------------------------------------------------------------------------
        |
        | Produk dihitung berdasarkan total unit yang terjual
        | pada transaksi yang pembayarannya berhasil.
        |
        */

        $products = DB::table('detail_penjualan as d')
            ->join(
                'penjualan as pj',
                'd.id_penjualan',
                '=',
                'pj.id_penjualan'
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

            // Hanya transaksi berhasil
            ->where(
                'pj.status_pembayaran',
                'BERHASIL'
            )

            // Transaksi batal tidak dihitung
            ->where(
                'pj.status_penjualan',
                '!=',
                'BATAL'
            )

            // Produk harus masih aktif
            ->where(
                'p.status_produk',
                'AKTIF'
            )

            // Produk harus masih memiliki stok
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('varian_produk as stok_v')
                    ->whereColumn(
                        'stok_v.id_produk',
                        'p.id_produk'
                    )
                    ->where(
                        'stok_v.stok',
                        '>',
                        0
                    );
            })

            ->select(
                'p.id_produk',
                'p.nama_produk',
                'p.harga_jual',
                'p.gambar_produk',
                DB::raw(
                    'SUM(d.jumlah) as total_terjual'
                )
            )

            ->groupBy(
                'p.id_produk',
                'p.nama_produk',
                'p.harga_jual',
                'p.gambar_produk'
            )

            // Produk paling banyak terjual di atas
            ->orderByDesc('total_terjual')

            // Jika jumlah sama, urutkan berdasarkan ID
            ->orderBy('p.id_produk')

            // Dashboard hanya menampilkan 3 produk
            ->limit(3)

            ->get();

        /*
        |--------------------------------------------------------------------------
        | Promo Aktif Best Seller
        |--------------------------------------------------------------------------
        */

        foreach ($products as $product) {
            $product->discount =
                $this->pricing->activeDiscount(
                    $product->id_produk
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Jumlah Pesanan Online Customer
        |--------------------------------------------------------------------------
        */

        $orderCount = DB::table('penjualan')
            ->where(
                'id_akun',
                $customerId
            )
            ->where(
                'kanal_penjualan',
                'ONLINE'
            )
            ->count();

        return view(
            'customer.dashboard',
            compact(
                'products',
                'pointBalance',
                'orderCount'
            )
        );
    }

    /**
     * Katalog Customer.
     */
    public function catalog(Request $request)
    {
        $query = $this->inStockProductsQuery()
            ->join(
                'kategori_produk as k',
                'p.id_kategori',
                '=',
                'k.id_kategori'
            )
            ->select(
                'p.*',
                'k.nama_kategori'
            );

        /*
        |--------------------------------------------------------------------------
        | Pencarian
        |--------------------------------------------------------------------------
        */

        if ($request->filled('q')) {
            $query->where(
                'p.nama_produk',
                'like',
                '%' . $request->q . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Kategori
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kategori')) {
            $query->where(
                'p.id_kategori',
                $request->kategori
            );
        }

        $products = $query
            ->orderBy('p.nama_produk')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Promo Aktif
        |--------------------------------------------------------------------------
        */

        foreach ($products as $product) {
            $product->discount =
                $this->pricing->activeDiscount(
                    $product->id_produk
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Daftar Kategori
        |--------------------------------------------------------------------------
        */

        $categories = DB::table('kategori_produk')
            ->orderBy('nama_kategori')
            ->get();

        return view(
            'customer.catalog',
            compact(
                'products',
                'categories'
            )
        );
    }

    /**
     * Promo Customer.
     */
    public function promotions()
    {
        $products = $this->inStockProductsQuery()
            ->join(
                'promo_produk as pp',
                'p.id_produk',
                '=',
                'pp.id_produk'
            )
            ->join(
                'promo as pr',
                'pp.id_promo',
                '=',
                'pr.id_promo'
            )
            ->where(
                'pr.tanggal_mulai',
                '<=',
                now()
            )
            ->where(
                'pr.tanggal_selesai',
                '>=',
                now()
            )
            ->select(
                'p.*',
                DB::raw(
                    'MAX(pr.persen_diskon) as discount'
                )
            )
            ->groupBy(
                'p.id_produk',
                'p.id_kategori',
                'p.nama_produk',
                'p.harga_jual',
                'p.deskripsi',
                'p.gambar_produk',
                'p.status_produk',
                'p.created_at',
                'p.updated_at'
            )
            ->orderByDesc('discount')
            ->get();

        return view(
            'customer.promotions',
            compact('products')
        );
    }

    /**
     * Detail Produk.
     */
    public function product(string $id)
    {
        $product = $this->inStockProductsQuery()
            ->join(
                'kategori_produk as k',
                'p.id_kategori',
                '=',
                'k.id_kategori'
            )
            ->where(
                'p.id_produk',
                $id
            )
            ->select(
                'p.*',
                'k.nama_kategori'
            )
            ->first();

        abort_if(!$product, 404);

        /*
        |--------------------------------------------------------------------------
        | Varian yang Masih Tersedia
        |--------------------------------------------------------------------------
        */

        $variants = DB::table('varian_produk')
            ->where(
                'id_produk',
                $id
            )
            ->where(
                'stok',
                '>',
                0
            )
            ->orderBy('warna')
            ->orderBy('ukuran')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Harga Setelah Promo
        |--------------------------------------------------------------------------
        */

        $discount =
            $this->pricing->activeDiscount(
                $id
            );

        $final = round(
            $product->harga_jual
                * (
                    1
                    - $discount / 100
                ),
            2
        );

        return view(
            'customer.product',
            compact(
                'product',
                'variants',
                'discount',
                'final'
            )
        );
    }
}