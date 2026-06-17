CREATE DATABASE IF NOT EXISTS mikastor
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE mikastor;

CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('pemilik', 'kasir', 'pelanggan') NOT NULL,
    id_pelanggan INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL UNIQUE,
    harga INT NOT NULL,
    stok INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_produk_harga CHECK (harga >= 0),
    CONSTRAINT chk_produk_stok CHECK (stok >= 0)
);

CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    nomor_telepon VARCHAR(25) NOT NULL DEFAULT '-',
    alamat TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users
    MODIFY role ENUM('pemilik', 'kasir', 'pelanggan') NOT NULL;

SET @schema_name = DATABASE();
SET @add_user_customer_column = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE users ADD COLUMN id_pelanggan INT NULL',
        'SELECT 1'
    )
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @schema_name
        AND TABLE_NAME = 'users'
        AND COLUMN_NAME = 'id_pelanggan'
);
PREPARE add_user_customer_column_stmt FROM @add_user_customer_column;
EXECUTE add_user_customer_column_stmt;
DEALLOCATE PREPARE add_user_customer_column_stmt;

SET @add_customer_address_column = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE pelanggan ADD COLUMN alamat TEXT NULL',
        'SELECT 1'
    )
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @schema_name
        AND TABLE_NAME = 'pelanggan'
        AND COLUMN_NAME = 'alamat'
);
PREPARE add_customer_address_column_stmt FROM @add_customer_address_column;
EXECUTE add_customer_address_column_stmt;
DEALLOCATE PREPARE add_customer_address_column_stmt;

ALTER TABLE pelanggan
    MODIFY nomor_telepon VARCHAR(25) NOT NULL DEFAULT '-';

CREATE TABLE IF NOT EXISTS penjualan (
    id_penjualan INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    tanggal_transaksi DATETIME NOT NULL,
    total_harga INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_penjualan_pelanggan
        FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    CONSTRAINT chk_penjualan_total CHECK (total_harga >= 0)
);

CREATE TABLE IF NOT EXISTS detail_penjualan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_penjualan INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal INT NOT NULL,
    CONSTRAINT fk_detail_penjualan
        FOREIGN KEY (id_penjualan) REFERENCES penjualan(id_penjualan)
        ON DELETE CASCADE,
    CONSTRAINT fk_detail_produk
        FOREIGN KEY (id_produk) REFERENCES produk(id_produk),
    CONSTRAINT chk_detail_jumlah CHECK (jumlah > 0),
    CONSTRAINT chk_detail_subtotal CHECK (subtotal >= 0)
);

CREATE TABLE IF NOT EXISTS pesanan_online (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    id_penjualan INT NULL,
    tanggal_pesanan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    nama_penerima VARCHAR(100) NOT NULL,
    nomor_telepon VARCHAR(25) NOT NULL,
    alamat_pengiriman TEXT NOT NULL,
    metode_pembayaran ENUM('transfer_bank', 'cod') NOT NULL DEFAULT 'transfer_bank',
    status_pembayaran ENUM('menunggu_pembayaran', 'menunggu_konfirmasi', 'lunas') NOT NULL DEFAULT 'menunggu_pembayaran',
    status_pengiriman ENUM('menunggu_pembayaran', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'menunggu_pembayaran',
    total_harga INT NOT NULL,
    bukti_transfer VARCHAR(255) NULL,
    catatan TEXT NULL,
    dibayar_pada DATETIME NULL,
    dikirim_pada DATETIME NULL,
    selesai_pada DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pesanan_online_pelanggan
        FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    CONSTRAINT fk_pesanan_online_penjualan
        FOREIGN KEY (id_penjualan) REFERENCES penjualan(id_penjualan)
        ON DELETE SET NULL,
    CONSTRAINT chk_pesanan_online_total CHECK (total_harga >= 0)
);

ALTER TABLE pesanan_online
    MODIFY nomor_telepon VARCHAR(25) NOT NULL;

ALTER TABLE pesanan_online
    MODIFY status_pembayaran ENUM('menunggu_pembayaran', 'menunggu_konfirmasi', 'lunas') NOT NULL DEFAULT 'menunggu_pembayaran';

SET @add_payment_proof_column = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE pesanan_online ADD COLUMN bukti_transfer VARCHAR(255) NULL AFTER total_harga',
        'SELECT 1'
    )
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @schema_name
        AND TABLE_NAME = 'pesanan_online'
        AND COLUMN_NAME = 'bukti_transfer'
);
PREPARE add_payment_proof_column_stmt FROM @add_payment_proof_column;
EXECUTE add_payment_proof_column_stmt;
DEALLOCATE PREPARE add_payment_proof_column_stmt;

CREATE TABLE IF NOT EXISTS detail_pesanan_online (
    id_detail_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_produk INT NOT NULL,
    harga INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal INT NOT NULL,
    CONSTRAINT fk_detail_pesanan_online
        FOREIGN KEY (id_pesanan) REFERENCES pesanan_online(id_pesanan)
        ON DELETE CASCADE,
    CONSTRAINT fk_detail_pesanan_produk
        FOREIGN KEY (id_produk) REFERENCES produk(id_produk),
    CONSTRAINT chk_detail_pesanan_harga CHECK (harga >= 0),
    CONSTRAINT chk_detail_pesanan_jumlah CHECK (jumlah > 0),
    CONSTRAINT chk_detail_pesanan_subtotal CHECK (subtotal >= 0)
);

CREATE TABLE IF NOT EXISTS pengeluaran (
    id_pengeluaran INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_pengeluaran DATE NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    keterangan TEXT NULL,
    jumlah INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_pengeluaran_jumlah CHECK (jumlah > 0)
);

INSERT INTO users (nama, username, password, role)
VALUES
    ('Pemilik Toko', 'admin', '$2y$12$ZgHEvOBUxx1DE/VtZZbkwexD02aCe/QVmq7ZtmbCIix1lNEZ.IDGa', 'pemilik'),
    ('Kasir Toko', 'kasir', '$2y$12$.FGSXgS5XBDhkDFGotWJbespJfXMaLWRgjrWKSQGQtl8NWBcNJBUO', 'kasir')
ON DUPLICATE KEY UPDATE
    nama = VALUES(nama),
    role = VALUES(role);

INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, nomor_telepon)
VALUES (1, 'Pelanggan Umum', '-')
ON DUPLICATE KEY UPDATE
    nama_pelanggan = VALUES(nama_pelanggan),
    nomor_telepon = VALUES(nomor_telepon);

INSERT INTO produk (nama_produk, harga, stok)
VALUES
    ('Minyak Kayu Putih 60ml', 15000, 50),
    ('Minyak Kayu Putih 120ml', 28000, 30),
    ('Minyak Kayu Putih 210ml', 50000, 20)
ON DUPLICATE KEY UPDATE
    harga = VALUES(harga),
    stok = VALUES(stok);
