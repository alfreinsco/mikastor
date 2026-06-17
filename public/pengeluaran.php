<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
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

$dateFrom = trim((string) ($_GET['date_from'] ?? date('Y-m-01')));
$dateTo = trim((string) ($_GET['date_to'] ?? date('Y-m-d')));
$fromDate = DateTimeImmutable::createFromFormat('Y-m-d', $dateFrom);
$toDate = DateTimeImmutable::createFromFormat('Y-m-d', $dateTo);

if ($fromDate === false || $fromDate->format('Y-m-d') !== $dateFrom) {
    $dateFrom = date('Y-m-01');
}

if ($toDate === false || $toDate->format('Y-m-d') !== $dateTo) {
    $dateTo = date('Y-m-d');
}

if ($dateFrom > $dateTo) {
    [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
}

$stmt = $pdo->prepare(
    'SELECT *
     FROM pengeluaran
     WHERE tanggal_pengeluaran BETWEEN ? AND ?
     ORDER BY tanggal_pengeluaran DESC, id_pengeluaran DESC'
);
$stmt->execute([$dateFrom, $dateTo]);
$rows = $stmt->fetchAll();

$totalStmt = $pdo->prepare(
    'SELECT COALESCE(SUM(jumlah), 0) AS total_pengeluaran
     FROM pengeluaran
     WHERE tanggal_pengeluaran BETWEEN ? AND ?'
);
$totalStmt->execute([$dateFrom, $dateTo]);
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
            <p>Total rentang ini: <strong><?= e(rupiah($totalPengeluaran)) ?></strong></p>
        </div>
    </div>

    <form class="table-filters" method="get">
        <div class="filter-title">Filter tanggal pengeluaran</div>
        <label for="date_from">Dari</label>
        <input type="date" id="date_from" name="date_from" value="<?= e($dateFrom) ?>">
        <label for="date_to">Sampai</label>
        <input type="date" id="date_to" name="date_to" value="<?= e($dateTo) ?>">
        <div class="filter-actions">
            <button type="submit">Terapkan</button>
            <a class="button secondary" href="pengeluaran.php">Reset</a>
        </div>
    </form>

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
                        <td colspan="5">Belum ada pengeluaran pada rentang ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
render_footer();
