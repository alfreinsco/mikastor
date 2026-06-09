<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/table.php';
require_role(['pemilik']);

$pdo = require __DIR__ . '/../config/database.php';
$q = table_string('q');
$stockFilter = table_string('stok');
$page = table_int('page');
$perPage = table_page_size();
$offset = ($page - 1) * $perPage;
$where = [];
$params = [];

if ($q !== '') {
    $where[] = 'nama_produk LIKE ?';
    $params[] = '%' . $q . '%';
}

if ($stockFilter === 'ready') {
    $where[] = 'stok > 5';
} elseif ($stockFilter === 'low') {
    $where[] = 'stok BETWEEN 1 AND 5';
} elseif ($stockFilter === 'empty') {
    $where[] = 'stok = 0';
}

$whereSql = $where === [] ? '' : 'WHERE ' . implode(' AND ', $where);
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM produk {$whereSql}");
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$stmt = $pdo->prepare(
    "SELECT * FROM produk {$whereSql} ORDER BY nama_produk ASC LIMIT {$perPage} OFFSET {$offset}"
);
$stmt->execute($params);
$products = $stmt->fetchAll();

render_header('Manajemen Produk');
render_flash();
?>

<section class="card">
    <div class="section-heading">
        <h3>Daftar Produk</h3>
        <a class="button" href="produk_form.php">Tambah Produk</a>
    </div>

    <?php render_table_filters_open('produk.php'); ?>
        <div>
            <label for="q">Cari Produk</label>
            <input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="Nama produk">
        </div>
        <div>
            <label for="stok">Filter Stok</label>
            <select id="stok" name="stok">
                <option value="">Semua stok</option>
                <option value="ready" <?= $stockFilter === 'ready' ? 'selected' : '' ?>>Stok aman</option>
                <option value="low" <?= $stockFilter === 'low' ? 'selected' : '' ?>>Stok menipis</option>
                <option value="empty" <?= $stockFilter === 'empty' ? 'selected' : '' ?>>Stok kosong</option>
            </select>
        </div>
        <?php render_page_size_select($perPage); ?>
    <?php render_table_filters_close(); ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <div class="product-cell">
                                <span class="product-icon"></span>
                                <span><?= e($product['nama_produk']) ?></span>
                            </div>
                        </td>
                        <td><?= e(rupiah((int) $product['harga'])) ?></td>
                        <td>
                            <span class="stock-badge <?= (int) $product['stok'] <= 5 ? 'low' : '' ?>">
                                <?= e($product['stok']) ?> botol
                            </span>
                        </td>
                        <td><a href="produk_form.php?id=<?= e($product['id_produk']) ?>">Ubah</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($products === []): ?>
                    <tr>
                        <td colspan="4">Produk tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php render_pagination($totalRows, $page, $perPage); ?>
</section>

<?php
render_footer();
