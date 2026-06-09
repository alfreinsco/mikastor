# MIKASTOR

MIKASTOR adalah aplikasi penjualan minyak kayu putih berbasis PHP dan MySQL. Aplikasi ini mencakup promosi produk, akun pelanggan, pemesanan online, upload bukti transfer, konfirmasi pembayaran oleh admin, pengiriman, kasir toko, stok produk, dan riwayat penjualan.

## Fitur Utama

- Homepage promosi publik untuk menarik pelanggan.
- Login multi-role: pemilik, kasir, dan pelanggan.
- Manajemen produk dan stok.
- Kasir internal untuk transaksi langsung.
- Pelanggan dapat daftar akun, belanja, checkout, upload bukti transfer, dan memantau pesanan.
- Admin/kasir dapat melihat pesanan online, mengecek bukti transfer, konfirmasi lunas, mengirim barang, dan menyelesaikan pesanan.
- Tabel utama sudah mendukung pencarian, filter, dan pagination.
- Database dipisah menjadi migration dan seeder agar mudah dikelola.

## Kebutuhan Sistem

- PHP 8.1 atau lebih baru
- MySQL/MariaDB
- Browser modern

## Konfigurasi

File konfigurasi environment tersedia di:

```bash
.env
```

Variabel database yang digunakan:

```env
DB_HOST=127.0.0.1
DB_NAME=mikastor
DB_USER=root
DB_PASS=
```

Catatan: aplikasi saat ini membaca environment variable melalui `getenv()`. Jika server lokal tidak otomatis membaca `.env`, pastikan variable tersebut tersedia di environment, atau gunakan default yang sudah sama dengan isi `.env`.

## Setup Database

Jalankan migration:

```bash
mysql -u root -p < database/migrate.sql
```

Jalankan seeder:

```bash
mysql -u root -p mikastor < database/seed.sql
```

Jika ingin reset total database:

```bash
mysql -u root -p < database/fresh.sql
```

## Menjalankan Aplikasi

Dari root project:

```bash
php -S 127.0.0.1:8001 -t public
```

Buka:

```text
http://127.0.0.1:8001
```

Pengunjung akan diarahkan ke homepage promosi. User yang sudah login akan masuk ke dashboard sesuai role.

## Akun Awal

Pemilik:

```text
username: admin
password: admin123
```

Kasir:

```text
username: kasir
password: kasir123
```

Pelanggan dapat membuat akun sendiri melalui halaman daftar.

## Alur Transaksi Online

1. Pelanggan daftar atau login.
2. Pelanggan memilih produk di halaman belanja.
3. Pelanggan checkout dan memilih metode pembayaran.
4. Jika transfer bank, pelanggan upload bukti transfer.
5. Admin/kasir mengecek bukti transfer dan mengonfirmasi pembayaran lunas.
6. Sistem membuat nota penjualan otomatis.
7. Admin/kasir menandai pesanan dikirim.
8. Pelanggan menandai barang sudah diterima.
9. Pesanan selesai.

## Struktur Folder

```text
assets/                 Salinan aset untuk referensi root project
config/                 Konfigurasi koneksi database
database/               Migration, seeder, dan panduan database
includes/               Helper layout, auth, order, dan tabel
public/                 Document root aplikasi
public/assets/          CSS, logo, dan gambar publik
public/uploads/         Upload bukti transfer pelanggan
```

## File Penting

- `public/home.php`: homepage promosi.
- `public/index.php`: dashboard internal.
- `public/toko.php`: halaman belanja pelanggan.
- `public/bayar.php`: upload bukti pembayaran.
- `public/pesanan_online.php`: daftar pesanan online admin/kasir.
- `public/pesanan_online_detail.php`: detail dan aksi pesanan online.
- `public/produk.php`: daftar produk.
- `public/produk_form.php`: tambah/edit produk.
- `public/kasir.php`: transaksi kasir.
- `database/migrate.sql`: menjalankan seluruh migration.
- `database/seed.sql`: menjalankan seluruh seeder.
- `database/fresh.sql`: reset dan setup ulang database.

## Catatan Upload

File bukti transfer disimpan di:

```text
public/uploads/bukti-transfer/
```

Folder ini diabaikan oleh Git agar bukti transfer pelanggan tidak ikut masuk repository.
