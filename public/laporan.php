<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/reports.php';
require_role(['pemilik', 'kasir']);

$pdo = require __DIR__ . '/../config/database.php';
$filters = report_current_filters();
$summary = report_summary($pdo, $filters);
$periodRows = report_period_rows($pdo, $filters);
$productRows = report_product_rows($pdo, $filters);

if (($_GET['export'] ?? '') === 'excel') {
    $filename = sprintf(
        'laporan-mikastor-%s-%s-sd-%s.xls',
        $filters['period'],
        $filters['date_from'],
        $filters['date_to']
    );

    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Laporan MIKASTOR</title>
    </head>
    <body>
        <h2>Laporan Penjualan MIKASTOR</h2>
        <table>
            <tr>
                <th>Periode</th>
                <td><?= e(report_period_label($filters['period'])) ?></td>
            </tr>
            <tr>
                <th>Rentang Tanggal</th>
                <td><?= e($filters['date_from']) ?> s/d <?= e($filters['date_to']) ?></td>
            </tr>
            <tr>
                <th>Total Nota</th>
                <td><?= e($summary['total_nota']) ?></td>
            </tr>
            <tr>
                <th>Total Item Terjual</th>
                <td><?= e($summary['total_item']) ?></td>
            </tr>
            <tr>
                <th>Total Pendapatan</th>
                <td><?= e((int) $summary['total_pendapatan']) ?></td>
            </tr>
            <tr>
                <th>Rata-rata Nota</th>
                <td><?= e((int) round((float) $summary['rata_rata_nota'])) ?></td>
            </tr>
        </table>

        <h3>Ringkasan <?= e(report_period_label($filters['period'])) ?></h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Total Nota</th>
                    <th>Total Item</th>
                    <th>Total Pendapatan</th>
                    <th>Rata-rata Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($periodRows as $row): ?>
                    <tr>
                        <td><?= e($row['periode_label']) ?></td>
                        <td><?= e($row['total_nota']) ?></td>
                        <td><?= e($row['total_item']) ?></td>
                        <td><?= e((int) $row['total_pendapatan']) ?></td>
                        <td><?= e((int) round((float) $row['rata_rata_nota'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Rekap Produk</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Total Item</th>
                    <th>Total Pendapatan</th>
                    <th>Jumlah Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productRows as $row): ?>
                    <tr>
                        <td><?= e($row['nama_produk']) ?></td>
                        <td><?= e($row['total_item']) ?></td>
                        <td><?= e((int) $row['total_pendapatan']) ?></td>
                        <td><?= e($row['total_nota']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
    exit;
}

render_header('Laporan Penjualan');
render_flash();
?>

<section class="card">
    <div class="section-heading">
        <div>
            <h3>Filter Laporan</h3>
            <p class="hint">Pilih rentang tanggal dan bentuk rekap yang dibutuhkan.</p>
        </div>
        <a class="button secondary report-export-button" href="laporan.php?<?= e(report_query(['export' => 'excel'])) ?>">Export Excel</a>
    </div>

    <form class="table-filters report-filters" method="get" action="laporan.php">
        <div class="filter-title">Parameter Laporan</div>
        <div>
            <label for="period">Tampilkan</label>
            <select id="period" name="period">
                <?php foreach (report_allowed_periods() as $value => $label): ?>
                    <option value="<?= e($value) ?>" <?= $filters['period'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="date_from">Dari Tanggal</label>
            <input type="date" id="date_from" name="date_from" value="<?= e($filters['date_from']) ?>">
        </div>
        <div>
            <label for="date_to">Sampai Tanggal</label>
            <input type="date" id="date_to" name="date_to" value="<?= e($filters['date_to']) ?>">
        </div>
        <div class="filter-actions">
            <button type="submit">Terapkan</button>
            <a class="button secondary" href="laporan.php">Reset</a>
        </div>
    </form>
</section>

<section class="stats">
    <div class="card stat-card">
        <span>Total Nota</span>
        <strong><?= e($summary['total_nota']) ?></strong>
        <small>Transaksi pada rentang aktif</small>
    </div>
    <div class="card stat-card">
        <span>Item Terjual</span>
        <strong><?= e($summary['total_item']) ?></strong>
        <small>Akumulasi seluruh produk</small>
    </div>
    <div class="card stat-card">
        <span>Total Pendapatan</span>
        <strong><?= e(rupiah((int) $summary['total_pendapatan'])) ?></strong>
        <small>Nilai penjualan bersih</small>
    </div>
    <div class="card stat-card">
        <span>Rata-rata Nota</span>
        <strong><?= e(rupiah((int) round((float) $summary['rata_rata_nota']))) ?></strong>
        <small>Rata-rata nilai transaksi</small>
    </div>
</section>

<section class="grid two-columns report-grid">
    <div class="card">
        <div class="section-heading">
            <div>
                <h3>Ringkasan <?= e(report_period_label($filters['period'])) ?></h3>
                <p class="hint"><?= e($filters['date_from']) ?> sampai <?= e($filters['date_to']) ?></p>
            </div>
            <span class="pill"><?= e(count($periodRows)) ?> periode</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Nota</th>
                        <th>Item</th>
                        <th>Pendapatan</th>
                        <th>Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($periodRows as $row): ?>
                        <tr>
                            <td><?= e($row['periode_label']) ?></td>
                            <td><?= e($row['total_nota']) ?></td>
                            <td><?= e($row['total_item']) ?></td>
                            <td><?= e(rupiah((int) $row['total_pendapatan'])) ?></td>
                            <td><?= e(rupiah((int) round((float) $row['rata_rata_nota']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($periodRows === []): ?>
                        <tr>
                            <td colspan="5">Belum ada penjualan pada rentang ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="section-heading">
            <div>
                <h3>Rekap Produk</h3>
                <p class="hint">Produk dengan kontribusi penjualan terbesar.</p>
            </div>
            <span class="pill"><?= e(count($productRows)) ?> produk</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Item</th>
                        <th>Pendapatan</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productRows as $row): ?>
                        <tr>
                            <td><?= e($row['nama_produk']) ?></td>
                            <td><?= e($row['total_item']) ?></td>
                            <td><?= e(rupiah((int) $row['total_pendapatan'])) ?></td>
                            <td><?= e($row['total_nota']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($productRows === []): ?>
                        <tr>
                            <td colspan="4">Belum ada produk terjual pada rentang ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php
render_footer();
