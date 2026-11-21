<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Kolom tambahan untuk kebutuhan Data Warehouse (UAS)
|--------------------------------------------------------------------------
|
| detail_penjualan.harga_normal  : harga jual sebelum diskon promo
| detail_penjualan.persen_diskon : diskon promo yang dipakai (0 = tanpa promo)
| detail_penjualan.id_promo      : promo yang dipakai (NULL = tanpa promo)
| pembelian_produk.tanggal_diterima : kapan barang diterima (stok bertambah)
|
| Kolom harga_normal dibuat nullable hanya supaya data lama bisa di-backfill.
| Setelah migrasi, aplikasi selalu mengisinya.
*/

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->decimal('harga_normal', 12, 2)
                ->nullable()
                ->after('harga_satuan');

            $table->decimal('persen_diskon', 5, 2)
                ->default(0)
                ->after('harga_normal');

            $table->string('id_promo')
                ->nullable()
                ->after('persen_diskon');

            $table->foreign('id_promo')
                ->references('id_promo')
                ->on('promo')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });

        Schema::table('pembelian_produk', function (Blueprint $table) {
            $table->dateTime('tanggal_diterima')
                ->nullable()
                ->after('tanggal_pembelian');
        });

        // Backfill data lama: harga normal = harga jual produk saat ini.
        DB::statement(
            'UPDATE detail_penjualan d
             JOIN varian_produk v ON v.id_varian = d.id_varian
             JOIN produk p ON p.id_produk = v.id_produk
             SET d.harga_normal = p.harga_jual,
                 d.persen_diskon = ROUND(
                     GREATEST(0, 1 - d.harga_satuan / p.harga_jual) * 100,
                     2
                 )
             WHERE d.harga_normal IS NULL'
        );

        DB::statement(
            "UPDATE pembelian_produk
             SET tanggal_diterima = updated_at
             WHERE status_pembelian = 'DITERIMA'
               AND tanggal_diterima IS NULL"
        );
    }

    public function down(): void
    {
        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropForeign(['id_promo']);
            $table->dropColumn(['harga_normal', 'persen_diskon', 'id_promo']);
        });

        Schema::table('pembelian_produk', function (Blueprint $table) {
            $table->dropColumn('tanggal_diterima');
        });
    }
};
