<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PrototypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {

            $now = now();

            /*
            |--------------------------------------------------------------------------
            | 1. AKUN
            |--------------------------------------------------------------------------
            |
            | 2 Customer
            | 2 Kasir
            | 1 Admin
            |
            | Username/password dibuat mudah untuk demo.
            |
            */

            DB::table('akun')->insert([
                [
                    'id_akun' => 'CUST123',
                    'nama' => 'Budi Santoso',
                    'username' => 'budi',
                    'password_hash' => Hash::make('budi123'),
                    'email' => 'budi.santoso@example.com',
                    'no_telp' => '081234567812',
                    'alamat' => 'Jl. Ketintang Baru, Surabaya',
                    'tipe_akun' => 'CUSTOMER',
                    'status_akun' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(45),
                    'updated_at' => $now->copy()->subDays(45),
                ],
                [
                    'id_akun' => 'CUST124',
                    'nama' => 'Alya Putri',
                    'username' => 'alya',
                    'password_hash' => Hash::make('alya123'),
                    'email' => 'alya.putri@example.com',
                    'no_telp' => '082112345678',
                    'alamat' => 'Jl. Dharmawangsa, Surabaya',
                    'tipe_akun' => 'CUSTOMER',
                    'status_akun' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(32),
                    'updated_at' => $now->copy()->subDays(20),
                ],
                [
                    'id_akun' => 'KSR001',
                    'nama' => 'Siti Amalia',
                    'username' => 'siti',
                    'password_hash' => Hash::make('kasir123'),
                    'email' => 'siti.amalia@example.com',
                    'no_telp' => '085712349876',
                    'alamat' => 'Jl. Rungkut Asri, Surabaya',
                    'tipe_akun' => 'KASIR',
                    'status_akun' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(90),
                    'updated_at' => $now->copy()->subDays(12),
                ],
                [
                    'id_akun' => 'KSR002',
                    'nama' => 'Rizky Ramadhan',
                    'username' => 'rizky',
                    'password_hash' => Hash::make('kasir123'),
                    'email' => 'rizky.ramadhan@example.com',
                    'no_telp' => '081298765431',
                    'alamat' => 'Jl. Manyar Kertoarjo, Surabaya',
                    'tipe_akun' => 'KASIR',
                    'status_akun' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(64),
                    'updated_at' => $now->copy()->subDays(9),
                ],
                [
                    'id_akun' => 'ADM021',
                    'nama' => 'Nanda Pratama',
                    'username' => 'admin',
                    'password_hash' => Hash::make('admin123'),
                    'email' => 'admin@forma.local',
                    'no_telp' => '087812345609',
                    'alamat' => 'Jl. Ahmad Yani, Surabaya',
                    'tipe_akun' => 'ADMIN',
                    'status_akun' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(120),
                    'updated_at' => $now,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 2. KATEGORI PRODUK
            |--------------------------------------------------------------------------
            */

            DB::table('kategori_produk')->insert([
                [
                    'id_kategori' => 'KTG001',
                    'nama_kategori' => 'Kaos',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id_kategori' => 'KTG002',
                    'nama_kategori' => 'Hoodie',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id_kategori' => 'KTG003',
                    'nama_kategori' => 'Sweater',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id_kategori' => 'KTG004',
                    'nama_kategori' => 'Kemeja',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id_kategori' => 'KTG005',
                    'nama_kategori' => 'Celana',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 3. PRODUK
            |--------------------------------------------------------------------------
            |
            | File gambar harus tersedia di:
            | public/images/products/
            |
            */

            DB::table('produk')->insert([
                [
                    'id_produk' => 'PRD001',
                    'id_kategori' => 'KTG001',
                    'nama_produk' => 'Basic T-Shirt',
                    'harga_jual' => 119000,
                    'deskripsi' =>
                        'Kaos basic dengan potongan simpel untuk pemakaian sehari-hari.',
                    'gambar_produk' => 'basic-tshirt.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(85),
                    'updated_at' => $now,
                ],
                [
                    'id_produk' => 'PRD002',
                    'id_kategori' => 'KTG001',
                    'nama_produk' => 'Oversized T-Shirt',
                    'harga_jual' => 149000,
                    'deskripsi' =>
                        'T-shirt oversized dengan potongan santai dan nyaman.',
                    'gambar_produk' => 'oversized-tshirt.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(78),
                    'updated_at' => $now,
                ],
                [
                    'id_produk' => 'PRD003',
                    'id_kategori' => 'KTG001',
                    'nama_produk' => 'Pocket T-Shirt',
                    'harga_jual' => 139000,
                    'deskripsi' =>
                        'T-shirt minimalis dengan detail pocket pada bagian depan.',
                    'gambar_produk' => 'pocket-tshirt.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(72),
                    'updated_at' => $now,
                ],
                [
                    'id_produk' => 'PRD004',
                    'id_kategori' => 'KTG002',
                    'nama_produk' => 'Pullover Hoodie',
                    'harga_jual' => 259000,
                    'deskripsi' =>
                        'Hoodie pullover untuk tampilan kasual dan nyaman.',
                    'gambar_produk' => 'hoodie.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(67),
                    'updated_at' => $now,
                ],
                [
                    'id_produk' => 'PRD005',
                    'id_kategori' => 'KTG003',
                    'nama_produk' => 'Crewneck Sweatshirt',
                    'harga_jual' => 229000,
                    'deskripsi' =>
                        'Crewneck sweatshirt untuk kebutuhan daily wear.',
                    'gambar_produk' => 'crewneck.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(61),
                    'updated_at' => $now,
                ],
                [
                    'id_produk' => 'PRD006',
                    'id_kategori' => 'KTG004',
                    'nama_produk' => 'Casual Shirt',
                    'harga_jual' => 189000,
                    'deskripsi' =>
                        'Kemeja kasual dengan tampilan clean untuk aktivitas sehari-hari.',
                    'gambar_produk' => 'casual-shirt.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(55),
                    'updated_at' => $now,
                ],
                [
                    'id_produk' => 'PRD007',
                    'id_kategori' => 'KTG004',
                    'nama_produk' => 'Overshirt',
                    'harga_jual' => 219000,
                    'deskripsi' =>
                        'Overshirt ringan yang cocok digunakan sebagai layering.',
                    'gambar_produk' => 'overshirt.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(48),
                    'updated_at' => $now,
                ],
                [
                    'id_produk' => 'PRD008',
                    'id_kategori' => 'KTG005',
                    'nama_produk' => 'Cargo Pants',
                    'harga_jual' => 229000,
                    'deskripsi' =>
                        'Celana cargo dengan potongan kasual dan kantong fungsional.',
                    'gambar_produk' => 'cargo-pants.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(42),
                    'updated_at' => $now,
                ],
                [
                    'id_produk' => 'PRD009',
                    'id_kategori' => 'KTG005',
                    'nama_produk' => 'Straight Pants',
                    'harga_jual' => 209000,
                    'deskripsi' =>
                        'Straight pants dengan potongan clean untuk berbagai gaya.',
                    'gambar_produk' => 'straight-pants.jpg',
                    'status_produk' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(36),
                    'updated_at' => $now,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 4. VARIAN PRODUK
            |--------------------------------------------------------------------------
            |
            | Setiap produk:
            | S, M, L, XL
            |
            | Stok dibuat bervariasi dan beberapa berada
            | pada kondisi low stock (stok <= stok_minimum).
            |
            */

            $variantData = [
                'PRD001' => [
                    'warna' => 'hitam',
                    'stok' => [18, 16, 14, 12],
                ],
                'PRD002' => [
                    'warna' => 'hitam',
                    'stok' => [9, 13, 11, 8],
                ],
                'PRD003' => [
                    'warna' => 'putih',
                    'stok' => [8, 7, 6, 4],
                ],
                'PRD004' => [
                    'warna' => 'hitam',
                    'stok' => [12, 10, 8, 6],
                ],
                'PRD005' => [
                    'warna' => 'hitam',
                    'stok' => [14, 12, 9, 7],
                ],
                'PRD006' => [
                    'warna' => 'putih',
                    'stok' => [10, 8, 7, 5],
                ],
                'PRD007' => [
                    'warna' => 'khaki',
                    'stok' => [8, 7, 6, 4],
                ],
                'PRD008' => [
                    'warna' => 'khaki',
                    'stok' => [14, 11, 8, 6],
                ],
                'PRD009' => [
                    'warna' => 'hitam',
                    'stok' => [13, 10, 9, 7],
                ],
            ];

            $sizes = ['S', 'M', 'L', 'XL'];

            $variantNumber = 1;

            foreach ($variantData as $productId => $data) {

                foreach ($sizes as $index => $size) {

                    DB::table('varian_produk')->insert([
                        'id_varian' =>
                            'VAR'
                            .str_pad(
                                $variantNumber,
                                4,
                                '0',
                                STR_PAD_LEFT
                            ),

                        'id_produk' => $productId,
                        'ukuran' => $size,
                        'warna' => $data['warna'],
                        'stok' => $data['stok'][$index],
                        'stok_minimum' => 5,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $variantNumber++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | REFERENSI VARIAN
            |--------------------------------------------------------------------------
            |
            | PRD001 -> VAR0001 - VAR0004
            | PRD002 -> VAR0005 - VAR0008
            | PRD003 -> VAR0009 - VAR0012
            | PRD004 -> VAR0013 - VAR0016
            | PRD005 -> VAR0017 - VAR0020
            | PRD006 -> VAR0021 - VAR0024
            | PRD007 -> VAR0025 - VAR0028
            | PRD008 -> VAR0029 - VAR0032
            | PRD009 -> VAR0033 - VAR0036
            |
            */

            /*
            |--------------------------------------------------------------------------
            | 5. SUPPLIER
            |--------------------------------------------------------------------------
            */

            DB::table('supplier')->insert([
                [
                    'id_supplier' => 'SUP001',
                    'nama_supplier' =>
                        'Nusantara Apparel Supply',
                    'no_telp' => '081357924681',
                    'alamat' =>
                        'Jl. Margomulyo Indah, Surabaya',
                    'created_at' =>
                        $now->copy()->subDays(150),
                    'updated_at' => $now,
                ],
                [
                    'id_supplier' => 'SUP002',
                    'nama_supplier' =>
                        'Urban Garment Wholesale',
                    'no_telp' => '082233764915',
                    'alamat' =>
                        'Jl. Kopo, Bandung',
                    'created_at' =>
                        $now->copy()->subDays(130),
                    'updated_at' => $now,
                ],
                [
                    'id_supplier' => 'SUP003',
                    'nama_supplier' =>
                        'Dailywear Distributor',
                    'no_telp' => '085889214376',
                    'alamat' =>
                        'Jl. Pademangan, Jakarta Utara',
                    'created_at' =>
                        $now->copy()->subDays(110),
                    'updated_at' => $now,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 6. PEMBELIAN PRODUK
            |--------------------------------------------------------------------------
            |
            | PB0001 = DITERIMA
            | PB0002 = DITERIMA
            | PB0003 = DIPESAN
            |
            */

            DB::table('pembelian_produk')->insert([
                [
                    'id_pembelian' => 'PB0001',
                    'id_supplier' => 'SUP001',
                    'id_akun' => 'ADM021',
                    'tanggal_pembelian' =>
                        $now->copy()->subDays(18),
                    'status_pembelian' => 'DITERIMA',
                    'created_at' =>
                        $now->copy()->subDays(18),
                    'updated_at' =>
                        $now->copy()->subDays(15),
                ],
                [
                    'id_pembelian' => 'PB0002',
                    'id_supplier' => 'SUP002',
                    'id_akun' => 'ADM021',
                    'tanggal_pembelian' =>
                        $now->copy()->subDays(9),
                    'status_pembelian' => 'DITERIMA',
                    'created_at' =>
                        $now->copy()->subDays(9),
                    'updated_at' =>
                        $now->copy()->subDays(7),
                ],
                [
                    'id_pembelian' => 'PB0003',
                    'id_supplier' => 'SUP003',
                    'id_akun' => 'ADM021',
                    'tanggal_pembelian' =>
                        $now->copy()->subDay(),
                    'status_pembelian' => 'DIPESAN',
                    'created_at' =>
                        $now->copy()->subDay(),
                    'updated_at' =>
                        $now->copy()->subDay(),
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 7. DETAIL PEMBELIAN
            |--------------------------------------------------------------------------
            */

            DB::table('detail_pembelian')->insert([
                [
                    'id_detail_pembelian' => 'DPB00001',
                    'id_pembelian' => 'PB0001',
                    'id_varian' => 'VAR0001',
                    'jumlah' => 20,
                    'harga_satuan' => 70000,
                ],
                [
                    'id_detail_pembelian' => 'DPB00002',
                    'id_pembelian' => 'PB0001',
                    'id_varian' => 'VAR0006',
                    'jumlah' => 15,
                    'harga_satuan' => 90000,
                ],
                [
                    'id_detail_pembelian' => 'DPB00003',
                    'id_pembelian' => 'PB0001',
                    'id_varian' => 'VAR0015',
                    'jumlah' => 12,
                    'harga_satuan' => 155000,
                ],

                [
                    'id_detail_pembelian' => 'DPB00004',
                    'id_pembelian' => 'PB0002',
                    'id_varian' => 'VAR0021',
                    'jumlah' => 10,
                    'harga_satuan' => 110000,
                ],
                [
                    'id_detail_pembelian' => 'DPB00005',
                    'id_pembelian' => 'PB0002',
                    'id_varian' => 'VAR0025',
                    'jumlah' => 8,
                    'harga_satuan' => 125000,
                ],
                [
                    'id_detail_pembelian' => 'DPB00006',
                    'id_pembelian' => 'PB0002',
                    'id_varian' => 'VAR0029',
                    'jumlah' => 15,
                    'harga_satuan' => 140000,
                ],

                [
                    'id_detail_pembelian' => 'DPB00007',
                    'id_pembelian' => 'PB0003',
                    'id_varian' => 'VAR0009',
                    'jumlah' => 10,
                    'harga_satuan' => 85000,
                ],
                [
                    'id_detail_pembelian' => 'DPB00008',
                    'id_pembelian' => 'PB0003',
                    'id_varian' => 'VAR0033',
                    'jumlah' => 10,
                    'harga_satuan' => 130000,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 8. PENGELUARAN
            |--------------------------------------------------------------------------
            |
            | Setiap pembelian produk dari supplier memiliki
            | satu pengeluaran dengan jenis PEMBELIAN_PRODUK.
            |
            | id_pembelian UNIQUE memastikan satu transaksi
            | pembelian tidak dicatat dua kali.
            |
            | Pengeluaran operasional memakai id_pembelian = null.
            |
            */

            DB::table('pengeluaran')->insert([
                /*
                |--------------------------------------------------------------
                | Pembelian Produk
                |--------------------------------------------------------------
                */

                [
                    'id_pengeluaran' => 'PNG0001',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => 'PB0001',

                    'tanggal_pengeluaran' =>
                        $now->copy()->subDays(18),

                    'jenis_pengeluaran' =>
                        'PEMBELIAN_PRODUK',

                    // 20 × 70.000
                    // 15 × 90.000
                    // 12 × 155.000
                    // = 4.610.000
                    'nominal' => 4610000,

                    'keterangan' =>
                        'Pembelian stok dari Nusantara Apparel Supply',

                    'created_at' =>
                        $now->copy()->subDays(18),

                    'updated_at' =>
                        $now->copy()->subDays(15),
                ],
                [
                    'id_pengeluaran' => 'PNG0002',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => 'PB0002',

                    'tanggal_pengeluaran' =>
                        $now->copy()->subDays(9),

                    'jenis_pengeluaran' =>
                        'PEMBELIAN_PRODUK',

                    // 10 × 110.000
                    // 8 × 125.000
                    // 15 × 140.000
                    // = 4.200.000
                    'nominal' => 4200000,

                    'keterangan' =>
                        'Pembelian stok dari Urban Garment Wholesale',

                    'created_at' =>
                        $now->copy()->subDays(9),

                    'updated_at' =>
                        $now->copy()->subDays(7),
                ],
                [
                    'id_pengeluaran' => 'PNG0003',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => 'PB0003',

                    'tanggal_pengeluaran' =>
                        $now->copy()->subDay(),

                    'jenis_pengeluaran' =>
                        'PEMBELIAN_PRODUK',

                    // 10 × 85.000
                    // 10 × 130.000
                    // = 2.150.000
                    'nominal' => 2150000,

                    'keterangan' =>
                        'Pemesanan stok dari Dailywear Distributor',

                    'created_at' =>
                        $now->copy()->subDay(),

                    'updated_at' =>
                        $now->copy()->subDay(),
                ],

                /*
                |--------------------------------------------------------------
                | Operasional
                |--------------------------------------------------------------
                */

                [
                    'id_pengeluaran' => 'PNG0004',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => null,

                    'tanggal_pengeluaran' =>
                        $now->copy()->subDays(12),

                    'jenis_pengeluaran' => 'INTERNET',
                    'nominal' => 350000,

                    'keterangan' =>
                        'Internet toko bulan berjalan',

                    'created_at' =>
                        $now->copy()->subDays(12),

                    'updated_at' =>
                        $now->copy()->subDays(12),
                ],
                [
                    'id_pengeluaran' => 'PNG0005',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => null,

                    'tanggal_pengeluaran' =>
                        $now->copy()->subDays(8),

                    'jenis_pengeluaran' => 'SEWA',
                    'nominal' => 1800000,

                    'keterangan' =>
                        'Sewa tempat usaha bulan berjalan',

                    'created_at' =>
                        $now->copy()->subDays(8),

                    'updated_at' =>
                        $now->copy()->subDays(8),
                ],
                [
                    'id_pengeluaran' => 'PNG0006',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => null,

                    'tanggal_pengeluaran' =>
                        $now->copy()->subDays(4),

                    'jenis_pengeluaran' => 'ONGKIR',
                    'nominal' => 225000,

                    'keterangan' =>
                        'Biaya pengiriman dan pengambilan barang',

                    'created_at' =>
                        $now->copy()->subDays(4),

                    'updated_at' =>
                        $now->copy()->subDays(4),
                ],
                [
                    'id_pengeluaran' => 'PNG0007',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => null,

                    'tanggal_pengeluaran' =>
                        $now->copy()->subDays(2),

                    'jenis_pengeluaran' => 'LISTRIK',
                    'nominal' => 475000,

                    'keterangan' =>
                        'Tagihan listrik toko bulan berjalan',

                    'created_at' =>
                        $now->copy()->subDays(2),

                    'updated_at' =>
                        $now->copy()->subDays(2),
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 9. PROMO
            |--------------------------------------------------------------------------
            |
            | PRM001 = aktif
            | PRM002 = aktif
            | PRM003 = sudah berakhir
            | PRM004 = akan datang
            |
            */

            DB::table('promo')->insert([
                [
                    'id_promo' => 'PRM001',
                    'nama_promo' =>
                        'Everyday Essentials',

                    'persen_diskon' => 10,

                    'tanggal_mulai' =>
                        $now->copy()->subDays(10),

                    'tanggal_selesai' =>
                        $now->copy()->addDays(12),

                    'created_at' =>
                        $now->copy()->subDays(12),

                    'updated_at' => $now,
                ],
                [
                    'id_promo' => 'PRM002',
                    'nama_promo' =>
                        'Outerwear Week',

                    'persen_diskon' => 15,

                    'tanggal_mulai' =>
                        $now->copy()->subDays(5),

                    'tanggal_selesai' =>
                        $now->copy()->addDays(8),

                    'created_at' =>
                        $now->copy()->subDays(7),

                    'updated_at' => $now,
                ],
                [
                    'id_promo' => 'PRM003',
                    'nama_promo' =>
                        'Weekend Bottoms',

                    'persen_diskon' => 20,

                    'tanggal_mulai' =>
                        $now->copy()->subDays(30),

                    'tanggal_selesai' =>
                        $now->copy()->subDays(20),

                    'created_at' =>
                        $now->copy()->subDays(32),

                    'updated_at' =>
                        $now->copy()->subDays(20),
                ],
                [
                    'id_promo' => 'PRM004',
                    'nama_promo' =>
                        'Payday Bottoms',

                    'persen_diskon' => 12,

                    'tanggal_mulai' =>
                        $now->copy()->addDays(5),

                    'tanggal_selesai' =>
                        $now->copy()->addDays(12),

                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 10. PROMO PRODUK
            |--------------------------------------------------------------------------
            */

            DB::table('promo_produk')->insert([
                /*
                | Promo aktif 10%
                */

                [
                    'id_promo' => 'PRM001',
                    'id_produk' => 'PRD001',
                ],
                [
                    'id_promo' => 'PRM001',
                    'id_produk' => 'PRD002',
                ],
                [
                    'id_promo' => 'PRM001',
                    'id_produk' => 'PRD006',
                ],

                /*
                | Promo aktif 15%
                */

                [
                    'id_promo' => 'PRM002',
                    'id_produk' => 'PRD004',
                ],
                [
                    'id_promo' => 'PRM002',
                    'id_produk' => 'PRD005',
                ],
                [
                    'id_promo' => 'PRM002',
                    'id_produk' => 'PRD007',
                ],

                /*
                | Promo expired
                */

                [
                    'id_promo' => 'PRM003',
                    'id_produk' => 'PRD008',
                ],
                [
                    'id_promo' => 'PRM003',
                    'id_produk' => 'PRD009',
                ],

                /*
                | Promo upcoming
                */

                [
                    'id_promo' => 'PRM004',
                    'id_produk' => 'PRD008',
                ],
                [
                    'id_promo' => 'PRM004',
                    'id_produk' => 'PRD009',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 11. PENJUALAN
            |--------------------------------------------------------------------------
            |
            | Variasi data:
            |
            | PJ00001 = Online Budi, berhasil
            | PJ00002 = Online Alya, berhasil
            | PJ00003 = Offline member Budi
            | PJ00004 = Offline guest
            | PJ00005 = Offline member Alya + redeem poin
            | PJ00006 = Online Budi, menunggu pembayaran
            | PJ00007 = Offline guest
            |
            */

            DB::table('penjualan')->insert([
                /*
                |--------------------------------------------------------------
                | PJ00001 - Online Budi
                |--------------------------------------------------------------
                */

                [
                    'id_penjualan' => 'PJ00001',
                    'id_akun' => 'CUST123',

                    'tanggal_penjualan' =>
                        $now->copy()->subDays(6),

                    'kanal_penjualan' => 'ONLINE',
                    'metode_pembayaran' => 'QR',
                    'status_pembayaran' => 'BERHASIL',

                    'referensi_pembayaran' =>
                        'QR-PJ00001-FORMA',

                    // Basic T-Shirt = 107.100
                    // Cargo Pants   = 229.000
                    // Total         = 336.100
                    'nominal_bayar' => 336100,

                    'status_penjualan' => 'SELESAI',

                    'alamat_pengiriman' =>
                        'Jl. Ketintang Baru, Surabaya',

                    // floor(336.100 / 10.000)
                    'poin_didapat' => 33,
                    'poin_digunakan' => 0,
                    'diskon_poin' => 0,

                    'created_at' =>
                        $now->copy()->subDays(6),

                    'updated_at' =>
                        $now->copy()->subDays(5),
                ],

                /*
                |--------------------------------------------------------------
                | PJ00002 - Online Alya
                |--------------------------------------------------------------
                */

                [
                    'id_penjualan' => 'PJ00002',
                    'id_akun' => 'CUST124',

                    'tanggal_penjualan' =>
                        $now->copy()->subDays(5),

                    'kanal_penjualan' => 'ONLINE',
                    'metode_pembayaran' => 'QR',
                    'status_pembayaran' => 'BERHASIL',

                    'referensi_pembayaran' =>
                        'QR-PJ00002-FORMA',

                    // Casual Shirt
                    // 189.000 - 10%
                    'nominal_bayar' => 170100,

                    'status_penjualan' => 'DIKIRIM',

                    'alamat_pengiriman' =>
                        'Jl. Dharmawangsa, Surabaya',

                    'poin_didapat' => 17,
                    'poin_digunakan' => 0,
                    'diskon_poin' => 0,

                    'created_at' =>
                        $now->copy()->subDays(5),

                    'updated_at' =>
                        $now->copy()->subDays(4),
                ],

                /*
                |--------------------------------------------------------------
                | PJ00003 - Offline member Budi
                |--------------------------------------------------------------
                */

                [
                    'id_penjualan' => 'PJ00003',
                    'id_akun' => 'KSR001',

                    'tanggal_penjualan' =>
                        $now->copy()->subDays(3),

                    'kanal_penjualan' => 'OFFLINE',
                    'metode_pembayaran' => 'TUNAI',
                    'status_pembayaran' => 'BERHASIL',

                    'referensi_pembayaran' => null,

                    // 3 Oversized T-Shirt
                    // 149.000 - 10% = 134.100
                    //
                    // 3 × 134.100
                    // = 402.300
                    'nominal_bayar' => 402300,

                    'status_penjualan' => 'SELESAI',
                    'alamat_pengiriman' => null,

                    'poin_didapat' => 40,
                    'poin_digunakan' => 0,
                    'diskon_poin' => 0,

                    'created_at' =>
                        $now->copy()->subDays(3),

                    'updated_at' =>
                        $now->copy()->subDays(3),
                ],

                /*
                |--------------------------------------------------------------
                | PJ00004 - Offline Guest
                |--------------------------------------------------------------
                */

                [
                    'id_penjualan' => 'PJ00004',
                    'id_akun' => 'KSR002',

                    'tanggal_penjualan' =>
                        $now->copy()->subDays(2),

                    'kanal_penjualan' => 'OFFLINE',
                    'metode_pembayaran' => 'QR',
                    'status_pembayaran' => 'BERHASIL',

                    'referensi_pembayaran' =>
                        'QR-PJ00004-FORMA',

                    'nominal_bayar' => 139000,

                    'status_penjualan' => 'SELESAI',
                    'alamat_pengiriman' => null,

                    // Guest tidak memperoleh poin.
                    'poin_didapat' => 0,
                    'poin_digunakan' => 0,
                    'diskon_poin' => 0,

                    'created_at' =>
                        $now->copy()->subDays(2),

                    'updated_at' =>
                        $now->copy()->subDays(2),
                ],

                /*
                |--------------------------------------------------------------
                | PJ00005 - Offline member Alya + redeem
                |--------------------------------------------------------------
                */

                [
                    'id_penjualan' => 'PJ00005',
                    'id_akun' => 'KSR001',

                    'tanggal_penjualan' =>
                        $now->copy()->subDay(),

                    'kanal_penjualan' => 'OFFLINE',
                    'metode_pembayaran' => 'TUNAI',
                    'status_pembayaran' => 'BERHASIL',

                    'referensi_pembayaran' => null,

                    // Hoodie:
                    // 259.000 - 15%
                    // = 220.150
                    //
                    // Alya sebelumnya punya 17 poin.
                    // Redeem 10 poin:
                    // 10 × 500 = 5.000
                    //
                    // Bayar:
                    // 220.150 - 5.000
                    // = 215.150
                    'nominal_bayar' => 215150,

                    'status_penjualan' => 'SELESAI',
                    'alamat_pengiriman' => null,

                    // Earn dihitung dari subtotal
                    // sesudah promo sebelum redeem poin.
                    'poin_didapat' => 22,
                    'poin_digunakan' => 10,
                    'diskon_poin' => 5000,

                    'created_at' =>
                        $now->copy()->subDay(),

                    'updated_at' =>
                        $now->copy()->subDay(),
                ],

                /*
                |--------------------------------------------------------------
                | PJ00006 - Online Budi, menunggu pembayaran
                |--------------------------------------------------------------
                */

                [
                    'id_penjualan' => 'PJ00006',
                    'id_akun' => 'CUST123',

                    'tanggal_penjualan' =>
                        $now->copy()->subHours(6),

                    'kanal_penjualan' => 'ONLINE',
                    'metode_pembayaran' => 'QR',
                    'status_pembayaran' => 'MENUNGGU',

                    'referensi_pembayaran' =>
                        'QR-PJ00006-FORMA',

                    'nominal_bayar' => 209000,

                    'status_penjualan' => 'BARU',

                    'alamat_pengiriman' =>
                        'Jl. Ketintang Baru, Surabaya',

                    // Belum berhasil.
                    'poin_didapat' => 0,
                    'poin_digunakan' => 0,
                    'diskon_poin' => 0,

                    'created_at' =>
                        $now->copy()->subHours(6),

                    'updated_at' =>
                        $now->copy()->subHours(6),
                ],

                /*
                |--------------------------------------------------------------
                | PJ00007 - Offline Guest
                |--------------------------------------------------------------
                */

                [
                    'id_penjualan' => 'PJ00007',
                    'id_akun' => 'KSR002',

                    'tanggal_penjualan' =>
                        $now->copy()->subHours(3),

                    'kanal_penjualan' => 'OFFLINE',
                    'metode_pembayaran' => 'TUNAI',
                    'status_pembayaran' => 'BERHASIL',

                    'referensi_pembayaran' => null,

                    // 2 Crewneck:
                    // 229.000 - 15%
                    // = 194.650
                    //
                    // 2 × 194.650
                    // = 389.300
                    'nominal_bayar' => 389300,

                    'status_penjualan' => 'SELESAI',
                    'alamat_pengiriman' => null,

                    'poin_didapat' => 0,
                    'poin_digunakan' => 0,
                    'diskon_poin' => 0,

                    'created_at' =>
                        $now->copy()->subHours(3),

                    'updated_at' =>
                        $now->copy()->subHours(3),
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 12. PENJUALAN MEMBER
            |--------------------------------------------------------------------------
            |
            | PJ00003:
            | Diproses KSR001
            | Member Budi CUST123
            |
            | PJ00005:
            | Diproses KSR001
            | Member Alya CUST124
            |
            */

            DB::table('penjualan_member')->insert([
                [
                    'id_penjualan' => 'PJ00003',
                    'id_akun_customer' => 'CUST123',
                ],
                [
                    'id_penjualan' => 'PJ00005',
                    'id_akun_customer' => 'CUST124',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 13. DETAIL PENJUALAN
            |--------------------------------------------------------------------------
            |
            | harga_satuan merupakan snapshot harga aktual
            | setelah promo produk.
            |
            */

            DB::table('detail_penjualan')->insert([
                /*
                | PJ00001
                */

                [
                    'id_detail_penjualan' => 'DPJ00001',
                    'id_penjualan' => 'PJ00001',
                    'id_varian' => 'VAR0001',
                    'jumlah' => 1,

                    // 119.000 - 10%
                    'harga_satuan' => 107100,
                ],
                [
                    'id_detail_penjualan' => 'DPJ00002',
                    'id_penjualan' => 'PJ00001',
                    'id_varian' => 'VAR0029',
                    'jumlah' => 1,

                    // Promo Cargo yang lama sudah expired.
                    'harga_satuan' => 229000,
                ],

                /*
                | PJ00002
                */

                [
                    'id_detail_penjualan' => 'DPJ00003',
                    'id_penjualan' => 'PJ00002',
                    'id_varian' => 'VAR0021',
                    'jumlah' => 1,

                    // 189.000 - 10%
                    'harga_satuan' => 170100,
                ],

                /*
                | PJ00003
                */

                [
                    'id_detail_penjualan' => 'DPJ00004',
                    'id_penjualan' => 'PJ00003',
                    'id_varian' => 'VAR0006',
                    'jumlah' => 3,

                    // 149.000 - 10%
                    'harga_satuan' => 134100,
                ],

                /*
                | PJ00004
                */

                [
                    'id_detail_penjualan' => 'DPJ00005',
                    'id_penjualan' => 'PJ00004',
                    'id_varian' => 'VAR0011',
                    'jumlah' => 1,
                    'harga_satuan' => 139000,
                ],

                /*
                | PJ00005
                */

                [
                    'id_detail_penjualan' => 'DPJ00006',
                    'id_penjualan' => 'PJ00005',
                    'id_varian' => 'VAR0015',
                    'jumlah' => 1,

                    // 259.000 - 15%
                    'harga_satuan' => 220150,
                ],

                /*
                | PJ00006
                */

                [
                    'id_detail_penjualan' => 'DPJ00007',
                    'id_penjualan' => 'PJ00006',
                    'id_varian' => 'VAR0034',
                    'jumlah' => 1,
                    'harga_satuan' => 209000,
                ],

                /*
                | PJ00007
                */

                [
                    'id_detail_penjualan' => 'DPJ00008',
                    'id_penjualan' => 'PJ00007',
                    'id_varian' => 'VAR0018',
                    'jumlah' => 2,

                    // 229.000 - 15%
                    'harga_satuan' => 194650,
                ],
            ]);
        });
    }
}