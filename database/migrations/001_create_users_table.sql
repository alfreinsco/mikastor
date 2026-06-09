CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('pemilik', 'kasir', 'pelanggan') NOT NULL,
    id_pelanggan INT NULL,
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
