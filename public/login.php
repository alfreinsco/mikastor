<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

if (is_logged_in()) {
    header('Location: ' . (is_customer() ? 'toko.php' : 'index.php'));
    exit;
}

$pdo = require __DIR__ . '/../config/database.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user !== false && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id_user' => (int) $user['id_user'],
                'id_pelanggan' => $user['id_pelanggan'] !== null ? (int) $user['id_pelanggan'] : null,
                'nama' => $user['nama'],
                'username' => $user['username'],
                'role' => $user['role'],
            ];

            header('Location: ' . ($user['role'] === 'pelanggan' ? 'toko.php' : 'index.php'));
            exit;
        }

        $error = 'Username atau password salah.';
    }
}

render_header('Login');
?>

<section class="login-shell">
    <div class="login-visual">
        <span class="eyebrow">MIKASTOR</span>
        <h3>Kelola penjualan minyak kayu putih dengan tampilan toko modern.</h3>
        <p>Stok, kasir, dan riwayat nota tersusun rapi untuk pelayanan yang lebih cepat.</p>
    </div>

<div class="card auth-card">
    <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="section-heading">
        <h3>Masuk</h3>
        <span class="pill">Area toko</span>
    </div>

    <form method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" autocomplete="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>

        <button type="submit">Masuk</button>
    </form>

    <p class="hint">Akun awal: <strong>admin/admin123</strong> atau <strong>kasir/kasir123</strong>.</p>
    <p class="hint">Pelanggan baru bisa daftar lewat <a href="daftar.php">halaman pendaftaran</a>.</p>
</div>
</section>

<?php
render_footer();
