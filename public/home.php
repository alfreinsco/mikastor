<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/layout.php';

$pdo = require __DIR__ . '/../config/database.php';
$products = $pdo
    ->query('SELECT id_produk, nama_produk, harga, stok FROM produk ORDER BY harga ASC, nama_produk ASC LIMIT 3')
    ->fetchAll();

$lowestPrice = $products !== [] ? min(array_map(static fn (array $product): int => (int) $product['harga'], $products)) : 0;
$primaryCta = is_logged_in() ? (is_customer() ? 'toko.php' : 'index.php') : 'daftar.php';
$primaryLabel = is_logged_in() ? (is_customer() ? 'Belanja Sekarang' : 'Buka Dashboard') : 'Belanja Sekarang';

render_header('Beranda');
?>

<section class="marketing-hero">
    <div class="marketing-hero-copy">
        <span class="eyebrow">Minyak kayu putih asli untuk keluarga</span>
        <h3>Hangat alami, dikemas modern, siap dikirim ke rumah Anda.</h3>
        <p>
            MIKASTOR menghadirkan minyak kayu putih pilihan dengan proses belanja online yang ringkas:
            pilih varian, upload bukti transfer, admin konfirmasi, lalu pesanan dikirim sampai selesai.
        </p>
        <div class="hero-actions">
            <a class="button" href="<?= e($primaryCta) ?>"><?= e($primaryLabel) ?></a>
            <a class="button secondary" href="#produk">Lihat Produk</a>
        </div>
        <div class="trust-strip">
            <span>Transfer diverifikasi admin</span>
            <span>Status pengiriman transparan</span>
            <span>Mulai <?= e($lowestPrice > 0 ? rupiah($lowestPrice) : 'harga toko') ?></span>
        </div>
    </div>
    <div class="marketing-visual">
        <div class="floating-order-card">
            <span>Status pesanan</span>
            <strong>Menunggu konfirmasi</strong>
            <small>Bukti transfer sudah diterima admin</small>
        </div>
    </div>
</section>

<section class="marketing-band">
    <div>
        <span class="eyebrow">Dibuat untuk pembeli yang ingin cepat</span>
        <h3>Belanja minyak kayu putih tanpa bolak-balik chat.</h3>
    </div>
    <div class="benefit-grid">
        <article>
            <strong>1</strong>
            <h4>Pilih varian</h4>
            <p>Produk dan stok tampil langsung dari sistem toko.</p>
        </article>
        <article>
            <strong>2</strong>
            <h4>Upload bukti</h4>
            <p>Transfer tidak langsung lunas sebelum admin mengecek bukti.</p>
        </article>
        <article>
            <strong>3</strong>
            <h4>Pantau kiriman</h4>
            <p>Pesanan bergerak dari dikemas, dikirim, sampai selesai.</p>
        </article>
    </div>
</section>

<section id="produk" class="marketing-section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Katalog unggulan</span>
            <h3>Varian yang paling mudah dipilih.</h3>
        </div>
        <a href="<?= e($primaryCta) ?>">Mulai belanja</a>
    </div>

    <div class="promo-products">
        <?php foreach ($products as $index => $product): ?>
            <article class="promo-product-card <?= $index === 1 ? 'featured' : '' ?>">
                <div class="product-art">
                    <span class="product-icon large"></span>
                </div>
                <span class="pill"><?= e((int) $product['stok'] > 0 ? 'Stok tersedia' : 'Stok kosong') ?></span>
                <h4><?= e($product['nama_produk']) ?></h4>
                <strong><?= e(rupiah((int) $product['harga'])) ?></strong>
                <button
                    type="button"
                    class="select-product"
                    data-name="<?= e($product['nama_produk']) ?>"
                    data-price="<?= e($product['harga']) ?>"
                >
                    Hitung Pesanan
                </button>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="interactive-panel">
    <div class="interactive-copy">
        <span class="eyebrow">Estimator cepat</span>
        <h3>Simulasikan pesanan sebelum masuk akun.</h3>
        <p>Pilih produk unggulan, atur jumlah, dan lihat estimasi total belanja secara langsung.</p>
    </div>
    <div class="estimate-box">
        <label for="estimate_product">Produk</label>
        <select id="estimate_product">
            <?php foreach ($products as $product): ?>
                <option value="<?= e($product['harga']) ?>"><?= e($product['nama_produk']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="estimate_qty">Jumlah Botol</label>
        <input type="range" id="estimate_qty" min="1" max="12" value="2">

        <div class="estimate-result">
            <span><span id="estimate_qty_text">2</span> botol</span>
            <strong id="estimate_total"><?= e($products !== [] ? rupiah((int) $products[0]['harga'] * 2) : rupiah(0)) ?></strong>
        </div>
        <a class="button" href="<?= e($primaryCta) ?>">Checkout di Akun</a>
    </div>
</section>

<section class="need-picker">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Pilih kebutuhan</span>
            <h3>Temukan cara pakai yang paling pas.</h3>
        </div>
    </div>
    <div class="need-tabs" role="tablist" aria-label="Pilihan kebutuhan">
        <button type="button" class="active" data-need="travel">Perjalanan</button>
        <button type="button" data-need="home">Rumah</button>
        <button type="button" data-need="gift">Hadiah</button>
    </div>
    <div class="need-content" id="need_content">
        <h4>Teman perjalanan yang ringkas</h4>
        <p>Siapkan ukuran kecil untuk tas harian, perjalanan keluarga, atau kebutuhan cepat saat cuaca dingin.</p>
    </div>
</section>

<section class="timeline-section">
    <span class="eyebrow">Alur transaksi</span>
    <h3>Dari pilih produk sampai barang diterima.</h3>
    <div class="timeline">
        <article>
            <span>01</span>
            <h4>Buat akun</h4>
            <p>Alamat dan nomor telepon tersimpan untuk checkout berikutnya.</p>
        </article>
        <article>
            <span>02</span>
            <h4>Upload transfer</h4>
            <p>Bukti pembayaran masuk ke halaman admin untuk dicek.</p>
        </article>
        <article>
            <span>03</span>
            <h4>Admin konfirmasi</h4>
            <p>Nota penjualan otomatis dibuat setelah pembayaran valid.</p>
        </article>
        <article>
            <span>04</span>
            <h4>Pesanan selesai</h4>
            <p>Pelanggan menandai barang diterima setelah paket sampai.</p>
        </article>
    </div>
</section>

<section class="faq-section">
    <div>
        <span class="eyebrow">Pertanyaan umum</span>
        <h3>Jawaban cepat sebelum belanja.</h3>
    </div>
    <div class="faq-list">
        <button type="button" class="faq-item active">
            <span>Apakah transfer langsung dianggap lunas?</span>
            <p>Tidak. Pelanggan upload bukti transfer, lalu admin mengecek dan mengonfirmasi pembayaran.</p>
        </button>
        <button type="button" class="faq-item">
            <span>Apakah bisa melihat status pengiriman?</span>
            <p>Bisa. Pelanggan dapat memantau status pesanan dari akun: menunggu pembayaran, dikemas, dikirim, sampai selesai.</p>
        </button>
        <button type="button" class="faq-item">
            <span>Apakah stok produk terlihat?</span>
            <p>Ya. Katalog pelanggan mengambil data stok dari sistem toko sehingga pilihan produk lebih jelas.</p>
        </button>
    </div>
</section>

<script>
const rupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
const productSelect = document.querySelector('#estimate_product');
const qtyInput = document.querySelector('#estimate_qty');
const qtyText = document.querySelector('#estimate_qty_text');
const totalText = document.querySelector('#estimate_total');

function updateEstimate() {
    const price = Number(productSelect?.value || 0);
    const qty = Number(qtyInput?.value || 1);
    if (qtyText && totalText) {
        qtyText.textContent = String(qty);
        totalText.textContent = rupiah.format(price * qty).replace(/\s/g, ' ');
    }
}

document.querySelectorAll('.select-product').forEach((button) => {
    button.addEventListener('click', () => {
        if (!productSelect) return;
        productSelect.value = button.dataset.price || productSelect.value;
        updateEstimate();
        document.querySelector('.interactive-panel')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
});

productSelect?.addEventListener('change', updateEstimate);
qtyInput?.addEventListener('input', updateEstimate);
updateEstimate();

const needCopy = {
    travel: ['Teman perjalanan yang ringkas', 'Siapkan ukuran kecil untuk tas harian, perjalanan keluarga, atau kebutuhan cepat saat cuaca dingin.'],
    home: ['Stok rumah yang selalu siap', 'Pilih ukuran sedang atau besar untuk kebutuhan keluarga agar tidak cepat habis saat dibutuhkan.'],
    gift: ['Paket sederhana yang bermanfaat', 'Produk natural dengan kemasan rapi cocok untuk bingkisan praktis bagi keluarga dan rekan.']
};

document.querySelectorAll('.need-tabs button').forEach((button) => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.need-tabs button').forEach((item) => item.classList.remove('active'));
        button.classList.add('active');
        const content = needCopy[button.dataset.need] || needCopy.travel;
        const target = document.querySelector('#need_content');
        if (target) {
            target.innerHTML = `<h4>${content[0]}</h4><p>${content[1]}</p>`;
        }
    });
});

document.querySelectorAll('.faq-item').forEach((item) => {
    item.addEventListener('click', () => {
        item.classList.toggle('active');
    });
});
</script>

<?php
render_footer();
