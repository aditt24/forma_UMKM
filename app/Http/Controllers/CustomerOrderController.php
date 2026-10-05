<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Services\SaleService;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    public function __construct(private SaleService $sales) {}

    public function index()
    {
        $orders = DB::table('penjualan')
            ->where('id_akun', Auth::id())
            ->where('kanal_penjualan', 'ONLINE')
            ->orderByDesc('tanggal_penjualan')
            ->get();

        return view(
            'customer.orders.index',
            compact('orders')
        );
    }

    public function show(string $id)
    {
        $order = DB::table('penjualan')
            ->where('id_penjualan', $id)
            ->where('id_akun', Auth::id())
            ->where('kanal_penjualan', 'ONLINE')
            ->first();

        abort_if(!$order, 404);

        $details = DB::table('detail_penjualan as d')
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
            ->where('d.id_penjualan', $id)
            ->select(
                'd.*',
                'v.ukuran',
                'v.warna',
                'p.nama_produk',
                'p.gambar_produk'
            )
            ->get();

        return view(
            'customer.orders.show',
            compact('order', 'details')
        );
    }

    public function cancel(string $id)
    {
        $this->sales->cancelPending($id, Auth::id());

        return back()->with('success', 'Pesanan dibatalkan.');
    }
}
