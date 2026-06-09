<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/orders.php';
require_once __DIR__ . '/../includes/table.php';
require_role(['pelanggan']);

$pdo = require __DIR__ . '/../config/database.php';
$user = current_user();
$q = table_string('q');
$paymentStatus = table_string('status_pembayaran');
$shippingStatus = table_string('status_pengiriman');
$page = table_int('page');
$perPage = table_page_size();
$offset = ($page - 1) * $perPage;
$where = ['id_pelanggan = ?'];
$params = [(int) $user['id_pelanggan']];

if ($q !== '') {
    $where[] = '(id_pesanan = ? OR nama_penerima LIKE ?)';
    $params[] = ctype_digit($q) ? (int) $q : 0;
    $params[] = '%' . $q . '%';
}

if (in_array($paymentStatus, ['menunggu_pembayaran', 'menunggu_konfirmasi', 'lunas'], true)) {
    $where[] = 'status_pembayaran = ?';
    $params[] = $paymentStatus;
}

if (in_array($shippingStatus, ['menunggu_pembayaran', 'dikemas', 'dikirim', 'selesai'], true)) {
    $where[] = 'status_pengiriman = ?';
    $params[] = $shippingStatus;
}

$whereSql = 'WHERE ' . implode(' AND ', $where);
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM pesanan_online {$whereSql}");
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$stmt = $pdo->prepare(
    "SELECT *
     FROM pesanan_online
     {$whereSql}
     ORDER BY tanggal_pesanan DESC, id_pesanan DESC
     LIMIT {$perPage} OFFSET {$offset}"
);
$stmt->execute($params);
$orders = $stmt->fetchAll();

render_header('Pesanan Saya');
render_flash();
?>

<section class="card">
    <div class="section-heading">
        <h3>Riwayat Pesanan</h3>
        <a href="toko.php">Belanja lagi</a>
    </div>

    <?php render_table_filters_open('pesanan_saya.php'); ?>
        <div>
            <label for="q">Cari Pesanan</label>
            <input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="No pesanan atau penerima">
        </div>
        <div>
            <label for="status_pembayaran">Pembayaran</label>
            <select id="status_pembayaran" name="status_pembayaran">
                <option value="">Semua</option>
                <option value="menunggu_pembayaran" <?= $paymentStatus === 'menunggu_pembayaran' ? 'selected' : '' ?>>Menunggu pembayaran</option>
                <option value="menunggu_konfirmasi" <?= $paymentStatus === 'menunggu_konfirmasi' ? 'selected' : '' ?>>Menunggu konfirmasi</option>
                <option value="lunas" <?= $paymentStatus === 'lunas' ? 'selected' : '' ?>>Lunas</option>
            </select>
        </div>
        <div>
            <label for="status_pengiriman">Pengiriman</label>
            <select id="status_pengiriman" name="status_pengiriman">
                <option value="">Semua</option>
                <option value="menunggu_pembayaran" <?= $shippingStatus === 'menunggu_pembayaran' ? 'selected' : '' ?>>Menunggu pembayaran</option>
                <option value="dikemas" <?= $shippingStatus === 'dikemas' ? 'selected' : '' ?>>Dikemas</option>
                <option value="dikirim" <?= $shippingStatus === 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                <option value="selesai" <?= $shippingStatus === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>
        <?php render_page_size_select($perPage); ?>
    <?php render_table_filters_close(); ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No Pesanan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Pengiriman</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= e($order['id_pesanan']) ?></td>
                        <td><?= e($order['tanggal_pesanan']) ?></td>
                        <td><?= e(rupiah((int) $order['total_harga'])) ?></td>
                        <td>
                            <span class="status-badge <?= e(status_badge_class($order['status_pembayaran'])) ?>">
                                <?= e(str_replace('_', ' ', $order['status_pembayaran'])) ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge <?= e(status_badge_class($order['status_pengiriman'])) ?>">
                                <?= e(str_replace('_', ' ', $order['status_pengiriman'])) ?>
                            </span>
                        </td>
                        <td><a href="pesanan_saya_detail.php?id=<?= e($order['id_pesanan']) ?>">Detail</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($orders === []): ?>
                    <tr>
                        <td colspan="6">Belum ada pesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php render_pagination($totalRows, $page, $perPage); ?>
</section>

<?php
render_footer();
