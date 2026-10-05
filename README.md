# FORMA — Sistem Basis Data UMKM Pakaian

FORMA adalah aplikasi berbasis web untuk membantu UMKM reseller pakaian dalam mengelola produk, stok, supplier, pembelian, penjualan online dan offline, promo, member point, pengeluaran, serta laporan.

Project ini dibuat menggunakan Laravel dan MySQL/MariaDB untuk kebutuhan Ujian Tengah Semester Basis Data.

## Fitur Utama

### Customer
- Registrasi dan login
- Melihat katalog produk
- Memilih varian ukuran dan warna
- Keranjang dan checkout
- Pembayaran QR simulasi
- Memperoleh dan menggunakan poin
- Melihat riwayat pesanan online
- Melihat promo aktif

### Kasir
- Login sebagai Kasir
- Point of Sale (POS)
- Transaksi offline member atau guest
- Pembayaran tunai atau QR
- Member memperoleh poin dari transaksi offline
- Riwayat transaksi Kasir
- Struk penjualan

### Admin
- Dashboard monitoring
- Kelola akun staf
- Kelola kategori
- Kelola produk dan gambar
- Kelola varian dan stok
- Kelola supplier
- Kelola pembelian produk
- Kelola promo
- Kelola pengeluaran
- Kelola penjualan
- Laporan pemasukan dan pengeluaran
- Monitoring best seller dan low stock

## Teknologi

- Laravel 12
- PHP 8.2
- MySQL / MariaDB
- Blade Template
- HTML, CSS, JavaScript
- Vite

## Struktur Basis Data

Sistem menggunakan 13 tabel utama:

1. `akun`
2. `kategori_produk`
3. `produk`
4. `varian_produk`
5. `supplier`
6. `pembelian_produk`
7. `detail_pembelian`
8. `penjualan`
9. `penjualan_member`
10. `detail_penjualan`
11. `pengeluaran`
12. `promo`
13. `promo_produk`

Beberapa konsep utama:

- Stok disimpan pada `varian_produk`.
- Pembelian menggunakan struktur header-detail.
- Penjualan online dan offline disimpan pada satu tabel `penjualan`.
- Transaksi offline member dihubungkan melalui `penjualan_member`.
- Pembelian dari supplier juga dicatat pada `pengeluaran`.
- Promo dan produk dihubungkan melalui `promo_produk`.
- Saldo poin dihitung dari histori transaksi berhasil.

## Instalasi Lokal

### 1. Install dependency

```bash
composer install
npm install
```

### 2. Siapkan file environment

Salin file:

```bash
cp .env.example .env
```

Untuk Windows, file `.env.example` juga dapat dicopy manual lalu diubah namanya menjadi `.env`.

### 3. Generate application key

```bash
php artisan key:generate
```

### 4. Buat database

Buat database MySQL/MariaDB dengan nama:

```text
umkm_pakaian
```

Kemudian sesuaikan konfigurasi database pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm_pakaian
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan migration dan seeder

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan membuat seluruh tabel dan mengisi data prototype.

### 6. Jalankan aplikasi

```bash
php artisan serve
```

Jika diperlukan untuk asset frontend:

```bash
npm run dev
```

Akses aplikasi melalui:

```text
http://127.0.0.1:8000
```

## Akun Demo

### Admin

```text
Username : admin
Password : admin123
```

### Kasir

```text
Username : siti
Password : kasir123
```

### Customer

```text
Username : budi
Password : budi123
```

## Prototype Seeder

Project menyediakan `PrototypeSeeder` berisi data demo seperti:

- akun Customer, Kasir, dan Admin
- kategori dan produk
- varian dan stok
- supplier
- pembelian produk
- promo aktif, expired, dan upcoming
- transaksi online
- transaksi offline member
- transaksi offline guest
- penggunaan member point
- pengeluaran pembelian dan operasional

Untuk mengembalikan database ke kondisi awal:

```bash
php artisan migrate:fresh --seed
```

> Perintah tersebut akan menghapus seluruh data pada database yang digunakan dan membangun ulang database dari migration dan seeder.

## Catatan

File berikut tidak disimpan di repository:

```text
.env
vendor/
node_modules/
.phpunit.result.cache
```

Gambar produk prototype disimpan pada:

```text
public/images/products/
```

Project ini dijalankan secara lokal menggunakan Laravel development server.
