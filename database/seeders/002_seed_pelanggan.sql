INSERT INTO pelanggan (id_pelanggan, nama_pelanggan, nomor_telepon, alamat)
VALUES (1, 'Pelanggan Umum', '-', NULL)
ON DUPLICATE KEY UPDATE
    nama_pelanggan = VALUES(nama_pelanggan),
    nomor_telepon = VALUES(nomor_telepon);
