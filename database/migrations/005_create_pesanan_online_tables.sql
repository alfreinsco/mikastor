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

SET @schema_name = DATABASE();
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
