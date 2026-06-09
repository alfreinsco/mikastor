<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_role(['pemilik']);

$pdo = require __DIR__ . '/../config/database.php';
$errors = [];
$idProduk = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = null;

if ($idProduk !== false && $idProduk !== null) {
    $stmt = $pdo->prepare('SELECT * FROM produk WHERE id_produk = ?');
    $stmt->execute([$idProduk]);
    $product = $stmt->fetch() ?: null;

    if ($product === null) {
        http_response_code(404);
        exit('Produk tidak ditemukan.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedId = filter_input(INPUT_POST, 'id_produk', FILTER_VALIDATE_INT);
    $namaProduk = trim((string) ($_POST['nama_produk'] ?? ''));
    $harga = filter_input(INPUT_POST, 'harga', FILTER_VALIDATE_INT);
    $stok = filter_input(INPUT_POST, 'stok', FILTER_VALIDATE_INT);

    if ($namaProduk === '') {
        $errors[] = 'Nama produk wajib diisi.';
    }

    if ($harga === false || $harga === null || $harga < 0) {
        $errors[] = 'Harga harus berupa angka minimal 0.';
    }

    if ($stok === false || $stok === null || $stok < 0) {
        $errors[] = 'Stok harus berupa angka minimal 0.';
    }

    if ($errors === []) {
        try {
            if ($postedId !== false && $postedId !== null) {
                $stmt = $pdo->prepare(
                    'UPDATE produk SET nama_produk = ?, harga = ?, stok = ? WHERE id_produk = ?'
                );
                $stmt->execute([$namaProduk, $harga, $stok, $postedId]);
                set_flash('Produk berhasil diperbarui.');
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO produk (nama_produk, harga, stok) VALUES (?, ?, ?)'
                );
                $stmt->execute([$namaProduk, $harga, $stok]);
                set_flash('Produk berhasil ditambahkan.');
            }

            header('Location: produk.php');
            exit;
        } catch (Throwable $exception) {
            $errors[] = str_contains($exception->getMessage(), 'Duplicate')
                ? 'Nama produk sudah digunakan.'
                : 'Produk gagal disimpan.';
        }
    }
}

render_header($product === null ? 'Tambah Produk' : 'Ubah Produk');
render_flash();
?>

<section class="card form-page">
    <div class="section-heading">
        <h3><?= $product === null ? 'Tambah Produk' : 'Ubah Produk' ?></h3>
        <a href="produk.php">Kembali ke daftar</a>
    </div>

    <?php if ($errors !== []): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <?php if ($product !== null): ?>
            <input type="hidden" name="id_produk" value="<?= e($product['id_produk']) ?>">
        <?php endif; ?>

        <label for="nama_produk">Nama Produk</label>
        <input
            type="text"
            id="nama_produk"
            name="nama_produk"
            value="<?= e($_POST['nama_produk'] ?? $product['nama_produk'] ?? '') ?>"
            required
        >

        <label for="harga">Harga</label>
        <input
            type="number"
            id="harga"
            name="harga"
            min="0"
            value="<?= e($_POST['harga'] ?? $product['harga'] ?? '') ?>"
            required
        >

        <label for="stok">Stok</label>
        <input
            type="number"
            id="stok"
            name="stok"
            min="0"
            value="<?= e($_POST['stok'] ?? $product['stok'] ?? '') ?>"
            required
        >

        <button type="submit"><?= $product === null ? 'Simpan Produk' : 'Update Produk' ?></button>
        <a class="button secondary" href="produk.php">Batal</a>
    </form>
</section>

<?php
render_footer();
