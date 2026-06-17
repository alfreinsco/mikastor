<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/table.php';
require_role(['pemilik', 'kasir']);

$pdo = require __DIR__ . '/../config/database.php';
$errors = [];
$idPengeluaran = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$pengeluaran = null;

if ($idPengeluaran !== false && $idPengeluaran !== null) {
    $stmt = $pdo->prepare('SELECT * FROM pengeluaran WHERE id_pengeluaran = ?');
    $stmt->execute([$idPengeluaran]);
    $pengeluaran = $stmt->fetch() ?: null;

    if ($pengeluaran === null) {
        http_response_code(404);
        exit('Pengeluaran tidak ditemukan.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'save');

    if ($action === 'delete') {
        $postedId = filter_input(INPUT_POST, 'id_pengeluaran', FILTER_VALIDATE_INT);

        if ($postedId === false || $postedId === null) {
            $errors[] = 'Data pengeluaran tidak valid.';
        } else {
            $stmt = $pdo->prepare('DELETE FROM pengeluaran WHERE id_pengeluaran = ?');
            $stmt->execute([$postedId]);
            set_flash('Pengeluaran berhasil dihapus.');
            header('Location: pengeluaran.php');
            exit;
        }
    } else {
        $postedId = filter_input(INPUT_POST, 'id_pengeluaran', FILTER_VALIDATE_INT);
        $tanggalPengeluaran = trim((string) ($_POST['tanggal_pengeluaran'] ?? ''));
        $kategori = trim((string) ($_POST['kategori'] ?? ''));
        $keterangan = trim((string) ($_POST['keterangan'] ?? ''));
        $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $tanggalPengeluaran);

        if ($date === false || $date->format('Y-m-d') !== $tanggalPengeluaran) {
            $errors[] = 'Tanggal pengeluaran wajib diisi dengan format yang valid.';
        }

        if ($kategori === '') {
            $errors[] = 'Kategori wajib diisi.';
        }

        if ($jumlah === false || $jumlah === null || $jumlah <= 0) {
            $errors[] = 'Jumlah pengeluaran harus berupa angka lebih dari 0.';
        }

        if ($errors === []) {
            if ($postedId !== false && $postedId !== null) {
                $stmt = $pdo->prepare(
                    'UPDATE pengeluaran
                     SET tanggal_pengeluaran = ?, kategori = ?, keterangan = ?, jumlah = ?
                     WHERE id_pengeluaran = ?'
                );
                $stmt->execute([
                    $tanggalPengeluaran,
                    $kategori,
                    $keterangan !== '' ? $keterangan : null,
                    $jumlah,
                    $postedId,
                ]);
                set_flash('Pengeluaran berhasil diperbarui.');
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO pengeluaran (tanggal_pengeluaran, kategori, keterangan, jumlah)
                     VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([
                    $tanggalPengeluaran,
                    $kategori,
                    $keterangan !== '' ? $keterangan : null,
                    $jumlah,
                ]);
                set_flash('Pengeluaran berhasil dicatat.');
            }

            header('Location: pengeluaran.php');
            exit;
        }
    }
}

$q = table_string('q');
$categoryFilter = table_string('kategori');
$dateFrom = table_string('date_from');
$dateTo = table_string('date_to');
$page = table_int('page');
$perPage = table_page_size();
$offset = ($page - 1) * $perPage;
$where = [];
$params = [];

if ($q !== '') {
    $where[] = '(kategori LIKE ? OR keterangan LIKE ? OR id_pengeluaran = ?)';
    $params[] = '%' . $q . '%';
    $params[] = '%' . $q . '%';
    $params[] = ctype_digit($q) ? (int) $q : 0;
}

if ($categoryFilter !== '') {
    $where[] = 'kategori = ?';
    $params[] = $categoryFilter;
}

if ($dateFrom !== '') {
    $fromDate = DateTimeImmutable::createFromFormat('Y-m-d', $dateFrom);

    if ($fromDate !== false && $fromDate->format('Y-m-d') === $dateFrom) {
        $where[] = 'tanggal_pengeluaran >= ?';
        $params[] = $dateFrom;
    } else {
        $dateFrom = '';
    }
}

if ($dateTo !== '') {
    $toDate = DateTimeImmutable::createFromFormat('Y-m-d', $dateTo);

    if ($toDate !== false && $toDate->format('Y-m-d') === $dateTo) {
        $where[] = 'tanggal_pengeluaran <= ?';
        $params[] = $dateTo;
    } else {
        $dateTo = '';
    }
}

$whereSql = $where === [] ? '' : 'WHERE ' . implode(' AND ', $where);

$categoryRows = $pdo
    ->query('SELECT DISTINCT kategori FROM pengeluaran ORDER BY kategori ASC')
    ->fetchAll();

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM pengeluaran {$whereSql}");
$countStmt->execute($params);
$totalRows = (int) $countStmt->fetchColumn();

$stmt = $pdo->prepare(
    "SELECT *
     FROM pengeluaran
     {$whereSql}
     ORDER BY tanggal_pengeluaran DESC, id_pengeluaran DESC
     LIMIT {$perPage} OFFSET {$offset}"
);
$stmt->execute($params);
$rows = $stmt->fetchAll();

$totalStmt = $pdo->prepare(
    'SELECT COALESCE(SUM(jumlah), 0) AS total_pengeluaran
     FROM pengeluaran
     ' . $whereSql
);
$totalStmt->execute($params);
$totalPengeluaran = (int) ($totalStmt->fetch()['total_pengeluaran'] ?? 0);

render_header('Pengeluaran');
render_flash();
?>

<section class="card form-page">
    <div class="section-heading">
        <h3><?= $pengeluaran === null ? 'Catat Pengeluaran' : 'Ubah Pengeluaran' ?></h3>
        <?php if ($pengeluaran !== null): ?>
            <a href="pengeluaran.php">Tambah Baru</a>
        <?php endif; ?>
    </div>

    <?php if ($errors !== []): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <?php if ($pengeluaran !== null): ?>
            <input type="hidden" name="id_pengeluaran" value="<?= e($pengeluaran['id_pengeluaran']) ?>">
        <?php endif; ?>

        <label for="tanggal_pengeluaran">Tanggal</label>
        <input
            type="date"
            id="tanggal_pengeluaran"
            name="tanggal_pengeluaran"
            value="<?= e($_POST['tanggal_pengeluaran'] ?? $pengeluaran['tanggal_pengeluaran'] ?? date('Y-m-d')) ?>"
            required
        >

        <label for="kategori">Kategori</label>
        <input
            type="text"
            id="kategori"
            name="kategori"
            value="<?= e($_POST['kategori'] ?? $pengeluaran['kategori'] ?? '') ?>"
            placeholder="Contoh: Operasional, Transport, Belanja bahan"
            required
        >

        <label for="jumlah">Jumlah</label>
        <input
            type="number"
            id="jumlah"
            name="jumlah"
            min="1"
            value="<?= e($_POST['jumlah'] ?? $pengeluaran['jumlah'] ?? '') ?>"
            required
        >

        <label for="keterangan">Keterangan</label>
        <textarea
            id="keterangan"
            name="keterangan"
            rows="3"
            placeholder="Catatan tambahan opsional"
        ><?= e($_POST['keterangan'] ?? $pengeluaran['keterangan'] ?? '') ?></textarea>

        <button type="submit"><?= $pengeluaran === null ? 'Simpan Pengeluaran' : 'Update Pengeluaran' ?></button>
        <?php if ($pengeluaran !== null): ?>
            <a class="button secondary" href="pengeluaran.php">Batal</a>
        <?php endif; ?>
    </form>
</section>

<section class="card">
    <div class="section-heading">
        <div>
            <h3>Daftar Pengeluaran</h3>
            <p>Total hasil filter: <strong><?= e(rupiah($totalPengeluaran)) ?></strong></p>
        </div>
        <span class="pill"><?= e($totalRows) ?> data</span>
    </div>

    <?php render_table_filters_open('pengeluaran.php', 'Filter Pengeluaran'); ?>
        <div>
            <label for="q">Cari Pengeluaran</label>
            <input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="ID, kategori, atau keterangan">
        </div>
        <div>
            <label for="kategori_filter">Filter Kategori</label>
            <select id="kategori_filter" name="kategori">
                <option value="">Semua kategori</option>
                <?php foreach ($categoryRows as $categoryRow): ?>
                    <option value="<?= e($categoryRow['kategori']) ?>" <?= $categoryFilter === $categoryRow['kategori'] ? 'selected' : '' ?>>
                        <?= e($categoryRow['kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
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
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e(date('d/m/Y', strtotime((string) $row['tanggal_pengeluaran']))) ?></td>
                        <td><?= e($row['kategori']) ?></td>
                        <td><?= e($row['keterangan'] ?: '-') ?></td>
                        <td><?= e(rupiah((int) $row['jumlah'])) ?></td>
                        <td>
                            <a href="pengeluaran.php?id=<?= e($row['id_pengeluaran']) ?>">Ubah</a>
                            <form method="post" class="inline-form" onsubmit="return confirm('Hapus pengeluaran ini?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_pengeluaran" value="<?= e($row['id_pengeluaran']) ?>">
                                <button type="submit" class="link-button">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($rows === []): ?>
                    <tr>
                        <td colspan="5">Pengeluaran tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php render_pagination($totalRows, $page, $perPage); ?>
</section>

<?php
render_footer();
