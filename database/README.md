# Panduan Database Lewat Terminal

Struktur database sekarang dipisah menjadi:

- `migrations/`: membuat atau mengubah struktur tabel.
- `seeders/`: mengisi data awal.
- `migrate.sql`: menjalankan seluruh migration.
- `seed.sql`: menjalankan seluruh seeder.
- `fresh.sql`: menghapus database lalu membuat ulang dari awal.

## Migration

Jalankan dari root project:

```bash
mysql -u root -p < database/migrate.sql
```

Jika MySQL memakai host:

```bash
mysql -h 127.0.0.1 -u root -p < database/migrate.sql
```

## Seeder

Setelah migration selesai, isi data awal:

```bash
mysql -u root -p mikastor < database/seed.sql
```

Seeder berisi:

- Akun awal `admin/admin123`
- Akun awal `kasir/kasir123`
- Pelanggan umum
- Produk minyak kayu putih awal
- Data laporan keuangan 2023-2025 dari file Excel `data dede.xlsx`

## Fresh Database

Gunakan ini hanya jika data lama boleh dihapus:

```bash
mysql -u root -p < database/fresh.sql
```

Command ini akan menghapus database `mikastor`, membuat ulang semua tabel, lalu menjalankan seeder.

## File Schema Lama

File SQL gabungan masih tersedia di:

```bash
database/schema.sql
```

Jika ingin import dengan satu file lama:

```bash
mysql -u root -p mikastor < database/schema.sql
```

Namun untuk pengelolaan jangka panjang, gunakan `migrations/` dan `seeders/`.

## Import Cepat Schema Lama

```bash
mysql -u USERNAME -p NAMA_DATABASE < database/schema.sql
```

Contoh:

```bash
mysql -u root -p mikastor < database/schema.sql
```

Setelah command dijalankan, terminal akan meminta password MySQL.

## Update Database Lama Dengan Schema

File `schema.sql` juga sudah memuat struktur modul pelanggan, pesanan online, pembayaran, dan pengiriman. Jika database `mikastor` sudah pernah dibuat sebelumnya, jalankan ulang file yang sama:

```bash
mysql -u root -p mikastor < database/schema.sql
```

## Buat Database Jika Belum Ada

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS mikastor;"
mysql -u root -p mikastor < database/schema.sql
```

## Import Dengan Host dan Port

Gunakan ini jika MySQL tidak berjalan di konfigurasi default:

```bash
mysql -h 127.0.0.1 -P 3306 -u root -p mikastor < database/schema.sql
```

## Cek Hasil Import

Masuk ke database:

```bash
mysql -u root -p mikastor
```

Lalu cek tabel:

```sql
SHOW TABLES;
```

## Catatan

- Ganti `USERNAME`, `NAMA_DATABASE`, host, dan port sesuai konfigurasi lokal.
- Jika database sudah berisi tabel dengan nama yang sama, cek isi `schema.sql` dulu agar tidak menimpa data penting.
- Untuk import ulang dari awal, pastikan data lama memang boleh dihapus sebelum menjalankan script SQL yang menghapus atau membuat ulang tabel.
