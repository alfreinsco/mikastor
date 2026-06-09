<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/table.php';
require_role(['pemilik', 'kasir']);

$pdo = require __DIR__ . '/../config/database.php';
$q = table_string('q');
$dateFrom = table_string('date_from');
$dateTo = table_string('date_to');
$page = table_int('page');
$perPage = table_page_size();
$offset = ($page - 1) * $perPage;
$where = [];
$params = [];

if ($q !== '') {
    $where[] = '(pl.nama_pelanggan LIKE ? OR p.id_penjualan = ?)';
    $params[] = '%' . $q . '%';
    $params[] = ctype_digit($q) ? (int) $q : 0;
}

if ($dateFrom !== '') {
    $where[] = 'DATE(p.tanggal_transaksi) >= ?';
    $params[] = $dateFrom;
}

if ($dateTo !== '') {
    $where[] = 'DATE(p.tanggal_transaksi) <= ?';
    $params[] = $dateTo;
}

$whereSql = $where === [] ? '' : 'WHERE ' . implode(' AND ', $where);

$summary = $pdo
    ->query('SELECT COUNT(*) AS total_nota, COALESCE(SUM(total_harga), 0) AS total_pendapatan FROM penjualan')
    ->fetch();

$countStmt = $pdo->prepare(
    "SELECT COUNT(*)
     FROM penjualan p
     JOIN pelanggan pl ON pl.id_pelanggan = p.id_pelanggan
     {$whereSql}"
);
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$stmt = $pdo->prepare(
    "SELECT p.id_penjualan, p.tanggal_transaksi, p.total_harga, pl.nama_pelanggan
     FROM penjualan p
     JOIN pelanggan pl ON pl.id_pelanggan = p.id_pelanggan
     {$whereSql}
     ORDER BY p.tanggal_transaksi DESC, p.id_penjualan DESC
     LIMIT {$perPage} OFFSET {$offset}"
);
$stmt->execute($params);
$sales = $stmt->fetchAll();

render_header('Riwayat Penjualan');
render_flash();
?>

<section class="stats">
    <div class="card stat-card">
        <span>Total Nota</span>
        <strong><?= e($summary['total_nota']) ?></strong>
        <small>Transaksi tersimpan</small>
    </div>
    <div class="card stat-card">
        <span>Total Pendapatan</span>
        <strong><?= e(rupiah((int) $summary['total_pendapatan'])) ?></strong>
        <small>Nilai seluruh penjualan</small>
    </div>
</section>

<section class="card">
    <div class="section-heading">
        <h3>Daftar Nota</h3>
        <span class="pill">Riwayat toko</span>
    </div>

    <?php render_table_filters_open('penjualan.php'); ?>
        <div>
            <label for="q">Cari Nota/Pelanggan</label>
            <input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="No nota atau nama pelanggan">
        </div>
        <div>
            <label for="date_from">Dari Tanggal</label>
            <input type="date" id="date_from" name="date_from" value="<?= e($dateFrom) ?>">
        </div>
        <div>
            <label for="date_to">Sampai Tanggal</label>
            <input type="date" id="date_to" name="date_to" value="<?= e($dateTo) ?>">
        </div>
        <?php render_page_size_select($perPage); ?>
    <?php render_table_filters_close(); ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No Nota</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td>#<?= e($sale['id_penjualan']) ?></td>
                        <td><?= e($sale['tanggal_transaksi']) ?></td>
                        <td><?= e($sale['nama_pelanggan']) ?></td>
                        <td><?= e(rupiah((int) $sale['total_harga'])) ?></td>
                        <td><a href="penjualan_detail.php?nota=<?= e($sale['id_penjualan']) ?>">Detail</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($sales === []): ?>
                    <tr>
                        <td colspan="5">Belum ada transaksi penjualan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php render_pagination($totalRows, $page, $perPage); ?>
</section>

<?php
render_footer();
