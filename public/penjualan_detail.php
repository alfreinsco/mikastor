<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_role(['pemilik', 'kasir']);

$pdo = require __DIR__ . '/../config/database.php';
$notaId = filter_input(INPUT_GET, 'nota', FILTER_VALIDATE_INT);

if ($notaId === false || $notaId === null) {
    http_response_code(404);
    exit('Nota tidak ditemukan.');
}

$stmt = $pdo->prepare(
    'SELECT p.id_penjualan, p.tanggal_transaksi, p.total_harga, pl.nama_pelanggan, pl.nomor_telepon
     FROM penjualan p
     JOIN pelanggan pl ON pl.id_pelanggan = p.id_pelanggan
     WHERE p.id_penjualan = ?'
);
$stmt->execute([$notaId]);
$nota = $stmt->fetch();

if ($nota === false) {
    http_response_code(404);
    exit('Nota tidak ditemukan.');
}

$stmt = $pdo->prepare(
    'SELECT dp.jumlah, dp.subtotal, pr.nama_produk, pr.harga
     FROM detail_penjualan dp
     JOIN produk pr ON pr.id_produk = dp.id_produk
     WHERE dp.id_penjualan = ?
     ORDER BY dp.id_detail ASC'
);
$stmt->execute([$notaId]);
$notaDetails = $stmt->fetchAll();

render_header('Detail Nota');
render_flash();
?>

<section class="card receipt">
    <div class="section-heading">
        <h3>Nota #<?= e($nota['id_penjualan']) ?></h3>
        <a href="penjualan.php">Kembali ke daftar</a>
    </div>
    <p>
        <strong>Tanggal:</strong> <?= e($nota['tanggal_transaksi']) ?><br>
        <strong>Pelanggan:</strong> <?= e($nota['nama_pelanggan']) ?>
        (<?= e($nota['nomor_telepon']) ?>)
    </p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notaDetails as $detail): ?>
                    <tr>
                        <td><?= e($detail['nama_produk']) ?></td>
                        <td><?= e(rupiah((int) $detail['harga'])) ?></td>
                        <td><?= e($detail['jumlah']) ?></td>
                        <td><?= e(rupiah((int) $detail['subtotal'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th><?= e(rupiah((int) $nota['total_harga'])) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

<?php
render_footer();
