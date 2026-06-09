<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/orders.php';
require_role(['pemilik', 'kasir']);

$pdo = require __DIR__ . '/../config/database.php';
$idPesanan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedId = filter_input(INPUT_POST, 'id_pesanan', FILTER_VALIDATE_INT);
    $aksi = (string) ($_POST['aksi'] ?? '');

    if ($postedId !== false && $postedId !== null) {
        try {
            $pdo->beginTransaction();

            if ($aksi === 'lunas') {
                mark_order_paid($pdo, $postedId);
                set_flash('Pesanan berhasil ditandai lunas dan nota penjualan dibuat.');
            } elseif ($aksi === 'kirim') {
                $stmt = $pdo->prepare(
                    "UPDATE pesanan_online
                     SET status_pengiriman = 'dikirim', dikirim_pada = NOW()
                     WHERE id_pesanan = ? AND status_pembayaran = 'lunas' AND status_pengiriman IN ('dikemas', 'menunggu_pembayaran')"
                );
                $stmt->execute([$postedId]);
                set_flash($stmt->rowCount() > 0 ? 'Pesanan ditandai sedang dikirim.' : 'Pesanan belum bisa dikirim.');
            } elseif ($aksi === 'selesai') {
                $stmt = $pdo->prepare(
                    "UPDATE pesanan_online
                     SET status_pengiriman = 'selesai', selesai_pada = NOW()
                     WHERE id_pesanan = ? AND status_pengiriman = 'dikirim'"
                );
                $stmt->execute([$postedId]);
                set_flash($stmt->rowCount() > 0 ? 'Pesanan ditandai selesai.' : 'Pesanan belum bisa diselesaikan.');
            }

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            set_flash($exception->getMessage());
        }
    }

    header('Location: pesanan_online_detail.php?id=' . (int) $postedId);
    exit;
}

if ($idPesanan === false || $idPesanan === null) {
    http_response_code(404);
    exit('Pesanan tidak ditemukan.');
}

$stmt = $pdo->prepare(
    'SELECT po.*, pl.nama_pelanggan
     FROM pesanan_online po
     JOIN pelanggan pl ON pl.id_pelanggan = po.id_pelanggan
     WHERE po.id_pesanan = ?'
);
$stmt->execute([$idPesanan]);
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

render_header('Detail Pesanan Online');
render_flash();
?>

<section class="card receipt">
    <div class="section-heading">
        <h3>Pesanan #<?= e($order['id_pesanan']) ?></h3>
        <a href="pesanan_online.php">Kembali ke daftar</a>
    </div>

    <div class="order-meta">
        <p><strong>Pelanggan:</strong> <?= e($order['nama_pelanggan']) ?></p>
        <p><strong>Penerima:</strong> <?= e($order['nama_penerima']) ?>, <?= e($order['nomor_telepon']) ?></p>
        <p><strong>Alamat:</strong> <?= e($order['alamat_pengiriman']) ?></p>
        <p><strong>Pembayaran:</strong> <?= e(str_replace('_', ' ', $order['status_pembayaran'])) ?></p>
        <p><strong>Pengiriman:</strong> <?= e(str_replace('_', ' ', $order['status_pengiriman'])) ?></p>
        <p><strong>Metode:</strong> <?= e($order['metode_pembayaran'] === 'cod' ? 'COD' : 'Transfer Bank') ?></p>
        <?php if ($order['bukti_transfer'] !== null): ?>
            <p><strong>Bukti Transfer:</strong> <a href="<?= e($order['bukti_transfer']) ?>" target="_blank" rel="noopener">Lihat bukti</a></p>
        <?php elseif ($order['metode_pembayaran'] === 'transfer_bank'): ?>
            <p><strong>Bukti Transfer:</strong> Belum diupload pelanggan.</p>
        <?php endif; ?>
        <?php if ($order['id_penjualan'] !== null): ?>
            <p><strong>Nota:</strong> <a href="penjualan_detail.php?nota=<?= e($order['id_penjualan']) ?>">#<?= e($order['id_penjualan']) ?></a></p>
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
        <?php if (
            $order['status_pembayaran'] !== 'lunas'
            && ($order['metode_pembayaran'] === 'cod' || $order['bukti_transfer'] !== null)
        ): ?>
            <form method="post">
                <input type="hidden" name="id_pesanan" value="<?= e($order['id_pesanan']) ?>">
                <input type="hidden" name="aksi" value="lunas">
                <button type="submit">Konfirmasi Lunas</button>
            </form>
        <?php endif; ?>

        <?php if ($order['status_pembayaran'] === 'lunas' && in_array($order['status_pengiriman'], ['dikemas', 'menunggu_pembayaran'], true)): ?>
            <form method="post">
                <input type="hidden" name="id_pesanan" value="<?= e($order['id_pesanan']) ?>">
                <input type="hidden" name="aksi" value="kirim">
                <button type="submit">Kirim Barang</button>
            </form>
        <?php endif; ?>

        <?php if ($order['status_pengiriman'] === 'dikirim'): ?>
            <form method="post">
                <input type="hidden" name="id_pesanan" value="<?= e($order['id_pesanan']) ?>">
                <input type="hidden" name="aksi" value="selesai">
                <button type="submit">Selesaikan</button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php
render_footer();
