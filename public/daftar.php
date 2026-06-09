<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

if (is_logged_in()) {
    header('Location: ' . (is_customer() ? 'toko.php' : 'index.php'));
    exit;
}

$pdo = require __DIR__ . '/../config/database.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim((string) ($_POST['nama'] ?? ''));
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $nomorTelepon = trim((string) ($_POST['nomor_telepon'] ?? ''));
    $alamat = trim((string) ($_POST['alamat'] ?? ''));

    if ($nama === '') {
        $errors[] = 'Nama wajib diisi.';
    }

    if ($username === '') {
        $errors[] = 'Username wajib diisi.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter.';
    }

    if ($nomorTelepon === '') {
        $errors[] = 'Nomor telepon wajib diisi.';
    }

    if (strlen($nomorTelepon) > 25) {
        $errors[] = 'Nomor telepon maksimal 25 karakter.';
    }

    if ($alamat === '') {
        $errors[] = 'Alamat pengiriman wajib diisi.';
    }

    if ($errors === []) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                'INSERT INTO pelanggan (nama_pelanggan, nomor_telepon, alamat) VALUES (?, ?, ?)'
            );
            $stmt->execute([$nama, $nomorTelepon, $alamat]);
            $idPelanggan = (int) $pdo->lastInsertId();

            $stmt = $pdo->prepare(
                'INSERT INTO users (nama, username, password, role, id_pelanggan) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $nama,
                $username,
                password_hash($password, PASSWORD_DEFAULT),
                'pelanggan',
                $idPelanggan,
            ]);
            $idUser = (int) $pdo->lastInsertId();

            $pdo->commit();

            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id_user' => $idUser,
                'id_pelanggan' => $idPelanggan,
                'nama' => $nama,
                'username' => $username,
                'role' => 'pelanggan',
            ];

            set_flash('Akun pelanggan berhasil dibuat. Silakan mulai belanja.');
            header('Location: toko.php');
            exit;
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if (str_contains($exception->getMessage(), 'Duplicate')) {
                $errors[] = 'Username sudah digunakan.';
            } elseif (str_contains($exception->getMessage(), 'Data too long')) {
                $errors[] = 'Data terlalu panjang. Periksa nomor telepon atau isian lainnya.';
            } else {
                $errors[] = 'Pendaftaran gagal. Jalankan ulang database/schema.sql lalu coba lagi.';
            }
        }
    }
}

render_header('Daftar Pelanggan');
?>

<section class="login-shell">
    <div class="login-visual">
        <span class="eyebrow">Belanja online</span>
        <h3>Akun pelanggan untuk pesan minyak kayu putih dari rumah.</h3>
        <p>Setelah daftar, pelanggan bisa checkout, membayar pesanan, dan memantau pengiriman.</p>
    </div>

    <div class="card auth-card">
        <?php if ($errors !== []): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= e($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="section-heading">
            <h3>Buat Akun</h3>
            <span class="pill">Pelanggan</span>
        </div>

        <form method="post">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" value="<?= e($_POST['nama'] ?? '') ?>" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= e($_POST['username'] ?? '') ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="6" required>

            <label for="nomor_telepon">Nomor Telepon</label>
            <input type="text" id="nomor_telepon" name="nomor_telepon" maxlength="25" value="<?= e($_POST['nomor_telepon'] ?? '') ?>" required>

            <label for="alamat">Alamat Pengiriman</label>
            <textarea id="alamat" name="alamat" required><?= e($_POST['alamat'] ?? '') ?></textarea>

            <button type="submit">Daftar dan Belanja</button>
        </form>

        <p class="hint">Sudah punya akun? <a href="login.php">Masuk di sini</a>.</p>
    </div>
</section>

<?php
render_footer();
