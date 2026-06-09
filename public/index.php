<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

if (!is_logged_in()) {
    header('Location: home.php');
    exit;
}

$pdo = require __DIR__ . '/../config/database.php';

$productSummary = $pdo
    ->query('SELECT COUNT(*) AS total_produk, COALESCE(SUM(stok), 0) AS total_stok FROM produk')
    ->fetch();

$salesSummary = $pdo
    ->query(
        'SELECT
            COALESCE(SUM(total_harga), 0) AS total_pendapatan,
            COALESCE(SUM(CASE WHEN DATE(tanggal_transaksi) = CURDATE() THEN total_harga ELSE 0 END), 0) AS pendapatan_hari_ini
         FROM penjualan'
    )
    ->fetch();

$lowStockProducts = $pdo
    ->query('SELECT nama_produk, stok FROM produk WHERE stok <= 5 ORDER BY stok ASC, nama_produk ASC')
    ->fetchAll();

$recentSales = $pdo
    ->query(
        'SELECT p.id_penjualan, p.tanggal_transaksi, p.total_harga, pl.nama_pelanggan
         FROM penjualan p
         JOIN pelanggan pl ON pl.id_pelanggan = p.id_pelanggan
         ORDER BY p.tanggal_transaksi DESC, p.id_penjualan DESC
         LIMIT 5'
    )
    ->fetchAll();

render_header('Dashboard');
render_flash();
?>

<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">Natural wellness retail</span>
        <h3>Jual minyak kayu putih dengan tampilan toko yang lebih percaya diri.</h3>
        <p>
            Pantau stok, layani pembelian, dan lihat performa penjualan dalam satu alur kerja
            yang cepat untuk kasir dan pemilik toko.
        </p>
        <div class="hero-actions">
            <a class="button" href="kasir.php">Mulai Transaksi</a>
            <?php if (is_owner()): ?>
                <a class="button secondary" href="produk.php">Kelola Produk</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-visual" aria-label="Minyak kayu putih MIKASTOR"></div>
</section>

<section class="stats">
    <div class="card stat-card">
        <span>Katalog Aktif</span>
        <strong><?= e($productSummary['total_produk']) ?></strong>
        <small>Varian minyak tersedia</small>
    </div>
    <div class="card stat-card">
        <span>Stok Siap Jual</span>
        <strong><?= e($productSummary['total_stok']) ?></strong>
        <small>Botol dalam inventori</small>
    </div>
    <div class="card stat-card">
        <span>Pendapatan Hari Ini</span>
        <strong><?= e(rupiah((int) $salesSummary['pendapatan_hari_ini'])) ?></strong>
        <small>Performa kasir hari ini</small>
    </div>
    <div class="card stat-card">
        <span>Total Pendapatan</span>
        <strong><?= e(rupiah((int) $salesSummary['total_pendapatan'])) ?></strong>
        <small>Akumulasi seluruh nota</small>
    </div>
</section>

<section class="grid two-columns">
    <div class="card">
        <div class="section-heading">
            <h3>Stok Menipis</h3>
            <span class="pill">Perlu restock</span>
        </div>
        <?php if ($lowStockProducts === []): ?>
            <p>Belum ada produk dengan stok menipis.</p>
        <?php else: ?>
            <ul class="simple-list">
                <?php foreach ($lowStockProducts as $product): ?>
                    <li>
                        <span><?= e($product['nama_produk']) ?></span>
                        <strong class="stock-warning"><?= e($product['stok']) ?> tersisa</strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="section-heading">
            <h3>Transaksi Terbaru</h3>
            <a href="penjualan.php">Lihat semua</a>
        </div>
        <?php if ($recentSales === []): ?>
            <p>Belum ada transaksi.</p>
        <?php else: ?>
            <ul class="simple-list">
                <?php foreach ($recentSales as $sale): ?>
                    <li>
                        <span>
                            <a href="penjualan_detail.php?nota=<?= e($sale['id_penjualan']) ?>">
                                Nota #<?= e($sale['id_penjualan']) ?>
                            </a>
                            - <?= e($sale['nama_pelanggan']) ?>
                        </span>
                        <strong><?= e(rupiah((int) $sale['total_harga'])) ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>

<?php
render_footer();
