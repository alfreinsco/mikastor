<?php

declare(strict_types=1);

function mark_order_paid(PDO $pdo, int $idPesanan): int
{
    $stmt = $pdo->prepare('SELECT * FROM pesanan_online WHERE id_pesanan = ? FOR UPDATE');
    $stmt->execute([$idPesanan]);
    $order = $stmt->fetch();

    if ($order === false) {
        throw new RuntimeException('Pesanan tidak ditemukan.');
    }

    if ($order['id_penjualan'] !== null) {
        return (int) $order['id_penjualan'];
    }

    $saleStmt = $pdo->prepare(
        'INSERT INTO penjualan (id_pelanggan, tanggal_transaksi, total_harga) VALUES (?, NOW(), ?)'
    );
    $saleStmt->execute([(int) $order['id_pelanggan'], (int) $order['total_harga']]);
    $idPenjualan = (int) $pdo->lastInsertId();

    $detailsStmt = $pdo->prepare(
        'SELECT id_produk, jumlah, subtotal FROM detail_pesanan_online WHERE id_pesanan = ? ORDER BY id_detail_pesanan ASC'
    );
    $detailsStmt->execute([$idPesanan]);
    $details = $detailsStmt->fetchAll();

    $insertDetailStmt = $pdo->prepare(
        'INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, subtotal) VALUES (?, ?, ?, ?)'
    );

    foreach ($details as $detail) {
        $insertDetailStmt->execute([
            $idPenjualan,
            (int) $detail['id_produk'],
            (int) $detail['jumlah'],
            (int) $detail['subtotal'],
        ]);
    }

    $updateStmt = $pdo->prepare(
        "UPDATE pesanan_online
         SET id_penjualan = ?,
             status_pembayaran = 'lunas',
             status_pengiriman = CASE
                 WHEN status_pengiriman = 'menunggu_pembayaran' THEN 'dikemas'
                 ELSE status_pengiriman
             END,
             dibayar_pada = COALESCE(dibayar_pada, NOW())
         WHERE id_pesanan = ?"
    );
    $updateStmt->execute([$idPenjualan, $idPesanan]);

    return $idPenjualan;
}

function status_badge_class(string $status): string
{
    return match ($status) {
        'lunas', 'selesai' => 'success',
        'dikirim', 'menunggu_konfirmasi' => 'info',
        'dikemas' => 'warning',
        default => '',
    };
}
