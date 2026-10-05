<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function dashboard()
    {
        $cashierId = Auth::id();

        $todaySales = DB::table('penjualan')
            ->where('id_akun', $cashierId)
            ->where('kanal_penjualan', 'OFFLINE')
            ->whereDate('tanggal_penjualan', today())
            ->where('status_pembayaran', 'BERHASIL')
            ->sum('nominal_bayar');

        $todayCount = DB::table('penjualan')
            ->where('id_akun', $cashierId)
            ->where('kanal_penjualan', 'OFFLINE')
            ->whereDate('tanggal_penjualan', today())
            ->count();

        $recent = DB::table('penjualan')
            ->where('id_akun', $cashierId)
            ->where('kanal_penjualan', 'OFFLINE')
            ->orderByDesc('tanggal_penjualan')
            ->limit(8)
            ->get();

        return view(
            'kasir.dashboard',
            compact('todaySales', 'todayCount', 'recent')
        );
    }

    public function history()
    {
        $sales = DB::table('penjualan')
            ->where('id_akun', Auth::id())
            ->where('kanal_penjualan', 'OFFLINE')
            ->orderByDesc('tanggal_penjualan')
            ->get();

        return view('kasir.history', compact('sales'));
    }
}
