<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/*
|--------------------------------------------------------------------------
| Seeder data demo OLTP untuk UAS Data Warehouse
|--------------------------------------------------------------------------
|
| Mensimulasikan toko pakaian hari demi hari: stok awal dibeli 22 Desember
| 2025, penjualan 3 Januari 2026 sampai 9 Desember 2026 (UAS 10 Desember):
|
|   - Stok dihitung berurutan, jadi TIDAK PERNAH minus.
|   - Restock (pembelian) dibuat otomatis saat stok menipis, lalu barang
|     baru diterima beberapa hari kemudian (stok bertambah saat DITERIMA).
|   - Pengeluaran PEMBELIAN_PRODUK hanya dibuat untuk pembelian DITERIMA,
|     sesuai aturan aplikasi.
|   - Penjualan online dan offline, promo, poin member, transaksi gagal,
|     batal, dan masih diproses (di hari-hari terakhir).
|   - Angka acak memakai seed tetap, jadi hasilnya SAMA di semua laptop.
|
| Jalankan:  php artisan migrate:fresh --seed
*/

class OltpDemoSeeder extends Seeder
{
    private const SEED = 20261210;

    private const FIRST_SALE_DAY = '2026-01-03';
    private const LAST_DAY = '2026-12-09';

    private const POINT_VALUE = 500;
    private const EARN_PER_RUPIAH = 10000;

    /** @var array<int, array<string, mixed>> */
    private array $accounts = [];

    /** id_akun => info customer */
    private array $customers = [];

    /** @var array<int, array<string, mixed>> */
    private array $staff = [];

    private array $categories = [];
    private array $products = [];
    private array $variants = [];
    private array $suppliers = [];
    private array $promos = [];

    private array $stock = [];
    private array $incoming = [];
    private array $reorderPoint = [];
    private array $receipts = [];
    private array $lastOrder = [];
    private array $balance = [];

    private array $rowsPembelian = [];
    private array $rowsDetailPembelian = [];
    private array $rowsPenjualan = [];
    private array $rowsPenjualanMember = [];
    private array $rowsDetailPenjualan = [];
    private array $rowsPengeluaran = [];

    private int $pbCounter = 0;
    private int $dpbCounter = 0;
    private int $pjCounter = 0;
    private int $dpjCounter = 0;

    private CarbonImmutable $lastDay;

    private array $onlinePaidPerWeek = [];

    public function run(): void
    {
        if (DB::table('akun')->exists()) {
            throw new RuntimeException(
                'Tabel akun sudah berisi data. '
                .'Jalankan: php artisan migrate:fresh --seed'
            );
        }

        mt_srand(self::SEED);

        $this->lastDay = CarbonImmutable::parse(self::LAST_DAY);

        $this->buildStaff();
        $this->buildCustomers();
        $this->buildCatalog();
        $this->buildSuppliers();
        $this->buildPromos();

        $this->simulate();
        $this->buildOperationalExpenses();

        DB::transaction(function (): void {
            $this->persist();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Helper acak (deterministik)
    |--------------------------------------------------------------------------
    */

    private function rnd(): float
    {
        return mt_rand() / mt_getrandmax();
    }

    private function between(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }

    /** Pilih key dari array [key => bobot]. */
    private function pick(array $weights): mixed
    {
        $total = array_sum($weights);

        if ($total <= 0) {
            return null;
        }

        $r = $this->rnd() * $total;

        foreach ($weights as $key => $weight) {
            $r -= $weight;

            if ($r <= 0) {
                return $key;
            }
        }

        return array_key_last($weights);
    }

    private function id(string $prefix, int $number, int $digits): string
    {
        return $prefix.str_pad((string) $number, $digits, '0', STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Akun staf
    |--------------------------------------------------------------------------
    */

    private function buildStaff(): void
    {
        $created = CarbonImmutable::parse('2025-12-20 09:00:00');

        $list = [
            ['ADM021', 'Nanda Pratama', 'admin', 'admin123', 'admin@forma.local', '087812345609', 'Jl. Ahmad Yani, Surabaya', 'ADMIN', 'AKTIF', null, null],
            ['ADM022', 'Dewi Lestari', 'dewi', 'admin123', 'dewi.lestari@forma.local', '081355501122', 'Jl. Darmo Permai, Surabaya', 'ADMIN', 'AKTIF', null, null],
            ['KSR001', 'Siti Amalia', 'siti', 'kasir123', 'siti.amalia@example.com', '085712349876', 'Jl. Rungkut Asri, Surabaya', 'KASIR', 'AKTIF', '2026-01-01', '2026-12-31'],
            ['KSR002', 'Rizky Ramadhan', 'rizky', 'kasir123', 'rizky.ramadhan@example.com', '081298765431', 'Jl. Manyar Kertoarjo, Surabaya', 'KASIR', 'AKTIF', '2026-01-01', '2026-12-31'],
            ['KSR003', 'Nabila Rahma', 'nabila', 'kasir123', 'nabila.rahma@example.com', '087746251903', 'Jl. Wonokromo, Surabaya', 'KASIR', 'NONAKTIF', '2026-01-01', '2026-08-31'],
            ['KSR004', 'Fajar Hidayat', 'fajar', 'kasir123', 'fajar.hidayat@example.com', '082144478890', 'Jl. Gubeng Kertajaya, Surabaya', 'KASIR', 'AKTIF', '2026-09-01', '2026-12-31'],
        ];

        $hashes = [];

        foreach ($list as $row) {
            [$id, $name, $user, $pass, $email, $phone, $address, $type, $status, $from, $to] = $row;

            $hashes[$pass] ??= Hash::make($pass);

            $this->accounts[] = [
                'id_akun' => $id,
                'nama' => $name,
                'username' => $user,
                'password_hash' => $hashes[$pass],
                'email' => $email,
                'no_telp' => $phone,
                'alamat' => $address,
                'tipe_akun' => $type,
                'status_akun' => $status,
                'created_at' => $created->toDateTimeString(),
                'updated_at' => $status === 'NONAKTIF'
                    ? '2026-09-01 08:00:00'
                    : $created->toDateTimeString(),
            ];

            if ($type === 'KASIR') {
                $this->staff[] = [
                    'id' => $id,
                    'from' => $from,
                    'to' => $to,
                ];
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Customer
    |--------------------------------------------------------------------------
    */

    private function buildCustomers(): void
    {
        $firstNames = ['Agus', 'Ahmad', 'Aisyah', 'Andi', 'Anita', 'Bagas', 'Bayu', 'Citra', 'Dedi', 'Dewi', 'Dian', 'Dimas', 'Eka', 'Fadil', 'Farah', 'Gita', 'Hadi', 'Indah', 'Intan', 'Joko', 'Kartika', 'Lina', 'Maya', 'Nando', 'Niko', 'Putri', 'Rani', 'Reza', 'Rina', 'Salsa', 'Tegar', 'Tika', 'Umar', 'Vina', 'Wahyu', 'Yoga', 'Yuni', 'Zahra', 'Rafi', 'Sekar'];
        $lastNames = ['Pratama', 'Saputra', 'Wijaya', 'Kusuma', 'Hidayat', 'Santoso', 'Wibowo', 'Lestari', 'Permata', 'Ramadhan', 'Setiawan', 'Nugroho', 'Utami', 'Rahmawati', 'Firmansyah', 'Maulana', 'Anggraini', 'Susanto', 'Purnomo', 'Kurniawan', 'Hakim', 'Fauzi', 'Halim', 'Cahyono', 'Puspita', 'Mahendra', 'Syahputra', 'Handayani', 'Pangestu', 'Aditya'];
        $streets = ['Jl. Ketintang Baru', 'Jl. Dharmawangsa', 'Jl. Ngagel Jaya', 'Jl. Mulyorejo', 'Jl. Rungkut Asri', 'Jl. Manyar Kertoarjo', 'Jl. Wonokromo', 'Jl. Darmo Permai', 'Jl. Gubeng Kertajaya', 'Jl. Kenjeran', 'Jl. Raya Gubeng', 'Jl. Klampis Aji', 'Jl. Pucang Anom', 'Jl. Diponegoro', 'Jl. Mayjend Sungkono', 'Jl. Tenggilis Mejoyo', 'Jl. Jemursari', 'Jl. Gayungsari', 'Jl. Semolowaru', 'Jl. Barata Jaya'];

        $demo = [
            ['Budi Santoso', 'budi', 'budi123', 'budi.santoso@example.com', '081234567812', 'Jl. Ketintang Baru, Surabaya'],
            ['Alya Putri', 'alya', 'alya123', 'alya.putri@example.com', '082112345678', 'Jl. Dharmawangsa, Surabaya'],
            ['Raka Mahendra', 'raka', 'raka123', 'raka.mahendra@example.com', '085156739204', 'Jl. Ngagel Jaya, Surabaya'],
            ['Nadia Prameswari', 'nadia', 'nadia123', 'nadia.prameswari@example.com', '083874216509', 'Jl. Mulyorejo, Surabaya'],
        ];

        $defaultHash = Hash::make('customer123');

        $monthWeights = [1 => 12, 2 => 8, 3 => 14, 4 => 6, 5 => 7, 6 => 7, 7 => 8, 8 => 8, 9 => 8, 10 => 9, 11 => 12, 12 => 1];

        $deactivated = [30 => true, 77 => true, 140 => true];

        $total = 220;

        for ($i = 0; $i < $total; $i++) {
            if ($i < count($demo)) {
                [$name, $user, $pass, $email, $phone, $address] = $demo[$i];

                $hash = Hash::make($pass);
                $reg = CarbonImmutable::parse('2026-01-02 10:00:00');
            } else {
                $n = $i - count($demo);
                $first = $firstNames[$n % 40];
                $last = $lastNames[($n * 7 + intdiv($n, 40) * 3) % 30];

                $name = $first.' '.$last;
                $user = strtolower($first.'.'.$last);
                $email = $user.'@example.com';
                $phone = '08'.$this->between(11, 89)
                    .str_pad((string) $this->between(0, 99999999), 8, '0', STR_PAD_LEFT);
                $address = $streets[$this->between(0, count($streets) - 1)]
                    .' No. '.$this->between(1, 120).', Surabaya';
                $hash = $defaultHash;

                $month = $this->pick($monthWeights);
                $firstDay = CarbonImmutable::create(2026, $month, 1);
                $maxDay = $month === 12 ? 8 : $firstDay->daysInMonth;
                $minDay = $month === 1 ? 3 : 1;

                $reg = CarbonImmutable::create(
                    2026,
                    $month,
                    $this->between($minDay, $maxDay),
                    $this->between(8, 21),
                    $this->between(0, 59),
                    $this->between(0, 59)
                );
            }

            $id = $this->id('CUST', $i + 1, 3);

            $inactiveFrom = isset($deactivated[$i])
                ? $reg->addDays(90)
                : null;

            if ($inactiveFrom && $inactiveFrom > $this->lastDay) {
                $inactiveFrom = null;
            }

            $this->accounts[] = [
                'id_akun' => $id,
                'nama' => $name,
                'username' => $user,
                'password_hash' => $hash,
                'email' => $email,
                'no_telp' => $phone,
                'alamat' => $address,
                'tipe_akun' => 'CUSTOMER',
                'status_akun' => $inactiveFrom ? 'NONAKTIF' : 'AKTIF',
                'created_at' => $reg->toDateTimeString(),
                'updated_at' => ($inactiveFrom ?? $reg)->toDateTimeString(),
            ];

            // Beberapa customer jauh lebih sering belanja daripada yang lain.
            $loyalty = 0.2 + 3 * pow($this->rnd(), 3);

            $this->customers[$id] = [
                'address' => $address,
                'reg' => $reg->startOfDay(),
                'until' => $inactiveFrom?->startOfDay(),
                'weight' => $loyalty,
            ];

            $this->balance[$id] = 0;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Kategori, produk, varian
    |--------------------------------------------------------------------------
    */

    private function buildCatalog(): void
    {
        $this->categories = [
            'KTG001' => 'Kaos',
            'KTG002' => 'Hoodie',
            'KTG003' => 'Sweater',
            'KTG004' => 'Kemeja',
            'KTG005' => 'Celana',
        ];

        // id => [kategori, nama, harga, gambar, deskripsi, bobot laku, rasio HPP, supplier, stok min, warna => bobot]
        $catalog = [
            'PRD001' => ['KTG001', 'Basic T-Shirt', 119000, 'basic-tshirt.jpg', 'Kaos basic dengan potongan simpel untuk pemakaian sehari-hari.', 1.6, 0.52, 'SUP001', 5, ['hitam' => 5, 'putih' => 4, 'navy' => 2]],
            'PRD002' => ['KTG001', 'Oversized T-Shirt', 149000, 'oversized-tshirt.jpg', 'T-shirt oversized dengan potongan santai dan nyaman.', 1.5, 0.52, 'SUP001', 5, ['hitam' => 5, 'putih' => 3, 'krem' => 2]],
            'PRD003' => ['KTG001', 'Pocket T-Shirt', 139000, 'pocket-tshirt.jpg', 'T-shirt minimalis dengan detail pocket pada bagian depan.', 0.8, 0.52, 'SUP005', 5, ['putih' => 4, 'abu' => 3]],
            'PRD004' => ['KTG002', 'Pullover Hoodie', 259000, 'hoodie.jpg', 'Hoodie pullover untuk tampilan kasual dan nyaman.', 1.0, 0.58, 'SUP002', 6, ['hitam' => 5, 'abu' => 3, 'maroon' => 2]],
            'PRD005' => ['KTG003', 'Crewneck Sweatshirt', 229000, 'crewneck.jpg', 'Crewneck sweatshirt untuk kebutuhan daily wear.', 0.8, 0.58, 'SUP002', 5, ['hitam' => 4, 'abu' => 3, 'navy' => 2]],
            'PRD006' => ['KTG004', 'Casual Shirt', 189000, 'casual-shirt.jpg', 'Kemeja kasual dengan tampilan clean untuk aktivitas sehari-hari.', 0.9, 0.55, 'SUP003', 5, ['putih' => 5, 'biru' => 3]],
            'PRD007' => ['KTG004', 'Overshirt', 219000, 'overshirt.jpg', 'Overshirt ringan yang cocok digunakan sebagai layering.', 0.6, 0.55, 'SUP003', 5, ['khaki' => 4, 'hitam' => 3]],
            'PRD008' => ['KTG005', 'Cargo Pants', 229000, 'cargo-pants.jpg', 'Celana cargo dengan potongan kasual dan kantong fungsional.', 1.1, 0.57, 'SUP004', 5, ['khaki' => 4, 'hitam' => 4, 'army' => 2]],
            'PRD009' => ['KTG005', 'Straight Pants', 209000, 'straight-pants.jpg', 'Straight pants dengan potongan clean untuk berbagai gaya.', 1.0, 0.57, 'SUP004', 5, ['hitam' => 5, 'krem' => 3]],
            'PRD010' => ['KTG001', 'Long Sleeve T-Shirt', 159000, 'long-sleeve.jpg', 'Kaos lengan panjang dengan bahan adem untuk layering.', 0.6, 0.52, 'SUP005', 5, ['hitam' => 4, 'putih' => 3]],
        ];

        $sizeWeights = ['S' => 0.22, 'M' => 0.36, 'L' => 0.28, 'XL' => 0.14];

        $variantNumber = 1;
        $created = CarbonImmutable::parse('2025-12-20 09:00:00');

        foreach ($catalog as $productId => $c) {
            [$cat, $name, $price, $image, $desc, $popularity, $costRatio, $supplier, $minStock, $colors] = $c;

            $this->products[$productId] = [
                'id_kategori' => $cat,
                'nama_produk' => $name,
                'harga_jual' => $price,
                'deskripsi' => $desc,
                'gambar_produk' => $image,
                'supplier' => $supplier,
                'popularity' => $popularity,
                'cost_ratio' => $costRatio,
                'stok_minimum' => $minStock,
                'colors' => $colors,
                'created_at' => $created->toDateTimeString(),
                'variants' => [],
            ];

            foreach ($colors as $color => $colorWeight) {
                foreach ($sizeWeights as $size => $sizeWeight) {
                    $variantId = $this->id('VAR', $variantNumber++, 4);

                    $this->variants[$variantId] = [
                        'id_produk' => $productId,
                        'ukuran' => $size,
                        'warna' => $color,
                        'weight' => $colorWeight * $sizeWeight,
                        'stok_minimum' => $minStock,
                        'created_at' => $created->toDateTimeString(),
                    ];

                    $this->products[$productId]['variants'][] = $variantId;
                    $this->stock[$variantId] = 0;
                    $this->incoming[$variantId] = 0;
                }
            }
        }

        // Titik pesan ulang: makin laku sebuah varian, makin tinggi batasnya.
        $totalShare = 0;
        $shares = [];

        foreach ($this->variants as $variantId => $variant) {
            $product = $this->products[$variant['id_produk']];

            // Porsi permintaan varian = popularitas produk x porsi warna x porsi ukuran.
            $shares[$variantId] = $product['popularity']
                * $variant['weight']
                / array_sum($product['colors']);

            $totalShare += $shares[$variantId];
        }

        foreach ($shares as $variantId => $share) {
            $normalized = $share / $totalShare;

            $this->reorderPoint[$variantId] =
                12 + (int) round($normalized * 24 * 7);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Supplier
    |--------------------------------------------------------------------------
    */

    private function buildSuppliers(): void
    {
        $this->suppliers = [
            'SUP001' => ['Nusantara Apparel Supply', '081357924681', 'Jl. Margomulyo Indah, Surabaya'],
            'SUP002' => ['Urban Garment Wholesale', '082233764915', 'Jl. Kopo, Bandung'],
            'SUP003' => ['Dailywear Distributor', '085889214376', 'Jl. Pademangan, Jakarta Utara'],
            'SUP004' => ['Prima Fashion Supply', '081299017733', 'Jl. Cipulir Raya, Jakarta Selatan'],
            'SUP005' => ['Mitra Kaos Bandung', '082177405521', 'Jl. Cibaduyut, Bandung'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Promo
    |--------------------------------------------------------------------------
    */

    private function buildPromos(): void
    {
        $data = [
            ['Tahun Baru Kaos Fest', 10, '2026-01-01 00:00:00', '2026-01-10 23:59:59', ['PRD001', 'PRD002', 'PRD003', 'PRD010']],
            ['Ramadhan Fashion Fest', 20, '2026-02-16 00:00:00', '2026-03-19 23:59:59', ['PRD006', 'PRD007', 'PRD008', 'PRD009']],
            ['Lebaran Super Sale', 25, '2026-03-10 00:00:00', '2026-03-19 23:59:59', ['PRD001', 'PRD002', 'PRD004', 'PRD005']],
            ['Back to School', 15, '2026-07-13 00:00:00', '2026-07-31 23:59:59', ['PRD001', 'PRD003', 'PRD008', 'PRD009']],
            ['Merdeka 17an', 17, '2026-08-10 00:00:00', '2026-08-20 23:59:59', ['PRD001', 'PRD002', 'PRD003', 'PRD010']],
            ['Harbolnas 9.9', 25, '2026-09-09 00:00:00', '2026-09-12 23:59:59', ['PRD004', 'PRD005']],
            ['Double Date 10.10', 20, '2026-10-08 00:00:00', '2026-10-12 23:59:59', ['PRD006', 'PRD007', 'PRD010']],
            ['Harbolnas 11.11', 30, '2026-11-09 00:00:00', '2026-11-13 23:59:59', ['PRD002', 'PRD004', 'PRD005']],
            ['Black Friday', 25, '2026-11-24 00:00:00', '2026-11-30 23:59:59', ['PRD004', 'PRD007', 'PRD008']],
            ['Promo Akhir Tahun', 15, '2026-12-01 00:00:00', '2026-12-31 23:59:59', ['PRD001', 'PRD002', 'PRD003', 'PRD006', 'PRD008', 'PRD009', 'PRD010']],
            ['Harbolnas 12.12', 25, '2026-12-10 00:00:00', '2026-12-14 23:59:59', ['PRD004', 'PRD005', 'PRD007']],
        ];

        foreach ($data as $i => [$name, $percent, $start, $end, $productIds]) {
            $this->promos[$this->id('PRM', $i + 1, 3)] = [
                'nama_promo' => $name,
                'persen_diskon' => $percent,
                'tanggal_mulai' => CarbonImmutable::parse($start),
                'tanggal_selesai' => CarbonImmutable::parse($end),
                'products' => $productIds,
            ];
        }
    }

    /** Promo aktif dengan diskon terbesar (sama seperti PricingService). */
    private function promoFor(string $productId, CarbonImmutable $at): ?array
    {
        $best = null;

        foreach ($this->promos as $promoId => $promo) {
            if (
                in_array($productId, $promo['products'], true)
                && $promo['tanggal_mulai'] <= $at
                && $promo['tanggal_selesai'] >= $at
                && (
                    $best === null
                    || $promo['persen_diskon'] > $best['persen']
                )
            ) {
                $best = ['id' => $promoId, 'persen' => $promo['persen_diskon']];
            }
        }

        return $best;
    }

    private function maxPromoOnDay(CarbonImmutable $day): int
    {
        $max = 0;

        foreach ($this->promos as $promo) {
            if (
                $promo['tanggal_mulai']->startOfDay() <= $day
                && $promo['tanggal_selesai']->startOfDay() >= $day
            ) {
                $max = max($max, $promo['persen_diskon']);
            }
        }

        return $max;
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Simulasi hari demi hari
    |--------------------------------------------------------------------------
    */

    private function simulate(): void
    {
        // Modal awal: stok pertama dibeli akhir Desember 2025 (sebelum toko
        // mulai berjualan 3 Januari 2026), supaya data 2026 tidak diawali
        // pengeluaran raksasa yang membuat grafik arus kas timpang.
        $start = CarbonImmutable::parse('2025-12-22');

        foreach (array_keys($this->suppliers) as $supplierId) {
            $lines = [];

            foreach ($this->products as $product) {
                if ($product['supplier'] !== $supplierId) {
                    continue;
                }

                foreach ($product['variants'] as $variantId) {
                    $lines[$variantId] = $this->between(4, 7) * 5;
                }
            }

            $this->createPurchase(
                $start->setTime(10, 0),
                $supplierId,
                $lines,
                5,
                true
            );
        }

        for ($day = $start; $day <= $this->lastDay; $day = $day->addDay()) {
            $this->receivePurchases($day);

            if ($day >= CarbonImmutable::parse(self::FIRST_SALE_DAY)) {
                $this->simulateSales($day);

                if ($day->toDateString() === '2026-12-08') {
                    $this->orderForHarbolnas($day);
                }

                $this->reorderCheck($day);
            }
        }
    }

    private function receivePurchases(CarbonImmutable $day): void
    {
        $key = $day->toDateString();

        foreach ($this->receipts[$key] ?? [] as $lines) {
            foreach ($lines as $variantId => $qty) {
                $this->stock[$variantId] += $qty;
                $this->incoming[$variantId] -= $qty;
            }
        }
    }

    /**
     * Supplier yang berhenti menerima pesanan mulai tanggal tertentu
     * (barang kosong). Dipakai supaya beberapa varian menipis di akhir data
     * dan fitur low stock di dashboard admin punya contoh nyata.
     */
    private const SUPPLIER_STOPS_ORDERS = [
        'SUP003' => '2026-11-01',
    ];

    /**
     * Pesanan yang sengaja dibuat sebelum Harbolnas 12.12: baru dipesan
     * tanggal 8 Desember, tiba setelah data berakhir, jadi berstatus DIPESAN.
     */
    private function orderForHarbolnas(CarbonImmutable $day): void
    {
        $lines = [];

        foreach (['PRD004', 'PRD005'] as $productId) {
            foreach ($this->products[$productId]['variants'] as $variantId) {
                if (in_array($this->variants[$variantId]['ukuran'], ['M', 'L'], true)) {
                    $lines[$variantId] = 30;
                }
            }
        }

        $this->lastOrder['SUP002'] = $day;

        $this->createPurchase(
            $day->setTime(14, 20),
            'SUP002',
            $lines,
            4,
            true
        );
    }

    private function reorderCheck(CarbonImmutable $day): void
    {
        foreach (array_keys($this->suppliers) as $supplierId) {
            $stopsFrom = self::SUPPLIER_STOPS_ORDERS[$supplierId] ?? null;

            if ($stopsFrom !== null && $day->toDateString() >= $stopsFrom) {
                continue;
            }

            $variantIds = [];

            foreach ($this->products as $product) {
                if ($product['supplier'] === $supplierId) {
                    array_push($variantIds, ...$product['variants']);
                }
            }

            $low = false;
            $urgent = false;

            foreach ($variantIds as $variantId) {
                $effective = $this->stock[$variantId] + $this->incoming[$variantId];

                if ($effective <= $this->reorderPoint[$variantId]) {
                    $low = true;
                }

                if ($this->stock[$variantId] <= 2) {
                    $urgent = true;
                }
            }

            if (!$low) {
                continue;
            }

            $last = $this->lastOrder[$supplierId] ?? null;

            if (
                $last
                && (int) round($last->diffInDays($day, true)) < 3
                && !$urgent
            ) {
                continue;
            }

            $lines = [];

            foreach ($variantIds as $variantId) {
                $effective = $this->stock[$variantId] + $this->incoming[$variantId];
                $point = $this->reorderPoint[$variantId];

                if ($effective > $point * 1.5) {
                    continue;
                }

                $target = $point + $this->between(30, 50);
                $qty = (int) (round(max(20, $target - $effective) / 5) * 5);

                $lines[$variantId] = $qty;
            }

            if (!$lines) {
                continue;
            }

            $this->lastOrder[$supplierId] = $day;

            $this->createPurchase(
                $day->setTime(16, $this->between(0, 59)),
                $supplierId,
                $lines,
                $this->between(2, 5),
                false
            );
        }
    }

    private function createPurchase(
        CarbonImmutable $orderedAt,
        string $supplierId,
        array $lines,
        int $leadDays,
        bool $neverCancel
    ): void {
        $purchaseId = $this->id('PB', ++$this->pbCounter, 4);
        $adminId = $this->rnd() < 0.7 ? 'ADM021' : 'ADM022';

        $receivedAt = $orderedAt
            ->addDays($leadDays)
            ->setTime($this->between(9, 16), $this->between(0, 59));

        if (!$neverCancel && $this->rnd() < 0.04) {
            $status = 'BATAL';
        } elseif ($receivedAt <= $this->lastDay->endOfDay()) {
            $status = 'DITERIMA';
        } else {
            $status = 'DIPESAN';
        }

        $total = 0;

        foreach ($lines as $variantId => $qty) {
            $productId = $this->variants[$variantId]['id_produk'];
            $product = $this->products[$productId];

            // Harga beli naik sedikit di semester 2.
            $drift = $orderedAt->month >= 7 ? 1.04 : 1.0;
            $cost = (int) (round(
                $product['harga_jual'] * $product['cost_ratio'] * $drift / 500
            ) * 500);

            $this->rowsDetailPembelian[] = [
                'id_detail_pembelian' => $this->id('DPB', ++$this->dpbCounter, 5),
                'id_pembelian' => $purchaseId,
                'id_varian' => $variantId,
                'jumlah' => $qty,
                'harga_satuan' => $cost,
            ];

            $total += $qty * $cost;

            if ($status !== 'BATAL') {
                $this->incoming[$variantId] += $qty;
            }
        }

        if ($status !== 'BATAL') {
            $this->receipts[$receivedAt->toDateString()][] = $lines;
        }

        $this->rowsPembelian[] = [
            'id_pembelian' => $purchaseId,
            'id_supplier' => $supplierId,
            'id_akun' => $adminId,
            'tanggal_pembelian' => $orderedAt->toDateTimeString(),
            'tanggal_diterima' => $status === 'DITERIMA'
                ? $receivedAt->toDateTimeString()
                : null,
            'status_pembelian' => $status,
            'created_at' => $orderedAt->toDateTimeString(),
            'updated_at' => ($status === 'DITERIMA' ? $receivedAt : $orderedAt)
                ->toDateTimeString(),
        ];

        if ($status === 'DITERIMA') {
            $this->rowsPengeluaran[] = [
                'id_pengeluaran' => null,
                'id_akun' => $adminId,
                'id_pembelian' => $purchaseId,
                'tanggal_pengeluaran' => $receivedAt->toDateTimeString(),
                'jenis_pengeluaran' => 'PEMBELIAN_PRODUK',
                'nominal' => $total,
                'keterangan' => 'Pembelian '.$purchaseId
                    .' dikonfirmasi diterima dari '
                    .$this->suppliers[$supplierId][0],
                'created_at' => $receivedAt->toDateTimeString(),
                'updated_at' => $receivedAt->toDateTimeString(),
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 7. Penjualan
    |--------------------------------------------------------------------------
    */

    private function demandFor(CarbonImmutable $day): int
    {
        $month = [1 => 0.85, 2 => 0.95, 3 => 1.5, 4 => 0.7, 5 => 0.9, 6 => 1.0, 7 => 1.05, 8 => 1.1, 9 => 1.1, 10 => 1.15, 11 => 1.45, 12 => 1.3];
        $weekday = [1 => 0.85, 2 => 0.8, 3 => 0.85, 4 => 0.9, 5 => 1.1, 6 => 1.45, 7 => 1.35];

        $promoMax = $this->maxPromoOnDay($day);
        $promoFactor = $promoMax >= 25 ? 1.5 : ($promoMax >= 15 ? 1.3 : ($promoMax > 0 ? 1.15 : 1.0));

        $payday = ($day->day >= 25 && $day->day <= 28) ? 1.2 : ($day->day <= 3 ? 1.1 : 1.0);

        // Libur Idul Fitri: toko hampir tutup.
        $holiday = in_array($day->toDateString(), ['2026-03-20', '2026-03-21'], true)
            ? 0.15
            : 1.0;

        $base = 9.0
            * $month[$day->month]
            * $weekday[$day->dayOfWeekIso]
            * $promoFactor
            * $payday
            * $holiday
            * (0.8 + 0.4 * $this->rnd());

        return (int) round($base);
    }

    private function simulateSales(CarbonImmutable $day): void
    {
        $count = $this->demandFor($day);

        if ($count < 1) {
            return;
        }

        $promoMax = $this->maxPromoOnDay($day);
        $onlineChance = $promoMax >= 20 ? 0.5 : 0.42;

        $offlineHours = [10 => 3, 11 => 5, 12 => 7, 13 => 6, 14 => 5, 15 => 6, 16 => 8, 17 => 9, 18 => 10, 19 => 10, 20 => 7, 21 => 3];
        $onlineHours = [8 => 2, 9 => 3, 10 => 4, 11 => 4, 12 => 6, 13 => 5, 14 => 4, 15 => 4, 16 => 5, 17 => 6, 18 => 8, 19 => 11, 20 => 14, 21 => 14, 22 => 10, 23 => 5];

        $events = [];

        for ($i = 0; $i < $count; $i++) {
            $online = $this->rnd() < $onlineChance;
            $hour = $this->pick($online ? $onlineHours : $offlineHours);

            $events[] = [
                $day->setTime($hour, $this->between(0, 59), $this->between(0, 59)),
                $online ? 'ONLINE' : 'OFFLINE',
            ];
        }

        usort($events, fn ($a, $b) => $a[0] <=> $b[0]);

        // Customer yang boleh bertransaksi hari ini.
        $eligible = [];

        foreach ($this->customers as $id => $customer) {
            if (
                $customer['reg'] <= $day
                && ($customer['until'] === null || $customer['until'] > $day)
            ) {
                $eligible[$id] = $customer['weight'];
            }
        }

        foreach ($events as [$at, $channel]) {
            $this->createSale($at, $channel, $eligible);
        }
    }

    private function createSale(
        CarbonImmutable $at,
        string $channel,
        array $eligible
    ): void {
        $online = $channel === 'ONLINE';

        // Siapa pembelinya.
        $customerId = null;
        $cashierId = null;

        if ($online) {
            $customerId = $this->pick($eligible);

            if ($customerId === null) {
                return;
            }
        } else {
            $cashierId = $this->activeCashier($at);

            if ($this->rnd() < 0.4) {
                $customerId = $this->pick($eligible);
            }
        }

        // Status pembayaran lebih dulu, karena menentukan efek ke stok.
        $paid = true;
        $paymentStatus = 'BERHASIL';
        $saleStatus = 'SELESAI';

        if ($online) {
            $r = $this->rnd();

            if ($r < 0.88) {
                $age = (int) round(
                    $at->startOfDay()->diffInDays($this->lastDay, true)
                );
                $saleStatus = $age >= 6 ? 'SELESAI' : ($age >= 3 ? 'DIKIRIM' : 'DIPROSES');

                if ($age <= 1 && $this->rnd() < 0.3) {
                    $paid = false;
                    $paymentStatus = 'MENUNGGU';
                    $saleStatus = 'BARU';
                }
            } elseif ($r < 0.94) {
                $paid = false;
                $paymentStatus = 'GAGAL';
                $saleStatus = 'BATAL';
            } else {
                $paid = false;
                $paymentStatus = 'MENUNGGU';
                $saleStatus = 'BATAL';
            }
        }

        // Pilih item.
        $items = $this->pickItems($at);

        if (!$items) {
            return; // stok habis: penjualan hilang
        }

        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['unit_price'] * $item['qty'];
        }

        // Poin member.
        $usedPoints = 0;
        $earned = 0;

        if ($customerId !== null) {
            $balance = $this->balance[$customerId];

            if ($balance >= 10 && $this->rnd() < 0.18) {
                $request = $this->between(5, min($balance, 60));
                $usedPoints = max(
                    0,
                    min($request, $balance, intdiv((int) $subtotal, self::POINT_VALUE))
                );
            }

            if ($paid) {
                $earned = intdiv((int) $subtotal, self::EARN_PER_RUPIAH);
            }
        }

        $pointDiscount = $usedPoints * self::POINT_VALUE;
        $total = max(0, $subtotal - $pointDiscount);

        $saleId = $this->id('PJ', ++$this->pjCounter, 5);

        $method = $online
            ? 'QR'
            : ($this->rnd() < 0.6 ? 'TUNAI' : 'QR');

        $reference = ($method === 'QR' && $paymentStatus === 'BERHASIL')
            ? 'QR-'.$saleId.'-'.$at->format('His')
            : null;

        $updatedAt = $at;

        if ($online && $saleStatus === 'SELESAI') {
            $updatedAt = $at->addDays($this->between(2, 4));
        } elseif ($online && $saleStatus === 'DIKIRIM') {
            $updatedAt = $at->addDay();
        }

        $this->rowsPenjualan[] = [
            'id_penjualan' => $saleId,
            'id_akun' => $online ? $customerId : $cashierId,
            'tanggal_penjualan' => $at->toDateTimeString(),
            'kanal_penjualan' => $channel,
            'metode_pembayaran' => $method,
            'status_pembayaran' => $paymentStatus,
            'referensi_pembayaran' => $reference,
            'nominal_bayar' => $total,
            'status_penjualan' => $saleStatus,
            'alamat_pengiriman' => $online
                ? $this->customers[$customerId]['address']
                : null,
            'poin_didapat' => $earned,
            'poin_digunakan' => $usedPoints,
            'diskon_poin' => $pointDiscount,
            'created_at' => $at->toDateTimeString(),
            'updated_at' => $updatedAt->toDateTimeString(),
        ];

        if (!$online && $customerId !== null) {
            $this->rowsPenjualanMember[] = [
                'id_penjualan' => $saleId,
                'id_akun_customer' => $customerId,
            ];
        }

        foreach ($items as $item) {
            $this->rowsDetailPenjualan[] = [
                'id_detail_penjualan' => $this->id('DPJ', ++$this->dpjCounter, 5),
                'id_penjualan' => $saleId,
                'id_varian' => $item['variant'],
                'jumlah' => $item['qty'],
                'harga_satuan' => $item['unit_price'],
                'harga_normal' => $item['normal_price'],
                'persen_diskon' => $item['percent'],
                'id_promo' => $item['promo_id'],
            ];

            if ($paid) {
                $this->stock[$item['variant']] -= $item['qty'];
            }
        }

        if ($paid && $customerId !== null) {
            $this->balance[$customerId] += $earned - $usedPoints;
        }

        if ($online && $paid) {
            $week = $at->startOfWeek()->toDateString();
            $this->onlinePaidPerWeek[$week] = ($this->onlinePaidPerWeek[$week] ?? 0) + 1;
        }
    }

    private function activeCashier(CarbonImmutable $at): string
    {
        $date = $at->toDateString();
        $pool = [];

        foreach ($this->staff as $staff) {
            if ($staff['from'] <= $date && $staff['to'] >= $date) {
                $pool[] = $staff['id'];
            }
        }

        return $pool[$this->between(0, count($pool) - 1)];
    }

    private function pickItems(CarbonImmutable $at): array
    {
        $lineCount = $this->pick([1 => 55, 2 => 30, 3 => 12, 4 => 3]);

        $items = [];
        $usedVariants = [];

        for ($line = 0; $line < $lineCount; $line++) {
            for ($attempt = 0; $attempt < 5; $attempt++) {
                $productId = $this->pickProduct($at);

                $weights = [];

                foreach ($this->products[$productId]['variants'] as $variantId) {
                    if (
                        $this->stock[$variantId] > 0
                        && !isset($usedVariants[$variantId])
                    ) {
                        $weights[$variantId] = $this->variants[$variantId]['weight'];
                    }
                }

                $variantId = $this->pick($weights);

                if ($variantId === null) {
                    continue;
                }

                $qty = min(
                    $this->pick([1 => 80, 2 => 15, 3 => 5]),
                    $this->stock[$variantId]
                );

                $promo = $this->promoFor($productId, $at);
                $normal = $this->products[$productId]['harga_jual'];
                $percent = $promo['persen'] ?? 0;

                $items[] = [
                    'variant' => $variantId,
                    'qty' => $qty,
                    'normal_price' => $normal,
                    'percent' => $percent,
                    'promo_id' => $promo['id'] ?? null,
                    'unit_price' => round($normal * (1 - $percent / 100), 2),
                ];

                $usedVariants[$variantId] = true;

                break;
            }
        }

        return $items;
    }

    private function pickProduct(CarbonImmutable $at): string
    {
        $weights = [];

        foreach ($this->products as $productId => $product) {
            $weight = $product['popularity']
                * $this->seasonFactor($product['id_kategori'], $at->month);

            if ($this->promoFor($productId, $at)) {
                $weight *= 1.7;
            }

            $weights[$productId] = $weight;
        }

        return $this->pick($weights);
    }

    private function seasonFactor(string $categoryId, int $month): float
    {
        // Hoodie dan sweater lebih laku di musim hujan.
        if (in_array($categoryId, ['KTG002', 'KTG003'], true)) {
            if (in_array($month, [11, 12, 1, 2], true)) {
                return 1.5;
            }

            if (in_array($month, [6, 7, 8], true)) {
                return 0.7;
            }
        }

        if ($categoryId === 'KTG001' && in_array($month, [3, 6, 7, 8], true)) {
            return 1.2;
        }

        if ($categoryId === 'KTG005' && $month === 7) {
            return 1.25;
        }

        return 1.0;
    }

    /*
    |--------------------------------------------------------------------------
    | 8. Pengeluaran operasional
    |--------------------------------------------------------------------------
    */

    private function buildOperationalExpenses(): void
    {
        $monthNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $add = function (
            CarbonImmutable $at,
            string $type,
            float $amount,
            string $note
        ): void {
            if ($at > $this->lastDay->endOfDay()) {
                return;
            }

            $this->rowsPengeluaran[] = [
                'id_pengeluaran' => null,
                'id_akun' => $this->rnd() < 0.7 ? 'ADM021' : 'ADM022',
                'id_pembelian' => null,
                'tanggal_pengeluaran' => $at->toDateTimeString(),
                'jenis_pengeluaran' => $type,
                'nominal' => $amount,
                'keterangan' => $note,
                'created_at' => $at->toDateTimeString(),
                'updated_at' => $at->toDateTimeString(),
            ];
        };

        for ($month = 1; $month <= 12; $month++) {
            $label = $monthNames[$month].' 2026';
            $first = CarbonImmutable::create(2026, $month, 1);

            $add($first->setTime(9, 15), 'SEWA', 3500000, 'Sewa toko bulan '.$label);

            $add(
                $first->setDay(5)->setTime(10, 30),
                'LISTRIK',
                $this->between(680, 950) * 1000,
                'Pembayaran listrik bulan '.$label
            );

            $add($first->setDay(10)->setTime(11, 0), 'INTERNET', 385000, 'Internet dan WiFi toko bulan '.$label);

            if ($this->rnd() < 0.5) {
                $add(
                    $first->setDay($this->between(12, 26))->setTime(14, 0),
                    'PERAWATAN',
                    $this->between(15, 75) * 10000,
                    $this->pick([
                        'Servis AC toko' => 1,
                        'Perbaikan rak display' => 1,
                        'Perawatan mesin kasir dan printer struk' => 1,
                    ])
                );
            }

            $miscCount = $this->between(1, 2);

            for ($i = 0; $i < $miscCount; $i++) {
                $add(
                    $first->setDay($this->between(2, 27))->setTime($this->between(10, 17), $this->between(0, 59)),
                    'LAINNYA',
                    $this->between(8, 45) * 10000,
                    $this->pick([
                        'Pembelian plastik kemasan' => 1,
                        'Lakban dan kertas resi' => 1,
                        'Hanger dan price tag' => 1,
                        'Biaya kebersihan dan parkir' => 1,
                        'Alat tulis kantor' => 1,
                        'Cetak banner promo' => 1,
                        'Konsumsi karyawan' => 1,
                    ])
                );
            }
        }

        $add(
            CarbonImmutable::parse('2026-03-12 13:00:00'),
            'LAINNYA',
            4500000,
            'THR karyawan Idul Fitri'
        );

        // Ongkir pesanan online, dicatat tiap Senin untuk minggu sebelumnya.
        foreach ($this->onlinePaidPerWeek as $weekStart => $orders) {
            $monday = CarbonImmutable::parse($weekStart)->addWeek();

            $add(
                $monday->setTime(15, 0),
                'ONGKIR',
                $orders * 15000,
                'Ongkos kirim '.$orders.' pesanan online minggu '.$weekStart
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 9. Simpan ke database
    |--------------------------------------------------------------------------
    */

    private function insert(string $table, array $rows): void
    {
        foreach (array_chunk($rows, 400) as $chunk) {
            DB::table($table)->insert($chunk);
        }
    }

    private function persist(): void
    {
        $this->insert('akun', $this->accounts);

        $this->insert('kategori_produk', array_map(
            fn ($id, $name) => [
                'id_kategori' => $id,
                'nama_kategori' => $name,
                'created_at' => '2025-12-20 09:00:00',
                'updated_at' => '2025-12-20 09:00:00',
            ],
            array_keys($this->categories),
            $this->categories
        ));

        $products = [];

        foreach ($this->products as $id => $p) {
            $products[] = [
                'id_produk' => $id,
                'id_kategori' => $p['id_kategori'],
                'nama_produk' => $p['nama_produk'],
                'harga_jual' => $p['harga_jual'],
                'deskripsi' => $p['deskripsi'],
                'gambar_produk' => $p['gambar_produk'],
                'status_produk' => 'AKTIF',
                'created_at' => $p['created_at'],
                'updated_at' => $p['created_at'],
            ];
        }

        $this->insert('produk', $products);

        $variants = [];

        foreach ($this->variants as $id => $v) {
            $variants[] = [
                'id_varian' => $id,
                'id_produk' => $v['id_produk'],
                'ukuran' => $v['ukuran'],
                'warna' => $v['warna'],
                'stok' => $this->stock[$id],
                'stok_minimum' => $v['stok_minimum'],
                'created_at' => $v['created_at'],
                'updated_at' => $this->lastDay->endOfDay()->toDateTimeString(),
            ];
        }

        $this->insert('varian_produk', $variants);

        $suppliers = [];

        foreach ($this->suppliers as $id => [$name, $phone, $address]) {
            $suppliers[] = [
                'id_supplier' => $id,
                'nama_supplier' => $name,
                'no_telp' => $phone,
                'alamat' => $address,
                'created_at' => '2025-12-15 09:00:00',
                'updated_at' => '2025-12-15 09:00:00',
            ];
        }

        $this->insert('supplier', $suppliers);

        $promos = [];
        $promoProducts = [];

        foreach ($this->promos as $id => $promo) {
            $created = $promo['tanggal_mulai']->subDays(5)->toDateTimeString();

            $promos[] = [
                'id_promo' => $id,
                'nama_promo' => $promo['nama_promo'],
                'persen_diskon' => $promo['persen_diskon'],
                'tanggal_mulai' => $promo['tanggal_mulai']->toDateTimeString(),
                'tanggal_selesai' => $promo['tanggal_selesai']->toDateTimeString(),
                'created_at' => $created,
                'updated_at' => $created,
            ];

            foreach ($promo['products'] as $productId) {
                $promoProducts[] = [
                    'id_promo' => $id,
                    'id_produk' => $productId,
                ];
            }
        }

        $this->insert('promo', $promos);
        $this->insert('promo_produk', $promoProducts);

        $this->insert('pembelian_produk', $this->rowsPembelian);
        $this->insert('detail_pembelian', $this->rowsDetailPembelian);

        $this->insert('penjualan', $this->rowsPenjualan);
        $this->insert('penjualan_member', $this->rowsPenjualanMember);
        $this->insert('detail_penjualan', $this->rowsDetailPenjualan);

        // Beri nomor pengeluaran berurutan menurut tanggal.
        usort(
            $this->rowsPengeluaran,
            fn ($a, $b) => strcmp($a['tanggal_pengeluaran'], $b['tanggal_pengeluaran'])
        );

        foreach ($this->rowsPengeluaran as $i => &$row) {
            $row['id_pengeluaran'] = $this->id('PNG', $i + 1, 4);
        }
        unset($row);

        $this->insert('pengeluaran', $this->rowsPengeluaran);
    }
}
