<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PricingService
{
    public function __construct(
        private PointService $points
    ) {}

    public function activeDiscount(string $productId): float
    {
        return (float) (
            DB::table('promo_produk as pp')
                ->join(
                    'promo as pr',
                    'pp.id_promo',
                    '=',
                    'pr.id_promo'
                )
                ->where('pp.id_produk', $productId)
                ->where('pr.tanggal_mulai', '<=', now())
                ->where('pr.tanggal_selesai', '>=', now())
                ->max('pr.persen_diskon')
            ?? 0
        );
    }

    public function variant(string $variantId): ?array
    {
        $variant = DB::table('varian_produk as v')
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
            ->where('v.id_varian', $variantId)
            ->where('p.status_produk', 'AKTIF')
            ->select(
                'v.*',
                'p.nama_produk',
                'p.harga_jual',
                'p.gambar_produk',
                'p.id_produk',
                'k.nama_kategori'
            )
            ->first();

        if (!$variant) {
            return null;
        }

        $discount = $this->activeDiscount(
            $variant->id_produk
        );

        $finalPrice = round(
            (float) $variant->harga_jual
                * (1 - $discount / 100),
            2
        );

        return [
            'row' => $variant,
            'discount' => $discount,
            'unit_price' => $finalPrice,
        ];
    }

    public function calculate(
        array $cart,
        ?string $customerId = null,
        int $requestedPoints = 0
    ): array {
        $items = [];
        $subtotal = 0;

        foreach ($cart as $variantId => $quantity) {
            $quantity = (int) $quantity;

            if ($quantity < 1) {
                continue;
            }

            $price = $this->variant($variantId);

            if (!$price) {
                continue;
            }

            $lineTotal =
                $price['unit_price'] * $quantity;

            $subtotal += $lineTotal;

            $items[] = [
                'variant' => $price['row'],
                'qty' => $quantity,
                'discount' => $price['discount'],
                'unit_price' => $price['unit_price'],
                'line_total' => $lineTotal,
            ];
        }

        $balance = $customerId
            ? $this->points->balance($customerId)
            : 0;

        $maxPointsBySubtotal = (int) floor(
            $subtotal / PointService::POINT_VALUE
        );

        $usedPoints = max(
            0,
            min(
                (int) $requestedPoints,
                $balance,
                $maxPointsBySubtotal
            )
        );

        $pointDiscount =
            $usedPoints * PointService::POINT_VALUE;

        return [
            'items' => $items,

            // subtotal setelah promo
            // sebelum diskon poin
            'subtotal' => $subtotal,

            'point_balance' => $balance,
            'points_used' => $usedPoints,
            'point_discount' => $pointDiscount,

            // total akhir setelah diskon poin
            'total' => max(
                0,
                $subtotal - $pointDiscount
            ),
        ];
    }
}