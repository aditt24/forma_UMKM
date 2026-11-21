# Setup untuk Tim — Database OLTP (`umkm`)

Database UTS sudah diperbaiki dan diisi data demo untuk UAS Data Warehouse.
Ada dua cara mendapatkannya. **Pilih salah satu.**

## Cara A — Impor file SQL (paling mudah, tanpa Laravel)

Cocok untuk yang mengerjakan star schema, ETL (Pentaho), dan OLAP.

1. Pastikan MySQL berjalan.
2. Di HeidiSQL, buat database baru bernama **`umkm`** (collation `utf8mb4_unicode_ci`).
3. Klik database `umkm`, lalu **File → Run SQL file…** dan pilih
   [`database/dump/umkm_oltp.sql`](../database/dump/umkm_oltp.sql).
4. Refresh. Harus ada **14 tabel** (13 tabel UTS + `migrations`).

Cek cepat, jalankan di tab Query:

```sql
SELECT (SELECT COUNT(*) FROM penjualan)        AS penjualan,        -- 3937
       (SELECT COUNT(*) FROM detail_penjualan) AS detail_penjualan, -- 6456
       (SELECT COUNT(*) FROM pembelian_produk) AS pembelian,        -- 68
       (SELECT COUNT(*) FROM pengeluaran)      AS pengeluaran;      -- 177
```

> Catatan: kolom "Rows" di HeidiSQL hanya **perkiraan** dan bisa meleset
> sedikit (mis. tampil 6.320 padahal isinya 6.456). Pakai `COUNT(*)`.

## Cara B — Lewat Laravel (untuk yang menjalankan website)

Cocok untuk yang mengerjakan dashboard.

```powershell
git pull origin main
composer install
copy .env.example .env
php artisan key:generate
```

Ubah bagian database di `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm
DB_USERNAME=root
DB_PASSWORD=
```

Lalu buat database `umkm` kosong dan jalankan:

```powershell
php artisan migrate:fresh --seed
```

Hasilnya **sama persis** dengan file SQL (angka acak seeder memakai seed tetap).
`migrate:fresh` menghapus semua tabel di database itu, jadi jangan dijalankan
di database yang isinya mau dipertahankan.

## Apa itu tabel `migrations`?

Tabel bawaan Laravel yang mencatat migrasi mana yang sudah dijalankan
(14 baris). Jangan dihapus, dan **tidak perlu dipakai** di star schema/ETL.
Kalau terhapus lalu `php artisan migrate` dijalankan, Laravel mencoba membuat
ulang tabel yang sudah ada dan error.

## Isi database (ringkas)

| Data | Jumlah |
|---|---|
| Akun | 226 (220 customer, 4 kasir, 2 admin) |
| Kategori / produk / varian | 5 / 10 / 100 |
| Supplier / promo | 5 / 11 |
| Pembelian / detail | 68 / 352 |
| Penjualan / detail | 3.937 / 6.456 |
| Pengeluaran | 177 |

- Penjualan: **3 Jan – 9 Des 2026**. Stok awal dibeli 22 Des 2025.
- Pembelian: 66 DITERIMA, 1 BATAL, 1 DIPESAN.
- Penjualan berhasil: 3.733 transaksi. Sisanya gagal bayar, dibatalkan,
  atau masih menunggu.

## Kolom baru dibanding UTS

| Tabel | Kolom | Arti |
|---|---|---|
| `detail_penjualan` | `harga_normal` | Harga jual sebelum diskon promo |
| `detail_penjualan` | `persen_diskon` | Diskon promo yang dipakai (0 = tanpa promo) |
| `detail_penjualan` | `id_promo` | Promo yang dipakai (NULL = tanpa promo) |
| `pembelian_produk` | `tanggal_diterima` | Kapan barang diterima (NULL jika belum) |

## Aturan data yang penting untuk ETL

- **Penjualan yang dihitung** hanya yang `status_pembayaran = 'BERHASIL'`
  (status_penjualan bukan `BATAL`).
- `penjualan.id_akun`: untuk `ONLINE` adalah **customer**, untuk `OFFLINE`
  adalah **kasir**. Customer member transaksi offline ada di `penjualan_member`.
- `detail_penjualan.harga_satuan` = harga **setelah** promo.
  `harga_normal × (1 − persen_diskon/100) = harga_satuan`.
- `penjualan.nominal_bayar` = jumlah (`jumlah × harga_satuan`) dikurangi
  `diskon_poin`.
- Pengeluaran `PEMBELIAN_PRODUK` hanya ada untuk pembelian `DITERIMA`, dan
  nominalnya sama dengan total detail pembelian itu. **Jangan menjumlahkan
  `pengeluaran` jenis ini bersama `detail_pembelian`** (terhitung dua kali).
- Stok tidak pernah minus. Stok akhir = total pembelian DITERIMA − total
  penjualan BERHASIL.

## Aturan bersama

Nama tabel dan kolom di `umkm` **sudah dikunci**. Kalau perlu mengubahnya,
kabari anggota yang terdampak dulu.
