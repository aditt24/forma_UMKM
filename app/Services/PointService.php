<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PointService
{
    public const POINT_VALUE = 500;
    public const EARN_PER_RUPIAH = 10000;

    public function balance(string $customerId): int
    {
        $onlineBalance = (int) DB::table('penjualan')
            ->where('id_akun', $customerId)
            ->where('kanal_penjualan', 'ONLINE')
            ->where('status_pembayaran', 'BERHASIL')
            ->where('status_penjualan', '!=', 'BATAL')
            ->selectRaw(
                'COALESCE(
                    SUM(
                        CAST(poin_didapat AS SIGNED)
                        -
                        CAST(poin_digunakan AS SIGNED)
                    ),
                    0
                ) as saldo'
            )
            ->value('saldo');

        $offlineBalance = (int) DB::table('penjualan_member as pm')
            ->join(
                'penjualan as p',
                'pm.id_penjualan',
                '=',
                'p.id_penjualan'
            )
            ->where('pm.id_akun_customer', $customerId)
            ->where('p.status_pembayaran', 'BERHASIL')
            ->where('p.status_penjualan', '!=', 'BATAL')
            ->selectRaw(
                'COALESCE(
                    SUM(
                        CAST(p.poin_didapat AS SIGNED)
                        -
                        CAST(p.poin_digunakan AS SIGNED)
                    ),
                    0
                ) as saldo'
            )
            ->value('saldo');

        return max(
            0,
            $onlineBalance + $offlineBalance
        );
    }

    public function earnedFromPaid(float $nominal): int
    {
        return max(
            0,
            (int) floor(
                $nominal / self::EARN_PER_RUPIAH
            )
        );
    }
}