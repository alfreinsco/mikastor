CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    nomor_telepon VARCHAR(25) NOT NULL DEFAULT '-',
    alamat TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

SET @schema_name = DATABASE();
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
