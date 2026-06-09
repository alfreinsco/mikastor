<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah(int|float $amount): string
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

function render_header(string $title): void
{
    $user = current_user();
    $currentPage = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $pageClass = 'page-' . preg_replace('/[^a-z0-9]+/', '-', strtolower(pathinfo($currentPage, PATHINFO_FILENAME)));
    $productPages = ['produk.php', 'produk_form.php'];
    $salesPages = ['penjualan.php', 'penjualan_detail.php'];
    $onlineOrderPages = ['pesanan_online.php', 'pesanan_online_detail.php'];
    $customerOrderPages = ['pesanan_saya.php', 'pesanan_saya_detail.php'];
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?> - MIKASTOR</title>
        <link rel="stylesheet" href="assets/style.css">
    </head>
    <body class="<?= e(($user === null ? 'auth-page' : 'app-page') . ' ' . $pageClass) ?>">
        <header class="topbar">
            <div class="brand">
                <img class="brand-logo" src="assets/mikastor-logo.svg" alt="Logo MIKASTOR">
                <div>
                    <h1>MIKASTOR</h1>
                    <p>Minyak kayu putih pilihan keluarga</p>
                </div>
            </div>
            <?php if ($user !== null): ?>
                <div class="user-info">
                    <div class="user-avatar"><?= e(strtoupper(substr((string) $user['nama'], 0, 1))) ?></div>
                    <div class="user-profile">
                        <strong><?= e($user['nama']) ?></strong>
                        <span><?= e(ucfirst($user['role'])) ?></span>
                    </div>
                    <a href="logout.php">Logout</a>
                </div>
            <?php else: ?>
                <div class="user-info auth-links">
                    <a href="home.php">Beranda</a>
                    <a href="login.php">Masuk</a>
                    <a href="daftar.php">Daftar Pelanggan</a>
                </div>
            <?php endif; ?>
        </header>

        <?php if ($user !== null): ?>
            <nav class="nav">
                <?php if (is_customer()): ?>
                    <a class="<?= $currentPage === 'toko.php' ? 'active' : '' ?>" href="toko.php">Belanja</a>
                    <a class="<?= in_array($currentPage, $customerOrderPages, true) ? 'active' : '' ?>" href="pesanan_saya.php">Pesanan Saya</a>
                <?php else: ?>
                    <a class="<?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php">Dashboard</a>
                    <?php if (is_owner()): ?>
                        <a class="<?= in_array($currentPage, $productPages, true) ? 'active' : '' ?>" href="produk.php">Produk</a>
                    <?php endif; ?>
                    <a class="<?= $currentPage === 'kasir.php' ? 'active' : '' ?>" href="kasir.php">Kasir</a>
                    <a class="<?= in_array($currentPage, $salesPages, true) ? 'active' : '' ?>" href="penjualan.php">Penjualan</a>
                    <a class="<?= in_array($currentPage, $onlineOrderPages, true) ? 'active' : '' ?>" href="pesanan_online.php">Pesanan Online</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

        <main class="container">
            <div class="page-title">
                <span>MIKASTOR POS</span>
                <h2><?= e($title) ?></h2>
            </div>
    <?php
}

function render_footer(): void
{
    ?>
        </main>
    </body>
    </html>
    <?php
}

function flash_message(): ?string
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $message = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $message;
}

function set_flash(string $message): void
{
    $_SESSION['flash'] = $message;
}

function render_flash(): void
{
    $message = flash_message();
    if ($message === null) {
        return;
    }
    ?>
    <div class="alert"><?= e($message) ?></div>
    <?php
}
