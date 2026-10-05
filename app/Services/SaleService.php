<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function __construct(
        private PricingService $pricing,
        private PointService $points
    ) {}

    private function nextId(
        string $table,
        string $column,
        string $prefix,
        int $digits
    ): string {
        $last = DB::table($table)
            ->where($column, 'like', $prefix.'%')
            ->orderByDesc($column)
            ->value($column);

        $number = $last
            ? ((int) substr(
                $last,
                strlen($prefix)
            )) + 1
            : 1;

        return $prefix.str_pad(
            $number,
            $digits,
            '0',
            STR_PAD_LEFT
        );
    }

    public function createOnlinePending(
        string $customerId,
        array $cart,
        string $address,
        int $requestedPoints
    ): string {
        $calculation = $this->pricing->calculate(
            $cart,
            $customerId,
            $requestedPoints
        );

        if (!$calculation['items']) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang kosong.',
            ]);
        }

        return DB::transaction(function () use (
            $customerId,
            $address,
            $calculation
        ) {
            $id = $this->nextId(
                'penjualan',
                'id_penjualan',
                'PJ',
                5
            );

            DB::table('penjualan')->insert([
                'id_penjualan' => $id,
                'id_akun' => $customerId,
                'tanggal_penjualan' => now(),
                'kanal_penjualan' => 'ONLINE',
                'metode_pembayaran' => 'QR',
                'status_pembayaran' => 'MENUNGGU',
                'referensi_pembayaran' => null,
                'nominal_bayar' =>
                    $calculation['total'],
                'status_penjualan' => 'BARU',
                'alamat_pengiriman' => $address,
                'poin_didapat' => 0,
                'poin_digunakan' =>
                    $calculation['points_used'],
                'diskon_poin' =>
                    $calculation['point_discount'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $firstDetailId = $this->nextId(
                'detail_penjualan',
                'id_detail_penjualan',
                'DPJ',
                5
            );

            $detailNumber = (int) substr(
                $firstDetailId,
                3
            );

            foreach ($calculation['items'] as $item) {
                DB::table('detail_penjualan')->insert([
                    'id_detail_penjualan' =>
                        'DPJ'.str_pad(
                            $detailNumber++,
                            5,
                            '0',
                            STR_PAD_LEFT
                        ),
                    'id_penjualan' => $id,
                    'id_varian' =>
                        $item['variant']->id_varian,
                    'jumlah' => $item['qty'],
                    'harga_satuan' =>
                        $item['unit_price'],
                ]);
            }

            return $id;
        });
    }

    public function payOnline(
        string $saleId,
        string $customerId
    ): void {
        DB::transaction(function () use (
            $saleId,
            $customerId
        ) {
            $customer = DB::table('akun')
                ->where('id_akun', $customerId)
                ->where('tipe_akun', 'CUSTOMER')
                ->where('status_akun', 'AKTIF')
                ->lockForUpdate()
                ->first();

            if (!$customer) {
                throw ValidationException::withMessages([
                    'payment' =>
                        'Akun customer tidak aktif atau tidak valid.',
                ]);
            }

            $sale = DB::table('penjualan')
                ->where('id_penjualan', $saleId)
                ->where('id_akun', $customerId)
                ->where('kanal_penjualan', 'ONLINE')
                ->lockForUpdate()
                ->first();

            if (!$sale) {
                abort(404);
            }

            if (
                $sale->status_pembayaran !== 'MENUNGGU'
                ||
                $sale->status_penjualan === 'BATAL'
            ) {
                throw ValidationException::withMessages([
                    'payment' =>
                        'Transaksi tidak dapat dibayar lagi.',
                ]);
            }

            $currentPointBalance =
                $this->points->balance(
                    $customerId
                );

            if (
                $sale->poin_digunakan
                    > $currentPointBalance
            ) {
                throw ValidationException::withMessages([
                    'payment' =>
                        'Saldo poin berubah dan tidak lagi mencukupi. '
                        .'Batalkan pesanan ini lalu checkout kembali.',
                ]);
            }

            $details = DB::table(
                'detail_penjualan'
            )
                ->where(
                    'id_penjualan',
                    $saleId
                )
                ->get();

            foreach ($details as $detail) {
                $variant = DB::table(
                    'varian_produk'
                )
                    ->where(
                        'id_varian',
                        $detail->id_varian
                    )
                    ->lockForUpdate()
                    ->first();

                if (
                    !$variant
                    ||
                    $variant->stok
                        < $detail->jumlah
                ) {
                    throw ValidationException::withMessages([
                        'stock' =>
                            'Stok salah satu varian tidak mencukupi. '
                            .'Silakan batalkan pesanan dan checkout ulang.',
                    ]);
                }
            }

            foreach ($details as $detail) {
                DB::table('varian_produk')
                    ->where(
                        'id_varian',
                        $detail->id_varian
                    )
                    ->decrement(
                        'stok',
                        $detail->jumlah,
                        [
                            'updated_at' => now(),
                        ]
                    );
            }

            /*
             * Basis poin = subtotal setelah promo
             * sebelum redeem poin.
             */
            $subtotalAfterPromo = (float)
                DB::table('detail_penjualan')
                    ->where(
                        'id_penjualan',
                        $saleId
                    )
                    ->selectRaw(
                        'COALESCE(
                            SUM(jumlah * harga_satuan),
                            0
                        ) as subtotal'
                    )
                    ->value('subtotal');

            $earned =
                $this->points->earnedFromPaid(
                    $subtotalAfterPromo
                );

            DB::table('penjualan')
                ->where(
                    'id_penjualan',
                    $saleId
                )
                ->update([
                    'status_pembayaran' =>
                        'BERHASIL',
                    'referensi_pembayaran' =>
                        'QR-'.$saleId.'-'
                        .now()->format('His'),
                    'status_penjualan' =>
                        'DIPROSES',
                    'poin_didapat' =>
                        $earned,
                    'updated_at' => now(),
                ]);
        });
    }

    public function failOnlinePayment(
        string $saleId,
        string $customerId
    ): void {
        $updated = DB::table('penjualan')
            ->where('id_penjualan', $saleId)
            ->where('id_akun', $customerId)
            ->where('kanal_penjualan', 'ONLINE')
            ->where(
                'status_pembayaran',
                'MENUNGGU'
            )
            ->where(
                'status_penjualan',
                'BARU'
            )
            ->update([
                'status_pembayaran' => 'GAGAL',
                'status_penjualan' => 'BATAL',
                'poin_didapat' => 0,
                'updated_at' => now(),
            ]);

        if (!$updated) {
            throw ValidationException::withMessages([
                'payment' =>
                    'Pembayaran hanya dapat digagalkan saat transaksi masih menunggu.',
            ]);
        }
    }

    public function cancelPending(
        string $saleId,
        string $customerId
    ): void {
        $updated = DB::table('penjualan')
            ->where('id_penjualan', $saleId)
            ->where('id_akun', $customerId)
            ->where('kanal_penjualan', 'ONLINE')
            ->where(
                'status_pembayaran',
                'MENUNGGU'
            )
            ->where(
                'status_penjualan',
                'BARU'
            )
            ->update([
                'status_penjualan' => 'BATAL',
                'updated_at' => now(),
            ]);

        if (!$updated) {
            throw ValidationException::withMessages([
                'cancel' =>
                    'Pesanan hanya dapat dibatalkan sebelum pembayaran berhasil.',
            ]);
        }
    }

    public function checkoutOffline(
        string $cashierId,
        array $cart,
        ?string $memberId,
        string $method,
        int $requestedPoints
    ): string {
        $requestedPoints = $memberId
            ? $requestedPoints
            : 0;

        return DB::transaction(function () use (
            $cashierId,
            $cart,
            $memberId,
            $method,
            $requestedPoints
        ) {
            if ($memberId) {
                $member = DB::table('akun')
                    ->where(
                        'id_akun',
                        $memberId
                    )
                    ->where(
                        'tipe_akun',
                        'CUSTOMER'
                    )
                    ->where(
                        'status_akun',
                        'AKTIF'
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$member) {
                    throw ValidationException::withMessages([
                        'member' =>
                            'Member tidak valid atau sedang nonaktif.',
                    ]);
                }
            }

            $calculation =
                $this->pricing->calculate(
                    $cart,
                    $memberId,
                    $requestedPoints
                );

            if (!$calculation['items']) {
                throw ValidationException::withMessages([
                    'cart' =>
                        'Keranjang POS kosong.',
                ]);
            }

            foreach (
                $calculation['items'] as $item
            ) {
                $variant = DB::table(
                    'varian_produk'
                )
                    ->where(
                        'id_varian',
                        $item['variant']->id_varian
                    )
                    ->lockForUpdate()
                    ->first();

                if (
                    !$variant
                    ||
                    $variant->stok
                        < $item['qty']
                ) {
                    throw ValidationException::withMessages([
                        'stock' =>
                            'Stok '
                            .$item['variant']->nama_produk
                            .' '
                            .$item['variant']->ukuran
                            .' tidak mencukupi.',
                    ]);
                }
            }

            $id = $this->nextId(
                'penjualan',
                'id_penjualan',
                'PJ',
                5
            );

            /*
             * Poin dihitung dari subtotal setelah promo.
             * BUKAN dari total setelah redeem.
             */
            $earned = $memberId
                ? $this->points->earnedFromPaid(
                    (float)
                    $calculation['subtotal']
                )
                : 0;

            DB::table('penjualan')->insert([
                'id_penjualan' => $id,
                'id_akun' => $cashierId,
                'tanggal_penjualan' => now(),
                'kanal_penjualan' => 'OFFLINE',
                'metode_pembayaran' => $method,
                'status_pembayaran' => 'BERHASIL',

                'referensi_pembayaran' =>
                    $method === 'QR'
                        ? 'QR-'.$id.'-'
                            .now()->format('His')
                        : null,

                'nominal_bayar' =>
                    $calculation['total'],

                'status_penjualan' => 'SELESAI',
                'alamat_pengiriman' => null,

                'poin_didapat' => $earned,

                'poin_digunakan' =>
                    $memberId
                        ? $calculation[
                            'points_used'
                        ]
                        : 0,

                'diskon_poin' =>
                    $memberId
                        ? $calculation[
                            'point_discount'
                        ]
                        : 0,

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($memberId) {
                DB::table(
                    'penjualan_member'
                )->insert([
                    'id_penjualan' => $id,
                    'id_akun_customer' =>
                        $memberId,
                ]);
            }

            $firstDetailId = $this->nextId(
                'detail_penjualan',
                'id_detail_penjualan',
                'DPJ',
                5
            );

            $detailNumber = (int) substr(
                $firstDetailId,
                3
            );

            foreach (
                $calculation['items'] as $item
            ) {
                DB::table(
                    'detail_penjualan'
                )->insert([
                    'id_detail_penjualan' =>
                        'DPJ'.str_pad(
                            $detailNumber++,
                            5,
                            '0',
                            STR_PAD_LEFT
                        ),

                    'id_penjualan' => $id,

                    'id_varian' =>
                        $item['variant']->id_varian,

                    'jumlah' =>
                        $item['qty'],

                    'harga_satuan' =>
                        $item['unit_price'],
                ]);

                DB::table('varian_produk')
                    ->where(
                        'id_varian',
                        $item['variant']->id_varian
                    )
                    ->decrement(
                        'stok',
                        $item['qty'],
                        [
                            'updated_at' => now(),
                        ]
                    );
            }

            return $id;
        });
    }
}