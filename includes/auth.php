<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user(): ?array
{
    if (!isset($_SESSION['user'])) {
        return null;
    }

    return $_SESSION['user'];
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function require_login(): void
{
    if (is_logged_in()) {
        return;
    }

    header('Location: login.php');
    exit;
}

function require_role(array $allowedRoles): void
{
    require_login();

    $user = current_user();
    if ($user === null || !in_array($user['role'], $allowedRoles, true)) {
        http_response_code(403);
        exit('Akses ditolak. Anda tidak memiliki izin untuk membuka halaman ini.');
    }
}

function is_owner(): bool
{
    $user = current_user();

    return $user !== null && $user['role'] === 'pemilik';
}

function is_customer(): bool
{
    $user = current_user();

    return $user !== null && $user['role'] === 'pelanggan';
}
