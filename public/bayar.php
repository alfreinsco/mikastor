<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/orders.php';
require_role(['pelanggan']);

$pdo = require __DIR__ . '/../config/database.php';
$user = current_user();
$idPesanan = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($idPesanan === false || $idPesanan === null) {
    http_response_code(404);
    exit('Pesanan tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'SELECT * FROM pesanan_online WHERE id_pesanan = ? AND id_pelanggan = ? FOR UPDATE'
        );
        $stmt->execute([$idPesanan, (int) $user['id_pelanggan']]);
        $lockedOrder = $stmt->fetch();

        if ($lockedOrder === false) {
            throw new RuntimeException('Pesanan tidak ditemukan.');
        }

        if ($lockedOrder['status_pembayaran'] === 'lunas') {
            throw new RuntimeException('Pesanan sudah lunas.');
        }

        if ($lockedOrder['metode_pembayaran'] === 'cod') {
            mark_order_paid($pdo, $idPesanan);
            $flash = 'Pembayaran COD berhasil dikonfirmasi. Pesanan masuk proses pengemasan.';
        } else {
            if (!isset($_FILES['bukti_transfer']) || $_FILES['bukti_transfer']['error'] !== UPLOAD_ERR_OK) {
                throw new RuntimeException('Upload bukti transfer wajib diisi.');
            }

            if ((int) $_FILES['bukti_transfer']['size'] > 2 * 1024 * 1024) {
                throw new RuntimeException('Ukuran bukti transfer maksimal 2 MB.');
            }

            $tmpPath = (string) $_FILES['bukti_transfer']['tmp_name'];
            $originalName = (string) $_FILES['bukti_transfer']['name'];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

            if (!in_array($extension, $allowedExtensions, true)) {
                throw new RuntimeException('Bukti transfer harus berupa JPG, PNG, WEBP, atau PDF.');
            }

            $uploadDir = __DIR__ . '/uploads/bukti-transfer';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                throw new RuntimeException('Folder upload bukti transfer tidak bisa dibuat.');
            }

            $fileName = 'pesanan-' . $idPesanan . '-' . bin2hex(random_bytes(8)) . '.' . $extension;
            $targetPath = $uploadDir . '/' . $fileName;

            if (!move_uploaded_file($tmpPath, $targetPath)) {
                throw new RuntimeException('Bukti transfer gagal disimpan.');
            }

            $proofPath = 'uploads/bukti-transfer/' . $fileName;
            $stmt = $pdo->prepare(
                "UPDATE pesanan_online
                 SET bukti_transfer = ?,
                     status_pembayaran = 'menunggu_konfirmasi'
                 WHERE id_pesanan = ?"
            );
            $stmt->execute([$proofPath, $idPesanan]);
            $flash = 'Bukti transfer berhasil diupload. Pembayaran menunggu konfirmasi admin.';
        }

        $pdo->commit();

        set_flash($flash);
        header('Location: pesanan_saya_detail.php?id=' . $idPesanan);
        exit;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        set_flash($exception->getMessage());
        header('Location: bayar.php?id=' . $idPesanan);
        exit;
    }
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
$details = $stmt->fetchAll();

render_header('Pembayaran Pesanan');
render_flash();
?>

<section class="grid two-columns">
    <div class="card receipt">
        <div class="section-heading">
            <h3>Pesanan #<?= e($order['id_pesanan']) ?></h3>
            <span class="status-badge <?= e(status_badge_class($order['status_pembayaran'])) ?>">
                <?= e(str_replace('_', ' ', $order['status_pembayaran'])) ?>
            </span>
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
                    <?php foreach ($details as $detail): ?>
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
                        <th colspan="3">Total Bayar</th>
                        <th><?= e(rupiah((int) $order['total_harga'])) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card payment-card">
        <div class="section-heading">
            <h3>Instruksi Pembayaran</h3>
            <span class="pill"><?= e($order['metode_pembayaran'] === 'cod' ? 'COD' : 'Transfer') ?></span>
        </div>

        <?php if ($order['status_pembayaran'] === 'lunas'): ?>
            <div class="alert">Pembayaran sudah lunas. Pesanan sedang diproses toko.</div>
            <a class="button" href="pesanan_saya_detail.php?id=<?= e($order['id_pesanan']) ?>">Lihat Status Pesanan</a>
        <?php else: ?>
            <?php if ($order['metode_pembayaran'] === 'cod'): ?>
                <p class="hint">Konfirmasi COD akan langsung mencatat pembayaran sebagai lunas.</p>
            <?php else: ?>
                <div class="bank-box">
                    <span>Transfer ke</span>
                    <strong>Bank MIKASTOR 1234567890</strong>
                    <small>a.n. Toko Minyak Kayu Putih MIKASTOR</small>
                </div>
                <p class="hint">Upload bukti transfer. Admin akan mengecek dan mengubah status menjadi lunas.</p>
                <?php if ($order['bukti_transfer'] !== null): ?>
                    <div class="alert">Bukti transfer sudah diupload dan sedang menunggu konfirmasi admin.</div>
                    <p><a href="<?= e($order['bukti_transfer']) ?>" target="_blank" rel="noopener">Lihat bukti transfer</a></p>
                <?php endif; ?>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <?php if ($order['metode_pembayaran'] === 'transfer_bank'): ?>
                    <label for="bukti_transfer">Bukti Transfer</label>
                    <input type="file" id="bukti_transfer" name="bukti_transfer" accept=".jpg,.jpeg,.png,.webp,.pdf" required>
                    <p class="hint">Format JPG, PNG, WEBP, atau PDF. Maksimal 2 MB.</p>
                <?php endif; ?>
                <button type="submit"><?= $order['metode_pembayaran'] === 'cod' ? 'Konfirmasi COD' : 'Upload Bukti Transfer' ?></button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php
render_footer();
