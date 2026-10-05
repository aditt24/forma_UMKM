<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct(private PricingService $pricing) {}

    public function index()
    {
        $cart = session('cart', []);
        $summary = $this->pricing->calculate(
            $cart,
            Auth::id(),
            0
        );

        return view(
            'customer.cart',
            compact('cart', 'summary')
        );
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'id_varian' => 'required|exists:varian_produk,id_varian',
            'jumlah' => 'required|integer|min:1',
        ]);

        $stock = (int) DB::table('varian_produk')
            ->where('id_varian', $validated['id_varian'])
            ->value('stok');

        $cart = session('cart', []);
        $newQuantity = ($cart[$validated['id_varian']] ?? 0)
            + (int) $validated['jumlah'];

        if ($newQuantity > $stock) {
            return back()->withErrors([
                'jumlah' => 'Jumlah melebihi stok tersedia.',
            ]);
        }

        $cart[$validated['id_varian']] = $newQuantity;
        session(['cart' => $cart]);

        return redirect()
            ->route('customer.cart')
            ->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $stock = (int) DB::table('varian_produk')
            ->where('id_varian', $id)
            ->value('stok');

        if ($validated['jumlah'] > $stock) {
            return back()->withErrors([
                'jumlah' => 'Jumlah melebihi stok tersedia.',
            ]);
        }

        $cart = session('cart', []);

        if (isset($cart[$id])) {
            $cart[$id] = (int) $validated['jumlah'];
            session(['cart' => $cart]);
        }

        return back();
    }

    public function remove(string $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item dihapus.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
