CREATE DATABASE IF NOT EXISTS mikastor
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mikastor;

SOURCE database/migrations/001_create_users_table.sql;
SOURCE database/migrations/002_create_produk_table.sql;
SOURCE database/migrations/003_create_pelanggan_table.sql;
SOURCE database/migrations/004_create_penjualan_tables.sql;
SOURCE database/migrations/005_create_pesanan_online_tables.sql;
SOURCE database/migrations/006_create_pengeluaran_table.sql;
