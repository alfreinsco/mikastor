<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/orders.php';
require_once __DIR__ . '/../includes/table.php';
require_role(['pemilik', 'kasir']);

$pdo = require __DIR__ . '/../config/database.php';
$q = table_string('q');
$paymentStatus = table_string('status_pembayaran');
$shippingStatus = table_string('status_pengiriman');
$method = table_string('metode');
$page = table_int('page');
$perPage = table_page_size();
$offset = ($page - 1) * $perPage;
$where = [];
$params = [];

if ($q !== '') {
    $where[] = '(po.id_pesanan = ? OR pl.nama_pelanggan LIKE ? OR po.nama_penerima LIKE ?)';
    $params[] = ctype_digit($q) ? (int) $q : 0;
    $params[] = '%' . $q . '%';
    $params[] = '%' . $q . '%';
}

if (in_array($paymentStatus, ['menunggu_pembayaran', 'menunggu_konfirmasi', 'lunas'], true)) {
    $where[] = 'po.status_pembayaran = ?';
    $params[] = $paymentStatus;
}

if (in_array($shippingStatus, ['menunggu_pembayaran', 'dikemas', 'dikirim', 'selesai'], true)) {
    $where[] = 'po.status_pengiriman = ?';
    $params[] = $shippingStatus;
}

if (in_array($method, ['transfer_bank', 'cod'], true)) {
    $where[] = 'po.metode_pembayaran = ?';
    $params[] = $method;
}

$whereSql = $where === [] ? '' : 'WHERE ' . implode(' AND ', $where);
$countStmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM pesanan_online po
     JOIN pelanggan pl ON pl.id_pelanggan = po.id_pelanggan
     {$whereSql}"
);
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$stmt = $pdo->prepare(
    "SELECT po.*, pl.nama_pelanggan
     FROM pesanan_online po
     JOIN pelanggan pl ON pl.id_pelanggan = po.id_pelanggan
     {$whereSql}
     ORDER BY po.tanggal_pesanan DESC, po.id_pesanan DESC
     LIMIT {$perPage} OFFSET {$offset}"
);
$stmt->execute($params);
$orders = $stmt->fetchAll();

render_header('Pesanan Online');
render_flash();
?>

<section class="card">
    <div class="section-heading">
        <h3>Daftar Pesanan Online</h3>
        <span class="pill"><?= e($totalRows) ?> pesanan</span>
    </div>

    <?php render_table_filters_open('pesanan_online.php'); ?>
        <div>
            <label for="q">Cari Pesanan</label>
            <input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="No pesanan, pelanggan, penerima">
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
        <div>
            <label for="metode">Metode</label>
            <select id="metode" name="metode">
                <option value="">Semua</option>
                <option value="transfer_bank" <?= $method === 'transfer_bank' ? 'selected' : '' ?>>Transfer Bank</option>
                <option value="cod" <?= $method === 'cod' ? 'selected' : '' ?>>COD</option>
            </select>
        </div>
        <?php render_page_size_select($perPage); ?>
    <?php render_table_filters_close(); ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No Pesanan</th>
                    <th>Pelanggan</th>
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
                        <td><?= e($order['nama_pelanggan']) ?></td>
                        <td><?= e(rupiah((int) $order['total_harga'])) ?></td>
                        <td>
                            <span class="status-badge <?= e(status_badge_class($order['status_pembayaran'])) ?>">
                                <?= e(str_replace('_', ' ', $order['status_pembayaran'])) ?>
                            </span>
                            <?php if ($order['bukti_transfer'] !== null && $order['status_pembayaran'] !== 'lunas'): ?>
                                <br><small><a href="<?= e($order['bukti_transfer']) ?>" target="_blank" rel="noopener">Bukti masuk</a></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?= e(status_badge_class($order['status_pengiriman'])) ?>">
                                <?= e(str_replace('_', ' ', $order['status_pengiriman'])) ?>
                            </span>
                        </td>
                        <td><a href="pesanan_online_detail.php?id=<?= e($order['id_pesanan']) ?>">Detail</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($orders === []): ?>
                    <tr>
                        <td colspan="6">Belum ada pesanan online.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php render_pagination($totalRows, $page, $perPage); ?>
</section>

<?php
render_footer();
