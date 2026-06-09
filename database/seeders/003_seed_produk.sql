INSERT INTO produk (nama_produk, harga, stok)
VALUES
    ('Minyak Kayu Putih 60ml', 15000, 50),
    ('Minyak Kayu Putih 120ml', 28000, 30),
    ('Minyak Kayu Putih 210ml', 50000, 20)
ON DUPLICATE KEY UPDATE
    harga = VALUES(harga),
    stok = VALUES(stok);
