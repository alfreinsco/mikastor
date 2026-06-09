<?php

declare(strict_types=1);

function report_allowed_periods(): array
{
    return [
        'day' => 'Per Hari',
        'week' => 'Per Minggu',
        'month' => 'Per Bulan',
        'year' => 'Per Tahun',
    ];
}

function report_period_label(string $period): string
{
    $periods = report_allowed_periods();

    return $periods[$period] ?? $periods['day'];
}

function report_period_from_query(): string
{
    $period = (string) ($_GET['period'] ?? 'day');

    return array_key_exists($period, report_allowed_periods()) ? $period : 'day';
}

function report_date_from_query(string $key, string $default): string
{
    $value = trim((string) ($_GET[$key] ?? ''));
    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);

    if ($date === false || $date->format('Y-m-d') !== $value) {
        return $default;
    }

    return $value;
}

function report_current_filters(): array
{
    $today = new DateTimeImmutable('today');
    $defaultFrom = $today->modify('first day of this month')->format('Y-m-d');
    $defaultTo = $today->format('Y-m-d');
    $dateFrom = report_date_from_query('date_from', $defaultFrom);
    $dateTo = report_date_from_query('date_to', $defaultTo);

    if ($dateFrom > $dateTo) {
        [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
    }

    return [
        'period' => report_period_from_query(),
        'date_from' => $dateFrom,
        'date_to' => $dateTo,
    ];
}

function report_period_sql(string $period): array
{
    return match ($period) {
        'week' => [
            'key' => 'YEARWEEK(p.tanggal_transaksi, 3)',
            'label' => "CONCAT(YEARWEEK(MIN(p.tanggal_transaksi), 3) DIV 100, ' Minggu ', LPAD(YEARWEEK(MIN(p.tanggal_transaksi), 3) MOD 100, 2, '0'))",
            'start' => 'DATE_SUB(DATE(MIN(p.tanggal_transaksi)), INTERVAL WEEKDAY(MIN(p.tanggal_transaksi)) DAY)',
        ],
        'month' => [
            'key' => "DATE_FORMAT(p.tanggal_transaksi, '%Y-%m')",
            'label' => "DATE_FORMAT(MIN(p.tanggal_transaksi), '%m/%Y')",
            'start' => "DATE_FORMAT(MIN(p.tanggal_transaksi), '%Y-%m-01')",
        ],
        'year' => [
            'key' => 'YEAR(p.tanggal_transaksi)',
            'label' => "DATE_FORMAT(MIN(p.tanggal_transaksi), '%Y')",
            'start' => "DATE_FORMAT(MIN(p.tanggal_transaksi), '%Y-01-01')",
        ],
        default => [
            'key' => 'DATE(p.tanggal_transaksi)',
            'label' => "DATE_FORMAT(MIN(p.tanggal_transaksi), '%d/%m/%Y')",
            'start' => 'DATE(MIN(p.tanggal_transaksi))',
        ],
    };
}

function report_summary(PDO $pdo, array $filters): array
{
    $stmt = $pdo->prepare(
        'SELECT
            COUNT(*) AS total_nota,
            COALESCE(SUM(sale_items.total_item), 0) AS total_item,
            COALESCE(SUM(p.total_harga), 0) AS total_pendapatan,
            COALESCE(AVG(p.total_harga), 0) AS rata_rata_nota
         FROM penjualan p
         LEFT JOIN (
            SELECT id_penjualan, SUM(jumlah) AS total_item
            FROM detail_penjualan
            GROUP BY id_penjualan
         ) sale_items ON sale_items.id_penjualan = p.id_penjualan
         WHERE DATE(p.tanggal_transaksi) BETWEEN ? AND ?'
    );
    $stmt->execute([$filters['date_from'], $filters['date_to']]);

    return $stmt->fetch() ?: [
        'total_nota' => 0,
        'total_item' => 0,
        'total_pendapatan' => 0,
        'rata_rata_nota' => 0,
    ];
}

function report_period_rows(PDO $pdo, array $filters): array
{
    $periodSql = report_period_sql($filters['period']);
    $stmt = $pdo->prepare(
        "SELECT
            {$periodSql['key']} AS periode_key,
            {$periodSql['label']} AS periode_label,
            {$periodSql['start']} AS tanggal_mulai,
            COUNT(*) AS total_nota,
            COALESCE(SUM(sale_items.total_item), 0) AS total_item,
            COALESCE(SUM(p.total_harga), 0) AS total_pendapatan,
            COALESCE(AVG(p.total_harga), 0) AS rata_rata_nota
         FROM penjualan p
         LEFT JOIN (
            SELECT id_penjualan, SUM(jumlah) AS total_item
            FROM detail_penjualan
            GROUP BY id_penjualan
         ) sale_items ON sale_items.id_penjualan = p.id_penjualan
         WHERE DATE(p.tanggal_transaksi) BETWEEN ? AND ?
         GROUP BY periode_key
         ORDER BY tanggal_mulai ASC"
    );
    $stmt->execute([$filters['date_from'], $filters['date_to']]);

    return $stmt->fetchAll();
}

function report_product_rows(PDO $pdo, array $filters): array
{
    $stmt = $pdo->prepare(
        'SELECT
            pr.nama_produk,
            COALESCE(SUM(dp.jumlah), 0) AS total_item,
            COALESCE(SUM(dp.subtotal), 0) AS total_pendapatan,
            COUNT(DISTINCT p.id_penjualan) AS total_nota
         FROM detail_penjualan dp
         JOIN penjualan p ON p.id_penjualan = dp.id_penjualan
         JOIN produk pr ON pr.id_produk = dp.id_produk
         WHERE DATE(p.tanggal_transaksi) BETWEEN ? AND ?
         GROUP BY pr.id_produk, pr.nama_produk
         ORDER BY total_pendapatan DESC, total_item DESC, pr.nama_produk ASC'
    );
    $stmt->execute([$filters['date_from'], $filters['date_to']]);

    return $stmt->fetchAll();
}

function report_query(array $overrides = []): string
{
    $params = array_merge($_GET, $overrides);

    foreach ($params as $key => $value) {
        if ($value === '' || $value === null) {
            unset($params[$key]);
        }
    }

    return http_build_query($params);
}
