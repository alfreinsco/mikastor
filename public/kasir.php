<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/table.php';
require_role(['pemilik', 'kasir']);

$pdo = require __DIR__ . '/../config/database.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlahInput = $_POST['jumlah'] ?? [];
    $items = [];

    if (is_array($jumlahInput)) {
        foreach ($jumlahInput as $idProduk => $jumlah) {
            $idProduk = filter_var($idProduk, FILTER_VALIDATE_INT);
            $jumlah = filter_var($jumlah, FILTER_VALIDATE_INT);

            if ($idProduk === false || $jumlah === false || $jumlah === null || $jumlah <= 0) {
                continue;
            }

            $items[$idProduk] = $jumlah;
        }
    }

    if ($items === []) {
        $errors[] = 'Isi minimal satu jumlah produk yang dibeli.';
    }

    if ($errors === []) {
        try {
            $pdo->beginTransaction();

            $namaPelanggan = trim((string) ($_POST['nama_pelanggan'] ?? ''));
            $nomorTelepon = trim((string) ($_POST['nomor_telepon'] ?? ''));
            $idPelanggan = 1;

            if ($namaPelanggan !== '') {
                $stmt = $pdo->prepare(
                    'INSERT INTO pelanggan (nama_pelanggan, nomor_telepon) VALUES (?, ?)'
                );
                $stmt->execute([$namaPelanggan, $nomorTelepon !== '' ? $nomorTelepon : '-']);
                $idPelanggan = (int) $pdo->lastInsertId();
            }

            $cart = [];
            $totalHarga = 0;

            foreach ($items as $idProduk => $jumlah) {
                $stmt = $pdo->prepare(
                    'SELECT id_produk, nama_produk, harga, stok FROM produk WHERE id_produk = ? FOR UPDATE'
                );
                $stmt->execute([$idProduk]);
                $product = $stmt->fetch();

                if ($product === false) {
                    throw new RuntimeException('Produk tidak ditemukan.');
                }

                if ((int) $product['stok'] < $jumlah) {
                    throw new RuntimeException(
                        'Stok tidak cukup untuk ' . $product['nama_produk'] . '.'
                    );
                }

                $subtotal = (int) $product['harga'] * $jumlah;
                $totalHarga += $subtotal;
                $cart[] = [
                    'id_produk' => (int) $product['id_produk'],
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                ];
            }

            $stmt = $pdo->prepare(
                'INSERT INTO penjualan (id_pelanggan, tanggal_transaksi, total_harga) VALUES (?, NOW(), ?)'
            );
            $stmt->execute([$idPelanggan, $totalHarga]);
            $idPenjualan = (int) $pdo->lastInsertId();

            $detailStmt = $pdo->prepare(
                'INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, subtotal) VALUES (?, ?, ?, ?)'
            );
            $stockStmt = $pdo->prepare(
                'UPDATE produk SET stok = stok - ? WHERE id_produk = ?'
            );

            foreach ($cart as $item) {
                $detailStmt->execute([
                    $idPenjualan,
                    $item['id_produk'],
                    $item['jumlah'],
                    $item['subtotal'],
                ]);
                $stockStmt->execute([$item['jumlah'], $item['id_produk']]);
            }

            $pdo->commit();
            set_flash('Transaksi berhasil disimpan. Stok produk sudah otomatis berkurang.');
            header('Location: penjualan_detail.php?nota=' . $idPenjualan);
            exit;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $errors[] = $exception->getMessage();
        }
    }
}

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

render_header('Kasir');
render_flash();
?>

<?php if ($errors !== []): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p><?= e($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<section class="card">
    <div class="section-heading">
        <h3>Filter Produk Kasir</h3>
        <span class="pill"><?= e($totalRows) ?> produk</span>
    </div>
    <?php render_table_filters_open('kasir.php'); ?>
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
</section>

<form method="post" class="card checkout-card">
    <div class="section-heading">
        <div>
            <h3>Data Pelanggan</h3>
            <p class="hint">Isi nama untuk nota personal, atau kosongkan untuk pelanggan umum.</p>
        </div>
        <span class="pill">Kasir cepat</span>
    </div>
    <div class="grid two-columns">
        <div>
            <label for="nama_pelanggan">Nama Pelanggan</label>
            <input
                type="text"
                id="nama_pelanggan"
                name="nama_pelanggan"
                placeholder="Kosongkan untuk Pelanggan Umum"
                value="<?= e($_POST['nama_pelanggan'] ?? '') ?>"
            >
        </div>
        <div>
            <label for="nomor_telepon">Nomor Telepon</label>
            <input
                type="text"
                id="nomor_telepon"
                name="nomor_telepon"
                placeholder="-"
                value="<?= e($_POST['nomor_telepon'] ?? '') ?>"
            >
        </div>
    </div>

    <div class="section-heading products-heading">
        <h3>Produk Dibeli</h3>
        <span class="pill"><?= e(count($products)) ?> pilihan</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Jumlah Beli</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <?php
                    $idProduk = (int) $product['id_produk'];
                    $postedJumlah = $_POST['jumlah'][$idProduk] ?? 0;
                    ?>
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
                        <td>
                            <input
                                type="number"
                                name="jumlah[<?= e($idProduk) ?>]"
                                min="0"
                                max="<?= e($product['stok']) ?>"
                                value="<?= e($postedJumlah) ?>"
                            >
                        </td>
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

    <button type="submit">Simpan Transaksi</button>
</form>

<?php
render_footer();
