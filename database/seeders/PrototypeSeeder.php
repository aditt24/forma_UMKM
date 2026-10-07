<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

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
                    'created_at' => $now->copy()->subDays(80),
                    'updated_at' => $now->copy()->subDays(80),
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
                    'created_at' => $now->copy()->subDays(72),
                    'updated_at' => $now->copy()->subDays(60),
                ],
                [
                    'id_akun' => 'CUST125',
                    'nama' => 'Raka Mahendra',
                    'username' => 'raka',
                    'password_hash' => Hash::make('raka123'),
                    'email' => 'raka.mahendra@example.com',
                    'no_telp' => '085156739204',
                    'alamat' => 'Jl. Ngagel Jaya, Surabaya',
                    'tipe_akun' => 'CUSTOMER',
                    'status_akun' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(58),
                    'updated_at' => $now->copy()->subDays(42),
                ],
                [
                    'id_akun' => 'CUST126',
                    'nama' => 'Nadia Prameswari',
                    'username' => 'nadia',
                    'password_hash' => Hash::make('nadia123'),
                    'email' => 'nadia.prameswari@example.com',
                    'no_telp' => '083874216509',
                    'alamat' => 'Jl. Mulyorejo, Surabaya',
                    'tipe_akun' => 'CUSTOMER',
                    'status_akun' => 'AKTIF',
                    'created_at' => $now->copy()->subDays(45),
                    'updated_at' => $now->copy()->subDays(30),
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
                    'created_at' => $now->copy()->subDays(120),
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
                    'created_at' => $now->copy()->subDays(96),
                    'updated_at' => $now->copy()->subDays(9),
                ],
                [
                    'id_akun' => 'KSR003',
                    'nama' => 'Nabila Rahma',
                    'username' => 'nabila',
                    'password_hash' => Hash::make('kasir123'),
                    'email' => 'nabila.rahma@example.com',
                    'no_telp' => '087746251903',
                    'alamat' => 'Jl. Wonokromo, Surabaya',
                    'tipe_akun' => 'KASIR',
                    'status_akun' => 'NONAKTIF',
                    'created_at' => $now->copy()->subDays(75),
                    'updated_at' => $now->copy()->subDays(18),
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
                    'created_at' => $now->copy()->subDays(180),
                    'updated_at' => $now,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 2. KATEGORI PRODUK
            |--------------------------------------------------------------------------
            */

            DB::table('kategori_produk')->insert([
                ['id_kategori' => 'KTG001', 'nama_kategori' => 'Kaos', 'created_at' => $now, 'updated_at' => $now],
                ['id_kategori' => 'KTG002', 'nama_kategori' => 'Hoodie', 'created_at' => $now, 'updated_at' => $now],
                ['id_kategori' => 'KTG003', 'nama_kategori' => 'Sweater', 'created_at' => $now, 'updated_at' => $now],
                ['id_kategori' => 'KTG004', 'nama_kategori' => 'Kemeja', 'created_at' => $now, 'updated_at' => $now],
                ['id_kategori' => 'KTG005', 'nama_kategori' => 'Celana', 'created_at' => $now, 'updated_at' => $now],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 3. PRODUK
            |--------------------------------------------------------------------------
            | Gambar harus tersedia di public/images/products/
            */

            DB::table('produk')->insert([
                [
                    'id_produk' => 'PRD001',
                    'id_kategori' => 'KTG001',
                    'nama_produk' => 'Basic T-Shirt',
                    'harga_jual' => 119000,
                    'deskripsi' => 'Kaos basic dengan potongan simpel untuk pemakaian sehari-hari.',
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
                    'deskripsi' => 'T-shirt oversized dengan potongan santai dan nyaman.',
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
                    'deskripsi' => 'T-shirt minimalis dengan detail pocket pada bagian depan.',
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
                    'deskripsi' => 'Hoodie pullover untuk tampilan kasual dan nyaman.',
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
                    'deskripsi' => 'Crewneck sweatshirt untuk kebutuhan daily wear.',
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
                    'deskripsi' => 'Kemeja kasual dengan tampilan clean untuk aktivitas sehari-hari.',
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
                    'deskripsi' => 'Overshirt ringan yang cocok digunakan sebagai layering.',
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
                    'deskripsi' => 'Celana cargo dengan potongan kasual dan kantong fungsional.',
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
                    'deskripsi' => 'Straight pants dengan potongan clean untuk berbagai gaya.',
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
            | Stok di bawah merupakan stok saat ini setelah histori transaksi prototype.
            | Beberapa varian sengaja berada pada kondisi low stock.
            */

            $variantData = [
                'PRD001' => ['warna' => 'hitam', 'stok' => [22, 18, 15, 11]],
                'PRD002' => ['warna' => 'hitam', 'stok' => [12, 16, 10, 8]],
                'PRD003' => ['warna' => 'putih', 'stok' => [9, 8, 6, 4]],
                'PRD004' => ['warna' => 'hitam', 'stok' => [14, 12, 9, 6]],
                'PRD005' => ['warna' => 'hitam', 'stok' => [16, 13, 10, 7]],
                'PRD006' => ['warna' => 'putih', 'stok' => [11, 9, 7, 5]],
                'PRD007' => ['warna' => 'khaki', 'stok' => [9, 8, 6, 4]],
                'PRD008' => ['warna' => 'khaki', 'stok' => [15, 12, 9, 6]],
                'PRD009' => ['warna' => 'hitam', 'stok' => [14, 11, 8, 5]],
            ];

            $sizes = ['S', 'M', 'L', 'XL'];
            $variantNumber = 1;
            $variantProduct = [];

            foreach ($variantData as $productId => $data) {
                foreach ($sizes as $index => $size) {
                    $variantId = 'VAR'.str_pad($variantNumber, 4, '0', STR_PAD_LEFT);

                    DB::table('varian_produk')->insert([
                        'id_varian' => $variantId,
                        'id_produk' => $productId,
                        'ukuran' => $size,
                        'warna' => $data['warna'],
                        'stok' => $data['stok'][$index],
                        'stok_minimum' => 5,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $variantProduct[$variantId] = $productId;
                    $variantNumber++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 5. SUPPLIER
            |--------------------------------------------------------------------------
            */

            DB::table('supplier')->insert([
                [
                    'id_supplier' => 'SUP001',
                    'nama_supplier' => 'Nusantara Apparel Supply',
                    'no_telp' => '081357924681',
                    'alamat' => 'Jl. Margomulyo Indah, Surabaya',
                    'created_at' => $now->copy()->subDays(150),
                    'updated_at' => $now,
                ],
                [
                    'id_supplier' => 'SUP002',
                    'nama_supplier' => 'Urban Garment Wholesale',
                    'no_telp' => '082233764915',
                    'alamat' => 'Jl. Kopo, Bandung',
                    'created_at' => $now->copy()->subDays(130),
                    'updated_at' => $now,
                ],
                [
                    'id_supplier' => 'SUP003',
                    'nama_supplier' => 'Dailywear Distributor',
                    'no_telp' => '085889214376',
                    'alamat' => 'Jl. Pademangan, Jakarta Utara',
                    'created_at' => $now->copy()->subDays(110),
                    'updated_at' => $now,
                ],
                [
                    'id_supplier' => 'SUP004',
                    'nama_supplier' => 'Prima Fashion Supply',
                    'no_telp' => '081376542890',
                    'alamat' => 'Jl. Waru Industri, Sidoarjo',
                    'created_at' => $now->copy()->subDays(88),
                    'updated_at' => $now,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 6. PEMBELIAN PRODUK
            |--------------------------------------------------------------------------
            |
            | DITERIMA -> stok dianggap masuk dan pengeluaran dibuat.
            | DIPESAN  -> belum masuk pengeluaran karena belum dikonfirmasi diterima.
            | BATAL    -> tidak masuk pengeluaran.
            |
            */

            DB::table('pembelian_produk')->insert([
                [
                    'id_pembelian' => 'PB0001',
                    'id_supplier' => 'SUP001',
                    'id_akun' => 'ADM021',
                    'tanggal_pembelian' => $now->copy()->subDays(18),
                    'status_pembelian' => 'DITERIMA',
                    'created_at' => $now->copy()->subDays(18),
                    'updated_at' => $now->copy()->subDays(15),
                ],
                [
                    'id_pembelian' => 'PB0002',
                    'id_supplier' => 'SUP002',
                    'id_akun' => 'ADM021',
                    'tanggal_pembelian' => $now->copy()->subDays(9),
                    'status_pembelian' => 'DITERIMA',
                    'created_at' => $now->copy()->subDays(9),
                    'updated_at' => $now->copy()->subDays(7),
                ],
                [
                    'id_pembelian' => 'PB0003',
                    'id_supplier' => 'SUP003',
                    'id_akun' => 'ADM021',
                    'tanggal_pembelian' => $now->copy()->subDay(),
                    'status_pembelian' => 'DIPESAN',
                    'created_at' => $now->copy()->subDay(),
                    'updated_at' => $now->copy()->subDay(),
                ],
                [
                    'id_pembelian' => 'PB0004',
                    'id_supplier' => 'SUP004',
                    'id_akun' => 'ADM021',
                    'tanggal_pembelian' => $now->copy()->subDays(6),
                    'status_pembelian' => 'BATAL',
                    'created_at' => $now->copy()->subDays(6),
                    'updated_at' => $now->copy()->subDays(5),
                ],
            ]);

            DB::table('detail_pembelian')->insert([
                // PB0001 -> total Rp3.990.000
                ['id_detail_pembelian' => 'DPB00001', 'id_pembelian' => 'PB0001', 'id_varian' => 'VAR0001', 'jumlah' => 20, 'harga_satuan' => 70000],
                ['id_detail_pembelian' => 'DPB00002', 'id_pembelian' => 'PB0001', 'id_varian' => 'VAR0006', 'jumlah' => 15, 'harga_satuan' => 90000],
                ['id_detail_pembelian' => 'DPB00003', 'id_pembelian' => 'PB0001', 'id_varian' => 'VAR0015', 'jumlah' => 8, 'harga_satuan' => 155000],

                // PB0002 -> total Rp3.500.000
                ['id_detail_pembelian' => 'DPB00004', 'id_pembelian' => 'PB0002', 'id_varian' => 'VAR0021', 'jumlah' => 10, 'harga_satuan' => 110000],
                ['id_detail_pembelian' => 'DPB00005', 'id_pembelian' => 'PB0002', 'id_varian' => 'VAR0025', 'jumlah' => 8, 'harga_satuan' => 125000],
                ['id_detail_pembelian' => 'DPB00006', 'id_pembelian' => 'PB0002', 'id_varian' => 'VAR0029', 'jumlah' => 10, 'harga_satuan' => 140000],

                // PB0003 -> masih DIPESAN, belum menjadi pengeluaran.
                ['id_detail_pembelian' => 'DPB00007', 'id_pembelian' => 'PB0003', 'id_varian' => 'VAR0009', 'jumlah' => 10, 'harga_satuan' => 85000],
                ['id_detail_pembelian' => 'DPB00008', 'id_pembelian' => 'PB0003', 'id_varian' => 'VAR0033', 'jumlah' => 10, 'harga_satuan' => 130000],

                // PB0004 -> BATAL, tidak menjadi pengeluaran.
                ['id_detail_pembelian' => 'DPB00009', 'id_pembelian' => 'PB0004', 'id_varian' => 'VAR0017', 'jumlah' => 6, 'harga_satuan' => 135000],
                ['id_detail_pembelian' => 'DPB00010', 'id_pembelian' => 'PB0004', 'id_varian' => 'VAR0030', 'jumlah' => 5, 'harga_satuan' => 145000],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 7. PENGELUARAN
            |--------------------------------------------------------------------------
            |
            | HANYA pembelian berstatus DITERIMA yang dicatat sebagai PEMBELIAN_PRODUK.
            | PB0003 (DIPESAN) dan PB0004 (BATAL) sengaja tidak memiliki row pengeluaran.
            |
            */

            DB::table('pengeluaran')->insert([
                [
                    'id_pengeluaran' => 'PNG0001',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => 'PB0001',
                    'tanggal_pengeluaran' => $now->copy()->subDays(15),
                    'jenis_pengeluaran' => 'PEMBELIAN_PRODUK',
                    'nominal' => 3990000,
                    'keterangan' => 'Pembelian PB0001 dikonfirmasi diterima dari Nusantara Apparel Supply',
                    'created_at' => $now->copy()->subDays(15),
                    'updated_at' => $now->copy()->subDays(15),
                ],
                [
                    'id_pengeluaran' => 'PNG0002',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => 'PB0002',
                    'tanggal_pengeluaran' => $now->copy()->subDays(7),
                    'jenis_pengeluaran' => 'PEMBELIAN_PRODUK',
                    'nominal' => 3500000,
                    'keterangan' => 'Pembelian PB0002 dikonfirmasi diterima dari Urban Garment Wholesale',
                    'created_at' => $now->copy()->subDays(7),
                    'updated_at' => $now->copy()->subDays(7),
                ],
                [
                    'id_pengeluaran' => 'PNG0003',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => null,
                    'tanggal_pengeluaran' => $now->copy()->subDays(12),
                    'jenis_pengeluaran' => 'INTERNET',
                    'nominal' => 350000,
                    'keterangan' => 'Internet toko bulan berjalan',
                    'created_at' => $now->copy()->subDays(12),
                    'updated_at' => $now->copy()->subDays(12),
                ],
                [
                    'id_pengeluaran' => 'PNG0004',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => null,
                    'tanggal_pengeluaran' => $now->copy()->subDays(8),
                    'jenis_pengeluaran' => 'SEWA',
                    'nominal' => 1200000,
                    'keterangan' => 'Sewa tempat usaha bulan berjalan',
                    'created_at' => $now->copy()->subDays(8),
                    'updated_at' => $now->copy()->subDays(8),
                ],
                [
                    'id_pengeluaran' => 'PNG0005',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => null,
                    'tanggal_pengeluaran' => $now->copy()->subDays(4),
                    'jenis_pengeluaran' => 'ONGKIR',
                    'nominal' => 175000,
                    'keterangan' => 'Biaya pengiriman dan pengambilan stok',
                    'created_at' => $now->copy()->subDays(4),
                    'updated_at' => $now->copy()->subDays(4),
                ],
                [
                    'id_pengeluaran' => 'PNG0006',
                    'id_akun' => 'ADM021',
                    'id_pembelian' => null,
                    'tanggal_pengeluaran' => $now->copy()->subDays(2),
                    'jenis_pengeluaran' => 'LISTRIK',
                    'nominal' => 200000,
                    'keterangan' => 'Tagihan listrik toko bulan berjalan',
                    'created_at' => $now->copy()->subDays(2),
                    'updated_at' => $now->copy()->subDays(2),
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 8. PROMO
            |--------------------------------------------------------------------------
            |
            | PRM001 & PRM002 -> sedang berjalan
            | PRM003          -> sudah berakhir
            | PRM004          -> akan datang
            |
            */

            DB::table('promo')->insert([
                [
                    'id_promo' => 'PRM001',
                    'nama_promo' => 'Everyday Essentials',
                    'persen_diskon' => 10,
                    'tanggal_mulai' => $now->copy()->subDays(12),
                    'tanggal_selesai' => $now->copy()->addDays(10),
                    'created_at' => $now->copy()->subDays(14),
                    'updated_at' => $now,
                ],
                [
                    'id_promo' => 'PRM002',
                    'nama_promo' => 'Outerwear Week',
                    'persen_diskon' => 15,
                    'tanggal_mulai' => $now->copy()->subDays(7),
                    'tanggal_selesai' => $now->copy()->addDays(6),
                    'created_at' => $now->copy()->subDays(9),
                    'updated_at' => $now,
                ],
                [
                    'id_promo' => 'PRM003',
                    'nama_promo' => 'Weekend Bottoms',
                    'persen_diskon' => 20,
                    'tanggal_mulai' => $now->copy()->subDays(30),
                    'tanggal_selesai' => $now->copy()->subDays(20),
                    'created_at' => $now->copy()->subDays(32),
                    'updated_at' => $now->copy()->subDays(20),
                ],
                [
                    'id_promo' => 'PRM004',
                    'nama_promo' => 'Payday Collection',
                    'persen_diskon' => 12,
                    'tanggal_mulai' => $now->copy()->addDays(4),
                    'tanggal_selesai' => $now->copy()->addDays(12),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            DB::table('promo_produk')->insert([
                // Promo aktif 10%
                ['id_promo' => 'PRM001', 'id_produk' => 'PRD001'],
                ['id_promo' => 'PRM001', 'id_produk' => 'PRD002'],
                ['id_promo' => 'PRM001', 'id_produk' => 'PRD006'],

                // Promo aktif 15%
                ['id_promo' => 'PRM002', 'id_produk' => 'PRD004'],
                ['id_promo' => 'PRM002', 'id_produk' => 'PRD005'],
                ['id_promo' => 'PRM002', 'id_produk' => 'PRD007'],

                // Promo sudah berakhir
                ['id_promo' => 'PRM003', 'id_produk' => 'PRD008'],
                ['id_promo' => 'PRM003', 'id_produk' => 'PRD009'],

                // Promo akan datang
                ['id_promo' => 'PRM004', 'id_produk' => 'PRD003'],
                ['id_promo' => 'PRM004', 'id_produk' => 'PRD008'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 9. PENJUALAN + PENJUALAN_MEMBER + DETAIL_PENJUALAN
            |--------------------------------------------------------------------------
            |
            | Mayoritas transaksi berada dalam 5 hari terakhir.
            | Harga detail adalah snapshot harga sesudah promo aktif.
            |
            */

            $salePrice = [
                'PRD001' => 107100, // 119.000 - 10%
                'PRD002' => 134100, // 149.000 - 10%
                'PRD003' => 139000, // tidak ada promo aktif
                'PRD004' => 220150, // 259.000 - 15%
                'PRD005' => 194650, // 229.000 - 15%
                'PRD006' => 170100, // 189.000 - 10%
                'PRD007' => 186150, // 219.000 - 15%
                'PRD008' => 229000, // promo lama sudah berakhir
                'PRD009' => 209000, // promo lama sudah berakhir
            ];

            $customerAddresses = [
                'CUST123' => 'Jl. Ketintang Baru, Surabaya',
                'CUST124' => 'Jl. Dharmawangsa, Surabaya',
                'CUST125' => 'Jl. Ngagel Jaya, Surabaya',
                'CUST126' => 'Jl. Mulyorejo, Surabaya',
            ];

            /*
            | Format:
            | id, actor, hours_ago, channel, method, payment, sale_status,
            | member, used_points, items
            |
            | actor:
            | - ONLINE  -> Customer
            | - OFFLINE -> Kasir
            |
            | member:
            | - OFFLINE member -> Customer ID
            | - OFFLINE guest  -> null
            */
            $transactions = [
                ['PJ00001', 'CUST123', 118, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0002', 2], ['VAR0006', 1]]],
                ['PJ00002', 'CUST124', 113, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0014', 1], ['VAR0018', 1]]],
                ['PJ00003', 'KSR001',  108, 'OFFLINE', 'TUNAI', 'BERHASIL', 'SELESAI',  null,      0, [['VAR0030', 2]]],
                ['PJ00004', 'CUST125', 103, 'ONLINE',  'QR',    'BERHASIL', 'DIKIRIM',  null,      0, [['VAR0006', 2], ['VAR0022', 1]]],
                ['PJ00005', 'CUST123',  98, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0001', 1], ['VAR0014', 1]]],
                ['PJ00006', 'KSR002',   93, 'OFFLINE', 'QR',    'BERHASIL', 'SELESAI',  'CUST126', 0, [['VAR0018', 2], ['VAR0026', 1]]],
                ['PJ00007', 'CUST124',  88, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0010', 1], ['VAR0030', 1]]],
                ['PJ00008', 'KSR001',   82, 'OFFLINE', 'TUNAI', 'BERHASIL', 'SELESAI',  'CUST123',20, [['VAR0006', 4]]],
                ['PJ00009', 'CUST126',  77, 'ONLINE',  'QR',    'BERHASIL', 'DIKIRIM',  null,      0, [['VAR0014', 2]]],
                ['PJ00010', 'KSR002',   72, 'OFFLINE', 'QR',    'BERHASIL', 'SELESAI',  'CUST124',20, [['VAR0002', 2], ['VAR0034', 1]]],
                ['PJ00011', 'CUST125',  67, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0022', 2], ['VAR0018', 1]]],
                ['PJ00012', 'KSR001',   62, 'OFFLINE', 'TUNAI', 'BERHASIL', 'SELESAI',  null,      0, [['VAR0030', 1], ['VAR0034', 1]]],
                ['PJ00013', 'CUST123',  57, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,     10, [['VAR0006', 2], ['VAR0014', 1]]],
                ['PJ00014', 'KSR002',   52, 'OFFLINE', 'QR',    'BERHASIL', 'SELESAI',  'CUST125',15, [['VAR0001', 3]]],
                ['PJ00015', 'CUST126',  47, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0018', 3]]],
                ['PJ00016', 'KSR001',   42, 'OFFLINE', 'TUNAI', 'BERHASIL', 'SELESAI',  'CUST126',10, [['VAR0026', 2], ['VAR0030', 1]]],
                ['PJ00017', 'CUST124',  37, 'ONLINE',  'QR',    'BERHASIL', 'DIPROSES', null,      0, [['VAR0010', 2], ['VAR0022', 1]]],
                ['PJ00018', 'KSR002',   32, 'OFFLINE', 'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0014', 1], ['VAR0030', 2]]],
                ['PJ00019', 'CUST125',  27, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0006', 1], ['VAR0018', 2]]],
                ['PJ00020', 'KSR001',   22, 'OFFLINE', 'TUNAI', 'BERHASIL', 'SELESAI',  'CUST123', 0, [['VAR0002', 2], ['VAR0026', 1]]],
                ['PJ00021', 'CUST124',  18, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,     15, [['VAR0034', 2], ['VAR0030', 1]]],
                ['PJ00022', 'KSR002',   14, 'OFFLINE', 'QR',    'BERHASIL', 'SELESAI',  'CUST125',10, [['VAR0014', 2], ['VAR0022', 1]]],
                ['PJ00023', 'CUST126',  10, 'ONLINE',  'QR',    'BERHASIL', 'SELESAI',  null,      0, [['VAR0006', 2], ['VAR0010', 1]]],
                ['PJ00024', 'KSR001',    7, 'OFFLINE', 'TUNAI', 'BERHASIL', 'SELESAI',  null,      0, [['VAR0018', 2], ['VAR0030', 1]]],

                // Variasi status yang tidak masuk pemasukan/poin.
                ['PJ00025', 'CUST123',   4, 'ONLINE',  'QR',    'MENUNGGU', 'BARU',     null,      0, [['VAR0034', 1]]],
                ['PJ00026', 'CUST124',   2, 'ONLINE',  'QR',    'GAGAL',    'BATAL',    null,      0, [['VAR0026', 1]]],
            ];

            $pointBalances = [
                'CUST123' => 0,
                'CUST124' => 0,
                'CUST125' => 0,
                'CUST126' => 0,
            ];

            $detailNumber = 1;

            foreach ($transactions as $trx) {
                [
                    $saleId,
                    $actorId,
                    $hoursAgo,
                    $channel,
                    $method,
                    $paymentStatus,
                    $saleStatus,
                    $offlineMemberId,
                    $pointsUsed,
                    $items
                ] = $trx;

                $saleMoment = $now->copy()->subHours($hoursAgo);

                $subtotal = 0;
                foreach ($items as [$variantId, $qty]) {
                    $productId = $variantProduct[$variantId];
                    $subtotal += $salePrice[$productId] * $qty;
                }

                $memberCustomerId = null;

                if ($channel === 'ONLINE') {
                    $memberCustomerId = $actorId;
                } elseif ($offlineMemberId !== null) {
                    $memberCustomerId = $offlineMemberId;
                }

                $pointsEarned = 0;
                $pointDiscount = 0;

                if ($paymentStatus === 'BERHASIL' && $memberCustomerId !== null) {
                    if ($pointsUsed > $pointBalances[$memberCustomerId]) {
                        throw new RuntimeException(
                            "Saldo poin {$memberCustomerId} tidak cukup pada {$saleId}."
                        );
                    }

                    $pointsEarned = (int) floor($subtotal / 10000);
                    $pointDiscount = $pointsUsed * 500;

                    $pointBalances[$memberCustomerId] += $pointsEarned - $pointsUsed;
                } else {
                    $pointsUsed = 0;
                }

                $nominalBayar = max(0, $subtotal - $pointDiscount);

                $reference = null;
                if ($method === 'QR') {
                    $reference = "QR-{$saleId}-FORMA";
                }

                DB::table('penjualan')->insert([
                    'id_penjualan' => $saleId,
                    'id_akun' => $actorId,
                    'tanggal_penjualan' => $saleMoment,
                    'kanal_penjualan' => $channel,
                    'metode_pembayaran' => $method,
                    'status_pembayaran' => $paymentStatus,
                    'referensi_pembayaran' => $reference,
                    'nominal_bayar' => $nominalBayar,
                    'status_penjualan' => $saleStatus,
                    'alamat_pengiriman' =>
                        $channel === 'ONLINE'
                            ? $customerAddresses[$actorId]
                            : null,
                    'poin_didapat' => $pointsEarned,
                    'poin_digunakan' => $pointsUsed,
                    'diskon_poin' => $pointDiscount,
                    'created_at' => $saleMoment,
                    'updated_at' => $saleMoment,
                ]);

                if ($channel === 'OFFLINE' && $offlineMemberId !== null) {
                    DB::table('penjualan_member')->insert([
                        'id_penjualan' => $saleId,
                        'id_akun_customer' => $offlineMemberId,
                    ]);
                }

                foreach ($items as [$variantId, $qty]) {
                    $productId = $variantProduct[$variantId];

                    DB::table('detail_penjualan')->insert([
                        'id_detail_penjualan' =>
                            'DPJ'.str_pad($detailNumber, 5, '0', STR_PAD_LEFT),
                        'id_penjualan' => $saleId,
                        'id_varian' => $variantId,
                        'jumlah' => $qty,
                        'harga_satuan' => $salePrice[$productId],
                    ]);

                    $detailNumber++;
                }
            }
        });
    }
}