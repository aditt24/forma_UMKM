<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Services\PointService;
use App\Services\PricingService;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        private PricingService $pricing,
        private PointService $points,
        private SaleService $sales
    ) {}

    public function show()
    {
        $cart = session('cart', []);

        if (!$cart) {
            return redirect()->route('customer.cart');
        }

        $summary = $this->pricing->calculate(
            $cart,
            Auth::id(),
            0
        );

        $pointBalance = $this->points->balance(Auth::id());

        return view(
            'customer.checkout',
            compact('summary', 'pointBalance')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alamat' => 'required|string|max:1000',
            'poin_digunakan' => 'nullable|integer|min:0',
        ]);

        $saleId = $this->sales->createOnlinePending(
            Auth::id(),
            session('cart', []),
            $validated['alamat'],
            (int) ($validated['poin_digunakan'] ?? 0)
        );

        session()->forget('cart');

        return redirect()->route('customer.payment', $saleId);
    }

    public function payment(string $id)
    {
        $sale = DB::table('penjualan')
            ->where('id_penjualan', $id)
            ->where('id_akun', Auth::id())
            ->where('kanal_penjualan', 'ONLINE')
            ->first();

        abort_if(!$sale, 404);

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
                'p.nama_produk'
            )
            ->get();

        return view(
            'customer.payment',
            compact('sale', 'details')
        );
    }

    public function pay(string $id)
    {
        $this->sales->payOnline($id, Auth::id());

        return redirect()
            ->route('customer.orders.show', $id)
            ->with(
                'success',
                'Pembayaran berhasil. Stok dan poin telah diperbarui.'
            );
    }

    public function fail(string $id)
    {
        $this->sales->failOnlinePayment($id, Auth::id());

        return redirect()
            ->route('customer.orders.show', $id)
            ->with(
                'success',
                'Pembayaran gagal. Stok dan poin tidak berubah.'
            );
    }
}
