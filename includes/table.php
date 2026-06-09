<?php

declare(strict_types=1);

function table_string(string $key): string
{
    return trim((string) ($_GET[$key] ?? ''));
}

function table_int(string $key, int $default = 1, int $min = 1, int $max = 100): int
{
    $value = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);

    if ($value === false || $value === null) {
        return $default;
    }

    return max($min, min($max, $value));
}

function table_page_size(): int
{
    return table_int('per_page', 10, 5, 50);
}

function table_query(array $overrides = []): string
{
    $params = array_merge($_GET, $overrides);

    foreach ($params as $key => $value) {
        if ($value === '' || $value === null) {
            unset($params[$key]);
        }
    }

    return http_build_query($params);
}

function render_table_filters_open(string $action, string $label = 'Filter Tabel'): void
{
    ?>
    <form class="table-filters" method="get" action="<?= e($action) ?>">
        <div class="filter-title"><?= e($label) ?></div>
    <?php
}

function render_table_filters_close(): void
{
    ?>
        <div class="filter-actions">
            <button type="submit">Terapkan</button>
            <a class="button secondary" href="<?= e(basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''))) ?>">Reset</a>
        </div>
    </form>
    <?php
}

function render_page_size_select(int $perPage): void
{
    ?>
    <div>
        <label for="per_page">Baris</label>
        <select id="per_page" name="per_page">
            <?php foreach ([5, 10, 20, 50] as $size): ?>
                <option value="<?= e($size) ?>" <?= $perPage === $size ? 'selected' : '' ?>><?= e($size) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}

function render_pagination(int $totalRows, int $page, int $perPage): void
{
    $totalPages = max(1, (int) ceil($totalRows / $perPage));
    $page = max(1, min($totalPages, $page));
    $start = $totalRows === 0 ? 0 : (($page - 1) * $perPage) + 1;
    $end = min($totalRows, $page * $perPage);
    ?>
    <div class="pagination">
        <span>Menampilkan <?= e($start) ?>-<?= e($end) ?> dari <?= e($totalRows) ?> data</span>
        <div class="pagination-links">
            <?php if ($page > 1): ?>
                <a href="?<?= e(table_query(['page' => $page - 1])) ?>">Sebelumnya</a>
            <?php endif; ?>

            <?php
            $first = max(1, $page - 2);
            $last = min($totalPages, $page + 2);
            for ($i = $first; $i <= $last; $i++):
                ?>
                <a class="<?= $i === $page ? 'active' : '' ?>" href="?<?= e(table_query(['page' => $i])) ?>"><?= e($i) ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?<?= e(table_query(['page' => $page + 1])) ?>">Berikutnya</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
