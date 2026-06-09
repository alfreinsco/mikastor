<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/orders.php';
require_role(['pelanggan']);

$pdo = require __DIR__ . '/../config/database.php';
$user = current_user();
$idPesanan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedId = filter_input(INPUT_POST, 'id_pesanan', FILTER_VALIDATE_INT);

    if ($postedId !== false && $postedId !== null) {
        $stmt = $pdo->prepare(
            "UPDATE pesanan_online
             SET status_pengiriman = 'selesai', selesai_pada = NOW()
             WHERE id_pesanan = ? AND id_pelanggan = ? AND status_pengiriman = 'dikirim'"
        );
        $stmt->execute([$postedId, (int) $user['id_pelanggan']]);

        set_flash($stmt->rowCount() > 0 ? 'Pesanan selesai. Terima kasih sudah belanja.' : 'Pesanan belum bisa diselesaikan.');
    }

    header('Location: pesanan_saya_detail.php?id=' . (int) $postedId);
    exit;
}

if ($idPesanan === false || $idPesanan === null) {
    http_response_code(404);
    exit('Pesanan tidak ditemukan.');
}

$stmt = $pdo->prepare(
    'SELECT * FROM pesanan_online WHERE id_pesanan = ? AND id_pelanggan = ?'
);
$stmt->execute([$idPesanan, (int) $user['id_pelanggan']]);
$order = $stmt->fetch();

if ($order === false) {
    http_response_code(404);
    exit('Pesanan tidak ditemukan.');
}

$stmt = $pdo->prepare(
    'SELECT d.*, p.nama_produk
     FROM detail_pesanan_online d
     JOIN produk p ON p.id_produk = d.id_produk
     WHERE d.id_pesanan = ?
     ORDER BY d.id_detail_pesanan ASC'
);
$stmt->execute([$idPesanan]);
$items = $stmt->fetchAll();

render_header('Detail Pesanan Saya');
render_flash();
?>

<section class="card receipt">
    <div class="section-heading">
        <h3>Pesanan #<?= e($order['id_pesanan']) ?></h3>
        <a href="pesanan_saya.php">Kembali ke daftar</a>
    </div>

    <div class="order-meta">
        <p><strong>Penerima:</strong> <?= e($order['nama_penerima']) ?>, <?= e($order['nomor_telepon']) ?></p>
        <p><strong>Alamat:</strong> <?= e($order['alamat_pengiriman']) ?></p>
        <p><strong>Pembayaran:</strong> <?= e(str_replace('_', ' ', $order['status_pembayaran'])) ?></p>
        <p><strong>Pengiriman:</strong> <?= e(str_replace('_', ' ', $order['status_pengiriman'])) ?></p>
        <?php if ($order['bukti_transfer'] !== null): ?>
            <p><strong>Bukti Transfer:</strong> <a href="<?= e($order['bukti_transfer']) ?>" target="_blank" rel="noopener">Lihat bukti</a></p>
        <?php endif; ?>
    </div>

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
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['nama_produk']) ?></td>
                        <td><?= e(rupiah((int) $item['harga'])) ?></td>
                        <td><?= e($item['jumlah']) ?></td>
                        <td><?= e(rupiah((int) $item['subtotal'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th><?= e(rupiah((int) $order['total_harga'])) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="action-row">
        <?php if ($order['status_pembayaran'] === 'menunggu_pembayaran'): ?>
            <a class="button" href="bayar.php?id=<?= e($order['id_pesanan']) ?>">Bayar Sekarang</a>
        <?php elseif ($order['status_pembayaran'] === 'menunggu_konfirmasi'): ?>
            <span class="pill">Menunggu admin</span>
        <?php endif; ?>

        <?php if ($order['status_pengiriman'] === 'dikirim'): ?>
            <form method="post">
                <input type="hidden" name="id_pesanan" value="<?= e($order['id_pesanan']) ?>">
                <button type="submit">Barang Sudah Diterima</button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php
render_footer();
