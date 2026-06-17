INSERT INTO produk (nama_produk, harga, stok)
VALUES
    ('Minyak Kayu Putih 60ml', 13500, 1088),
    ('Minyak Kayu Putih 120ml', 27000, 594),
    ('Minyak Kayu Putih 210ml', 47250, 339),
    ('Minyak Kayu Putih 1Kg', 225000, 3133)
ON DUPLICATE KEY UPDATE
    harga = VALUES(harga),
    stok = VALUES(stok);
