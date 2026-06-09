<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';
require_role(['pelanggan']);

$pdo = require __DIR__ . '/../config/database.php';
$user = current_user();
$errors = [];

$stmt = $pdo->prepare('SELECT * FROM pelanggan WHERE id_pelanggan = ?');
$stmt->execute([(int) $user['id_pelanggan']]);
$customer = $stmt->fetch();

if ($customer === false) {
    http_response_code(403);
    exit('Data pelanggan tidak ditemukan.');
}

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

    $namaPenerima = trim((string) ($_POST['nama_penerima'] ?? ''));
    $nomorTelepon = trim((string) ($_POST['nomor_telepon'] ?? ''));
    $alamatPengiriman = trim((string) ($_POST['alamat_pengiriman'] ?? ''));
    $metodePembayaran = (string) ($_POST['metode_pembayaran'] ?? 'transfer_bank');
    $catatan = trim((string) ($_POST['catatan'] ?? ''));

    if ($items === []) {
        $errors[] = 'Pilih minimal satu produk.';
    }

    if ($namaPenerima === '' || $nomorTelepon === '' || $alamatPengiriman === '') {
        $errors[] = 'Nama penerima, nomor telepon, dan alamat pengiriman wajib diisi.';
    }

    if (strlen($nomorTelepon) > 25) {
        $errors[] = 'Nomor telepon maksimal 25 karakter.';
    }

    if (!in_array($metodePembayaran, ['transfer_bank', 'cod'], true)) {
        $errors[] = 'Metode pembayaran tidak valid.';
    }

    if ($errors === []) {
        try {
            $pdo->beginTransaction();
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
                    throw new RuntimeException('Stok tidak cukup untuk ' . $product['nama_produk'] . '.');
                }

                $subtotal = (int) $product['harga'] * $jumlah;
                $totalHarga += $subtotal;
                $cart[] = [
                    'id_produk' => (int) $product['id_produk'],
                    'harga' => (int) $product['harga'],
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                ];
            }

            $stmt = $pdo->prepare(
                'INSERT INTO pesanan_online
                    (id_pelanggan, nama_penerima, nomor_telepon, alamat_pengiriman, metode_pembayaran, total_harga, catatan)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                (int) $user['id_pelanggan'],
                $namaPenerima,
                $nomorTelepon,
                $alamatPengiriman,
                $metodePembayaran,
                $totalHarga,
                $catatan !== '' ? $catatan : null,
            ]);
            $idPesanan = (int) $pdo->lastInsertId();

            $detailStmt = $pdo->prepare(
                'INSERT INTO detail_pesanan_online (id_pesanan, id_produk, harga, jumlah, subtotal) VALUES (?, ?, ?, ?, ?)'
            );
            $stockStmt = $pdo->prepare('UPDATE produk SET stok = stok - ? WHERE id_produk = ?');

            foreach ($cart as $item) {
                $detailStmt->execute([
                    $idPesanan,
                    $item['id_produk'],
                    $item['harga'],
                    $item['jumlah'],
                    $item['subtotal'],
                ]);
                $stockStmt->execute([$item['jumlah'], $item['id_produk']]);
            }

            $pdo->commit();
            set_flash('Pesanan berhasil dibuat. Lanjutkan pembayaran sampai lunas.');
            header('Location: bayar.php?id=' . $idPesanan);
            exit;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $errors[] = $exception->getMessage();
        }
    }
}

$products = $pdo
    ->query('SELECT * FROM produk WHERE stok > 0 ORDER BY harga ASC, nama_produk ASC')
    ->fetchAll();

render_header('Belanja Minyak Kayu Putih');
render_flash();
?>

<?php if ($errors !== []): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p><?= e($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<section class="hero shop-hero">
    <div class="hero-copy">
        <span class="eyebrow">Pesan langsung</span>
        <h3>Minyak kayu putih siap dikirim ke alamat Anda.</h3>
        <p>Pilih varian, checkout, bayar sampai lunas, lalu pantau status pengiriman dari akun pelanggan.</p>
    </div>
    <div class="hero-visual" aria-label="Produk minyak kayu putih"></div>
</section>

<form method="post" class="grid two-columns order-grid">
    <section class="card">
        <div class="section-heading">
            <h3>Pilih Produk</h3>
            <span class="pill"><?= e(count($products)) ?> tersedia</span>
        </div>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php
                $idProduk = (int) $product['id_produk'];
                $postedJumlah = $_POST['jumlah'][$idProduk] ?? 0;
                ?>
                <article class="product-card">
                    <div class="product-art">
                        <span class="product-icon large"></span>
                    </div>
                    <h4><?= e($product['nama_produk']) ?></h4>
                    <strong><?= e(rupiah((int) $product['harga'])) ?></strong>
                    <span class="stock-badge <?= (int) $product['stok'] <= 5 ? 'low' : '' ?>">
                        Stok <?= e($product['stok']) ?> botol
                    </span>
                    <label for="jumlah_<?= e($idProduk) ?>">Jumlah</label>
                    <input
                        type="number"
                        id="jumlah_<?= e($idProduk) ?>"
                        name="jumlah[<?= e($idProduk) ?>]"
                        min="0"
                        max="<?= e($product['stok']) ?>"
                        value="<?= e($postedJumlah) ?>"
                    >
                </article>
            <?php endforeach; ?>

            <?php if ($products === []): ?>
                <p>Produk belum tersedia.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="card checkout-panel">
        <div class="section-heading">
            <h3>Pengiriman</h3>
            <span class="pill">Checkout</span>
        </div>

        <label for="nama_penerima">Nama Penerima</label>
        <input
            type="text"
            id="nama_penerima"
            name="nama_penerima"
            value="<?= e($_POST['nama_penerima'] ?? $customer['nama_pelanggan']) ?>"
            required
        >

        <label for="nomor_telepon">Nomor Telepon</label>
        <input
            type="text"
            id="nomor_telepon"
            name="nomor_telepon"
            maxlength="25"
            value="<?= e($_POST['nomor_telepon'] ?? $customer['nomor_telepon']) ?>"
            required
        >

        <label for="alamat_pengiriman">Alamat Pengiriman</label>
        <textarea id="alamat_pengiriman" name="alamat_pengiriman" required><?= e($_POST['alamat_pengiriman'] ?? $customer['alamat'] ?? '') ?></textarea>

        <label for="metode_pembayaran">Metode Pembayaran</label>
        <select id="metode_pembayaran" name="metode_pembayaran">
            <option value="transfer_bank" <?= ($_POST['metode_pembayaran'] ?? '') === 'transfer_bank' ? 'selected' : '' ?>>Transfer Bank</option>
            <option value="cod" <?= ($_POST['metode_pembayaran'] ?? '') === 'cod' ? 'selected' : '' ?>>COD</option>
        </select>

        <label for="catatan">Catatan</label>
        <textarea id="catatan" name="catatan" placeholder="Opsional"><?= e($_POST['catatan'] ?? '') ?></textarea>

        <button type="submit">Buat Pesanan</button>
        <p class="hint">Stok akan diamankan saat pesanan dibuat.</p>
    </section>
</form>

<?php
render_footer();
