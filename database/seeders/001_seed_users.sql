INSERT INTO users (nama, username, password, role)
VALUES
    ('Pemilik Toko', 'admin', '$2y$12$ZgHEvOBUxx1DE/VtZZbkwexD02aCe/QVmq7ZtmbCIix1lNEZ.IDGa', 'pemilik'),
    ('Kasir Toko', 'kasir', '$2y$12$.FGSXgS5XBDhkDFGotWJbespJfXMaLWRgjrWKSQGQtl8NWBcNJBUO', 'kasir')
ON DUPLICATE KEY UPDATE
    nama = VALUES(nama),
    role = VALUES(role);
