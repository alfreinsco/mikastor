-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 20 Jun 2026 pada 10.08
-- Versi server: 9.1.0
-- Versi PHP: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `mikastor`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail` int NOT NULL,
  `id_penjualan` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL
) ;

--
-- Dumping data untuk tabel `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id_detail`, `id_penjualan`, `id_produk`, `jumlah`, `subtotal`) VALUES
(1, 1, 4, 73, 16425000),
(2, 2, 4, 70, 15750000),
(3, 3, 4, 71, 15975000),
(4, 4, 4, 59, 13275000),
(5, 5, 4, 62, 13950000),
(6, 6, 4, 64, 14400000),
(7, 7, 4, 85, 19125000),
(8, 8, 4, 89, 20025000),
(9, 9, 4, 86, 19350000),
(10, 10, 4, 78, 17550000),
(11, 11, 4, 76, 17100000),
(12, 12, 4, 81, 18225000),
(13, 13, 4, 92, 20700000),
(14, 14, 4, 91, 20475000),
(15, 15, 4, 92, 20700000),
(16, 16, 4, 110, 24750000),
(17, 17, 4, 110, 24750000),
(18, 18, 4, 106, 23850000),
(19, 19, 4, 94, 21150000),
(20, 20, 4, 93, 20925000),
(21, 21, 4, 103, 23175000),
(22, 22, 4, 102, 22950000),
(23, 23, 4, 102, 22950000),
(24, 24, 4, 106, 23850000),
(25, 25, 4, 91, 20475000),
(26, 26, 4, 92, 20700000),
(27, 27, 4, 92, 20700000),
(28, 28, 4, 82, 18450000),
(29, 29, 4, 84, 18900000),
(30, 30, 4, 79, 17775000),
(31, 31, 4, 86, 19350000),
(32, 32, 4, 82, 18450000),
(33, 33, 4, 85, 19125000),
(34, 34, 4, 86, 19350000),
(35, 35, 4, 88, 19800000),
(36, 36, 4, 91, 20475000),
(37, 37, 4, 61, 13725000),
(38, 38, 4, 64, 14400000),
(39, 39, 4, 65, 14625000),
(40, 40, 4, 53, 11925000),
(41, 41, 4, 57, 12825000),
(42, 42, 4, 52, 11700000),
(43, 43, 4, 80, 18000000),
(44, 44, 4, 78, 17550000),
(45, 45, 4, 82, 18450000),
(46, 46, 4, 71, 15975000),
(47, 47, 4, 70, 15750000),
(48, 48, 4, 71, 15975000),
(49, 49, 4, 82, 18450000),
(50, 50, 4, 82, 18450000),
(51, 51, 4, 76, 17100000),
(52, 52, 4, 69, 15525000),
(53, 53, 4, 68, 15300000),
(54, 54, 4, 78, 17550000),
(55, 55, 4, 92, 20700000),
(56, 56, 4, 92, 20700000),
(57, 57, 4, 95, 21375000),
(58, 58, 4, 98, 22050000),
(59, 59, 4, 99, 22275000),
(60, 60, 4, 98, 22050000),
(61, 61, 4, 84, 18900000),
(62, 62, 4, 86, 19350000),
(63, 63, 4, 81, 18225000),
(64, 64, 4, 68, 15300000),
(65, 65, 4, 64, 14400000),
(66, 66, 4, 68, 15300000),
(67, 67, 4, 89, 20025000),
(68, 68, 4, 91, 20475000),
(69, 69, 4, 95, 21375000),
(70, 70, 4, 99, 22275000),
(71, 71, 4, 102, 22950000),
(72, 72, 4, 99, 22275000),
(73, 73, 4, 74, 16650000),
(74, 74, 4, 78, 17550000),
(75, 75, 4, 73, 16425000),
(76, 76, 4, 65, 14625000),
(77, 77, 4, 63, 14175000),
(78, 78, 4, 67, 15075000),
(79, 79, 4, 85, 19125000),
(80, 80, 4, 84, 18900000),
(81, 81, 4, 85, 19125000),
(82, 82, 4, 93, 20925000),
(83, 83, 4, 93, 20925000),
(84, 84, 4, 87, 19575000),
(85, 85, 4, 93, 20925000),
(86, 86, 4, 92, 20700000),
(87, 87, 4, 100, 22500000),
(88, 88, 4, 72, 16200000),
(89, 89, 4, 72, 16200000),
(90, 90, 4, 76, 17100000),
(91, 91, 4, 97, 21825000),
(92, 92, 4, 98, 22050000),
(93, 93, 4, 98, 22050000),
(94, 94, 4, 121, 27225000),
(95, 95, 4, 123, 27675000),
(96, 96, 4, 116, 26100000),
(97, 97, 4, 88, 19800000),
(98, 98, 4, 84, 18900000),
(99, 99, 4, 88, 19800000),
(100, 100, 4, 76, 17100000),
(101, 101, 4, 78, 17550000),
(102, 102, 4, 81, 18225000),
(103, 103, 4, 77, 17325000),
(104, 104, 4, 80, 18000000),
(105, 105, 4, 78, 17550000),
(106, 106, 4, 80, 18000000),
(107, 107, 4, 77, 17325000),
(108, 108, 4, 85, 19125000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan_online`
--

CREATE TABLE `detail_pesanan_online` (
  `id_detail_pesanan` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_produk` int NOT NULL,
  `harga` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama_pelanggan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_telepon` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '-',
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `nomor_telepon`, `alamat`, `created_at`) VALUES
(1, 'Pelanggan Umum', '-', NULL, '2026-06-20 18:38:06'),
(2, 'Pelanggan Laporan Keuangan', '-', 'Data seeder laporan keuangan 2023-2025', '2026-06-20 18:38:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int NOT NULL,
  `tanggal_pengeluaran` date NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `jumlah` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data untuk tabel `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `tanggal_pengeluaran`, `kategori`, `keterangan`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, '2023-01-02', 'Biaya Daun', '[Seeder data dede] Jan 2023 - Biaya Daun', 21400000, '2026-06-20 18:38:06', NULL),
(2, '2023-01-10', 'Kayu Bakar', '[Seeder data dede] Jan 2023 - Kayu Bakar', 80250, '2026-06-20 18:38:06', NULL),
(3, '2023-01-16', 'Upah Produksi', '[Seeder data dede] Jan 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(4, '2023-01-22', 'Transportasi', '[Seeder data dede] Jan 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(5, '2023-02-02', 'Biaya Daun', '[Seeder data dede] Feb 2023 - Biaya Daun', 18500000, '2026-06-20 18:38:06', NULL),
(6, '2023-02-10', 'Kayu Bakar', '[Seeder data dede] Feb 2023 - Kayu Bakar', 69375, '2026-06-20 18:38:06', NULL),
(7, '2023-02-16', 'Upah Produksi', '[Seeder data dede] Feb 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(8, '2023-02-22', 'Transportasi', '[Seeder data dede] Feb 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(9, '2023-03-02', 'Biaya Daun', '[Seeder data dede] Mar 2023 - Biaya Daun', 26000000, '2026-06-20 18:38:06', NULL),
(10, '2023-03-10', 'Kayu Bakar', '[Seeder data dede] Mar 2023 - Kayu Bakar', 97500, '2026-06-20 18:38:06', NULL),
(11, '2023-03-16', 'Upah Produksi', '[Seeder data dede] Mar 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(12, '2023-03-22', 'Transportasi', '[Seeder data dede] Mar 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(13, '2023-04-02', 'Biaya Daun', '[Seeder data dede] Apr 2023 - Biaya Daun', 23500000, '2026-06-20 18:38:06', NULL),
(14, '2023-04-10', 'Kayu Bakar', '[Seeder data dede] Apr 2023 - Kayu Bakar', 88125, '2026-06-20 18:38:06', NULL),
(15, '2023-04-16', 'Upah Produksi', '[Seeder data dede] Apr 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(16, '2023-04-22', 'Transportasi', '[Seeder data dede] Apr 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(17, '2023-05-02', 'Biaya Daun', '[Seeder data dede] Mei 2023 - Biaya Daun', 27500000, '2026-06-20 18:38:06', NULL),
(18, '2023-05-10', 'Kayu Bakar', '[Seeder data dede] Mei 2023 - Kayu Bakar', 103125, '2026-06-20 18:38:06', NULL),
(19, '2023-05-16', 'Upah Produksi', '[Seeder data dede] Mei 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(20, '2023-05-22', 'Transportasi', '[Seeder data dede] Mei 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(21, '2023-06-02', 'Biaya Daun', '[Seeder data dede] Jun 2023 - Biaya Daun', 32600000, '2026-06-20 18:38:06', NULL),
(22, '2023-06-10', 'Kayu Bakar', '[Seeder data dede] Jun 2023 - Kayu Bakar', 122250, '2026-06-20 18:38:06', NULL),
(23, '2023-06-16', 'Upah Produksi', '[Seeder data dede] Jun 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(24, '2023-06-22', 'Transportasi', '[Seeder data dede] Jun 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(25, '2023-07-02', 'Biaya Daun', '[Seeder data dede] Jul 2023 - Biaya Daun', 29000000, '2026-06-20 18:38:06', NULL),
(26, '2023-07-10', 'Kayu Bakar', '[Seeder data dede] Jul 2023 - Kayu Bakar', 108750, '2026-06-20 18:38:06', NULL),
(27, '2023-07-16', 'Upah Produksi', '[Seeder data dede] Jul 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(28, '2023-07-22', 'Transportasi', '[Seeder data dede] Jul 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(29, '2023-08-02', 'Biaya Daun', '[Seeder data dede] Agu 2023 - Biaya Daun', 31000000, '2026-06-20 18:38:06', NULL),
(30, '2023-08-10', 'Kayu Bakar', '[Seeder data dede] Agu 2023 - Kayu Bakar', 116250, '2026-06-20 18:38:06', NULL),
(31, '2023-08-16', 'Upah Produksi', '[Seeder data dede] Agu 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(32, '2023-08-22', 'Transportasi', '[Seeder data dede] Agu 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(33, '2023-09-02', 'Biaya Daun', '[Seeder data dede] Sep 2023 - Biaya Daun', 27500000, '2026-06-20 18:38:06', NULL),
(34, '2023-09-10', 'Kayu Bakar', '[Seeder data dede] Sep 2023 - Kayu Bakar', 103125, '2026-06-20 18:38:06', NULL),
(35, '2023-09-16', 'Upah Produksi', '[Seeder data dede] Sep 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(36, '2023-09-22', 'Transportasi', '[Seeder data dede] Sep 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(37, '2023-10-02', 'Biaya Daun', '[Seeder data dede] Okt 2023 - Biaya Daun', 24500000, '2026-06-20 18:38:06', NULL),
(38, '2023-10-10', 'Kayu Bakar', '[Seeder data dede] Okt 2023 - Kayu Bakar', 91875, '2026-06-20 18:38:06', NULL),
(39, '2023-10-16', 'Upah Produksi', '[Seeder data dede] Okt 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(40, '2023-10-22', 'Transportasi', '[Seeder data dede] Okt 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(41, '2023-11-02', 'Biaya Daun', '[Seeder data dede] Nov 2023 - Biaya Daun', 25300000, '2026-06-20 18:38:06', NULL),
(42, '2023-11-10', 'Kayu Bakar', '[Seeder data dede] Nov 2023 - Kayu Bakar', 94875, '2026-06-20 18:38:06', NULL),
(43, '2023-11-16', 'Upah Produksi', '[Seeder data dede] Nov 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(44, '2023-11-22', 'Transportasi', '[Seeder data dede] Nov 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(45, '2023-12-02', 'Biaya Daun', '[Seeder data dede] Des 2023 - Biaya Daun', 26500000, '2026-06-20 18:38:06', NULL),
(46, '2023-12-10', 'Kayu Bakar', '[Seeder data dede] Des 2023 - Kayu Bakar', 99375, '2026-06-20 18:38:06', NULL),
(47, '2023-12-16', 'Upah Produksi', '[Seeder data dede] Des 2023 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(48, '2023-12-22', 'Transportasi', '[Seeder data dede] Des 2023 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(49, '2024-01-02', 'Biaya Daun', '[Seeder data dede] Jan 2024 - Biaya Daun', 19000000, '2026-06-20 18:38:06', NULL),
(50, '2024-01-10', 'Kayu Bakar', '[Seeder data dede] Jan 2024 - Kayu Bakar', 71250, '2026-06-20 18:38:06', NULL),
(51, '2024-01-16', 'Upah Produksi', '[Seeder data dede] Jan 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(52, '2024-01-22', 'Transportasi', '[Seeder data dede] Jan 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(53, '2024-02-02', 'Biaya Daun', '[Seeder data dede] Feb 2024 - Biaya Daun', 16200000, '2026-06-20 18:38:06', NULL),
(54, '2024-02-10', 'Kayu Bakar', '[Seeder data dede] Feb 2024 - Kayu Bakar', 60750, '2026-06-20 18:38:06', NULL),
(55, '2024-02-16', 'Upah Produksi', '[Seeder data dede] Feb 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(56, '2024-02-22', 'Transportasi', '[Seeder data dede] Feb 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(57, '2024-03-02', 'Biaya Daun', '[Seeder data dede] Mar 2024 - Biaya Daun', 24000000, '2026-06-20 18:38:06', NULL),
(58, '2024-03-10', 'Kayu Bakar', '[Seeder data dede] Mar 2024 - Kayu Bakar', 90000, '2026-06-20 18:38:06', NULL),
(59, '2024-03-16', 'Upah Produksi', '[Seeder data dede] Mar 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(60, '2024-03-22', 'Transportasi', '[Seeder data dede] Mar 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(61, '2024-04-02', 'Biaya Daun', '[Seeder data dede] Apr 2024 - Biaya Daun', 21200000, '2026-06-20 18:38:06', NULL),
(62, '2024-04-10', 'Kayu Bakar', '[Seeder data dede] Apr 2024 - Kayu Bakar', 79500, '2026-06-20 18:38:06', NULL),
(63, '2024-04-16', 'Upah Produksi', '[Seeder data dede] Apr 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(64, '2024-04-22', 'Transportasi', '[Seeder data dede] Apr 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(65, '2024-05-02', 'Biaya Daun', '[Seeder data dede] Mei 2024 - Biaya Daun', 24000000, '2026-06-20 18:38:06', NULL),
(66, '2024-05-10', 'Kayu Bakar', '[Seeder data dede] Mei 2024 - Kayu Bakar', 90000, '2026-06-20 18:38:06', NULL),
(67, '2024-05-16', 'Upah Produksi', '[Seeder data dede] Mei 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(68, '2024-05-22', 'Transportasi', '[Seeder data dede] Mei 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(69, '2024-06-02', 'Biaya Daun', '[Seeder data dede] Jun 2024 - Biaya Daun', 21500000, '2026-06-20 18:38:06', NULL),
(70, '2024-06-10', 'Kayu Bakar', '[Seeder data dede] Jun 2024 - Kayu Bakar', 80625, '2026-06-20 18:38:06', NULL),
(71, '2024-06-16', 'Upah Produksi', '[Seeder data dede] Jun 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(72, '2024-06-22', 'Transportasi', '[Seeder data dede] Jun 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(73, '2024-07-02', 'Biaya Daun', '[Seeder data dede] Jul 2024 - Biaya Daun', 27900000, '2026-06-20 18:38:06', NULL),
(74, '2024-07-10', 'Kayu Bakar', '[Seeder data dede] Jul 2024 - Kayu Bakar', 104625, '2026-06-20 18:38:06', NULL),
(75, '2024-07-16', 'Upah Produksi', '[Seeder data dede] Jul 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(76, '2024-07-22', 'Transportasi', '[Seeder data dede] Jul 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(77, '2024-08-02', 'Biaya Daun', '[Seeder data dede] Agu 2024 - Biaya Daun', 29500000, '2026-06-20 18:38:06', NULL),
(78, '2024-08-10', 'Kayu Bakar', '[Seeder data dede] Agu 2024 - Kayu Bakar', 110625, '2026-06-20 18:38:06', NULL),
(79, '2024-08-16', 'Upah Produksi', '[Seeder data dede] Agu 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(80, '2024-08-22', 'Transportasi', '[Seeder data dede] Agu 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(81, '2024-09-02', 'Biaya Daun', '[Seeder data dede] Sep 2024 - Biaya Daun', 25100000, '2026-06-20 18:38:06', NULL),
(82, '2024-09-10', 'Kayu Bakar', '[Seeder data dede] Sep 2024 - Kayu Bakar', 94125, '2026-06-20 18:38:06', NULL),
(83, '2024-09-16', 'Upah Produksi', '[Seeder data dede] Sep 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(84, '2024-09-22', 'Transportasi', '[Seeder data dede] Sep 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(85, '2024-10-02', 'Biaya Daun', '[Seeder data dede] Okt 2024 - Biaya Daun', 20000000, '2026-06-20 18:38:06', NULL),
(86, '2024-10-10', 'Kayu Bakar', '[Seeder data dede] Okt 2024 - Kayu Bakar', 75000, '2026-06-20 18:38:06', NULL),
(87, '2024-10-16', 'Upah Produksi', '[Seeder data dede] Okt 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(88, '2024-10-22', 'Transportasi', '[Seeder data dede] Okt 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(89, '2024-11-02', 'Biaya Daun', '[Seeder data dede] Nov 2024 - Biaya Daun', 27500000, '2026-06-20 18:38:06', NULL),
(90, '2024-11-10', 'Kayu Bakar', '[Seeder data dede] Nov 2024 - Kayu Bakar', 103125, '2026-06-20 18:38:06', NULL),
(91, '2024-11-16', 'Upah Produksi', '[Seeder data dede] Nov 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(92, '2024-11-22', 'Transportasi', '[Seeder data dede] Nov 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(93, '2024-12-02', 'Biaya Daun', '[Seeder data dede] Des 2024 - Biaya Daun', 30000000, '2026-06-20 18:38:06', NULL),
(94, '2024-12-10', 'Kayu Bakar', '[Seeder data dede] Des 2024 - Kayu Bakar', 112500, '2026-06-20 18:38:06', NULL),
(95, '2024-12-16', 'Upah Produksi', '[Seeder data dede] Des 2024 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(96, '2024-12-22', 'Transportasi', '[Seeder data dede] Des 2024 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(97, '2025-01-02', 'Biaya Daun', '[Seeder data dede] Jan 2025 - Biaya Daun', 22500000, '2026-06-20 18:38:06', NULL),
(98, '2025-01-10', 'Kayu Bakar', '[Seeder data dede] Jan 2025 - Kayu Bakar', 84375, '2026-06-20 18:38:06', NULL),
(99, '2025-01-16', 'Upah Produksi', '[Seeder data dede] Jan 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(100, '2025-01-22', 'Transportasi', '[Seeder data dede] Jan 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(101, '2025-02-02', 'Biaya Daun', '[Seeder data dede] Feb 2025 - Biaya Daun', 19500000, '2026-06-20 18:38:06', NULL),
(102, '2025-02-10', 'Kayu Bakar', '[Seeder data dede] Feb 2025 - Kayu Bakar', 73125, '2026-06-20 18:38:06', NULL),
(103, '2025-02-16', 'Upah Produksi', '[Seeder data dede] Feb 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(104, '2025-02-22', 'Transportasi', '[Seeder data dede] Feb 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(105, '2025-03-02', 'Biaya Daun', '[Seeder data dede] Mar 2025 - Biaya Daun', 25400000, '2026-06-20 18:38:06', NULL),
(106, '2025-03-10', 'Kayu Bakar', '[Seeder data dede] Mar 2025 - Kayu Bakar', 95250, '2026-06-20 18:38:06', NULL),
(107, '2025-03-16', 'Upah Produksi', '[Seeder data dede] Mar 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(108, '2025-03-22', 'Transportasi', '[Seeder data dede] Mar 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(109, '2025-04-02', 'Biaya Daun', '[Seeder data dede] Apr 2025 - Biaya Daun', 27300000, '2026-06-20 18:38:06', NULL),
(110, '2025-04-10', 'Kayu Bakar', '[Seeder data dede] Apr 2025 - Kayu Bakar', 102375, '2026-06-20 18:38:06', NULL),
(111, '2025-04-16', 'Upah Produksi', '[Seeder data dede] Apr 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(112, '2025-04-22', 'Transportasi', '[Seeder data dede] Apr 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(113, '2025-05-02', 'Biaya Daun', '[Seeder data dede] Mei 2025 - Biaya Daun', 28500000, '2026-06-20 18:38:06', NULL),
(114, '2025-05-10', 'Kayu Bakar', '[Seeder data dede] Mei 2025 - Kayu Bakar', 106875, '2026-06-20 18:38:06', NULL),
(115, '2025-05-16', 'Upah Produksi', '[Seeder data dede] Mei 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(116, '2025-05-22', 'Transportasi', '[Seeder data dede] Mei 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(117, '2025-06-02', 'Biaya Daun', '[Seeder data dede] Jun 2025 - Biaya Daun', 22000000, '2026-06-20 18:38:06', NULL),
(118, '2025-06-10', 'Kayu Bakar', '[Seeder data dede] Jun 2025 - Kayu Bakar', 82500, '2026-06-20 18:38:06', NULL),
(119, '2025-06-16', 'Upah Produksi', '[Seeder data dede] Jun 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(120, '2025-06-22', 'Transportasi', '[Seeder data dede] Jun 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(121, '2025-07-02', 'Biaya Daun', '[Seeder data dede] Jul 2025 - Biaya Daun', 29300000, '2026-06-20 18:38:06', NULL),
(122, '2025-07-10', 'Kayu Bakar', '[Seeder data dede] Jul 2025 - Kayu Bakar', 109875, '2026-06-20 18:38:06', NULL),
(123, '2025-07-16', 'Upah Produksi', '[Seeder data dede] Jul 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(124, '2025-07-22', 'Transportasi', '[Seeder data dede] Jul 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(125, '2025-08-02', 'Biaya Daun', '[Seeder data dede] Agu 2025 - Biaya Daun', 36000000, '2026-06-20 18:38:06', NULL),
(126, '2025-08-10', 'Kayu Bakar', '[Seeder data dede] Agu 2025 - Kayu Bakar', 135000, '2026-06-20 18:38:06', NULL),
(127, '2025-08-16', 'Upah Produksi', '[Seeder data dede] Agu 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(128, '2025-08-22', 'Transportasi', '[Seeder data dede] Agu 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(129, '2025-09-02', 'Biaya Daun', '[Seeder data dede] Sep 2025 - Biaya Daun', 26000000, '2026-06-20 18:38:06', NULL),
(130, '2025-09-10', 'Kayu Bakar', '[Seeder data dede] Sep 2025 - Kayu Bakar', 97500, '2026-06-20 18:38:06', NULL),
(131, '2025-09-16', 'Upah Produksi', '[Seeder data dede] Sep 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(132, '2025-09-22', 'Transportasi', '[Seeder data dede] Sep 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(133, '2025-10-02', 'Biaya Daun', '[Seeder data dede] Okt 2025 - Biaya Daun', 23500000, '2026-06-20 18:38:06', NULL),
(134, '2025-10-10', 'Kayu Bakar', '[Seeder data dede] Okt 2025 - Kayu Bakar', 88125, '2026-06-20 18:38:06', NULL),
(135, '2025-10-16', 'Upah Produksi', '[Seeder data dede] Okt 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(136, '2025-10-22', 'Transportasi', '[Seeder data dede] Okt 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(137, '2025-11-02', 'Biaya Daun', '[Seeder data dede] Nov 2025 - Biaya Daun', 23500000, '2026-06-20 18:38:06', NULL),
(138, '2025-11-10', 'Kayu Bakar', '[Seeder data dede] Nov 2025 - Kayu Bakar', 88125, '2026-06-20 18:38:06', NULL),
(139, '2025-11-16', 'Upah Produksi', '[Seeder data dede] Nov 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(140, '2025-11-22', 'Transportasi', '[Seeder data dede] Nov 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL),
(141, '2025-12-02', 'Biaya Daun', '[Seeder data dede] Des 2025 - Biaya Daun', 24200000, '2026-06-20 18:38:06', NULL),
(142, '2025-12-10', 'Kayu Bakar', '[Seeder data dede] Des 2025 - Kayu Bakar', 90750, '2026-06-20 18:38:06', NULL),
(143, '2025-12-16', 'Upah Produksi', '[Seeder data dede] Des 2025 - Upah Produksi', 8000000, '2026-06-20 18:38:06', NULL),
(144, '2025-12-22', 'Transportasi', '[Seeder data dede] Des 2025 - Transportasi', 2250000, '2026-06-20 18:38:06', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int NOT NULL,
  `id_pelanggan` int NOT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `total_harga` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_pelanggan`, `tanggal_transaksi`, `total_harga`, `created_at`) VALUES
(1, 2, '2023-01-05 09:00:00', 16425000, '2026-06-20 18:38:06'),
(2, 2, '2023-01-14 13:00:00', 15750000, '2026-06-20 18:38:06'),
(3, 2, '2023-01-25 16:00:00', 15975000, '2026-06-20 18:38:06'),
(4, 2, '2023-02-05 09:00:00', 13275000, '2026-06-20 18:38:06'),
(5, 2, '2023-02-14 13:00:00', 13950000, '2026-06-20 18:38:06'),
(6, 2, '2023-02-25 16:00:00', 14400000, '2026-06-20 18:38:06'),
(7, 2, '2023-03-05 09:00:00', 19125000, '2026-06-20 18:38:06'),
(8, 2, '2023-03-14 13:00:00', 20025000, '2026-06-20 18:38:06'),
(9, 2, '2023-03-25 16:00:00', 19350000, '2026-06-20 18:38:06'),
(10, 2, '2023-04-05 09:00:00', 17550000, '2026-06-20 18:38:06'),
(11, 2, '2023-04-14 13:00:00', 17100000, '2026-06-20 18:38:06'),
(12, 2, '2023-04-25 16:00:00', 18225000, '2026-06-20 18:38:06'),
(13, 2, '2023-05-05 09:00:00', 20700000, '2026-06-20 18:38:06'),
(14, 2, '2023-05-14 13:00:00', 20475000, '2026-06-20 18:38:06'),
(15, 2, '2023-05-25 16:00:00', 20700000, '2026-06-20 18:38:06'),
(16, 2, '2023-06-05 09:00:00', 24750000, '2026-06-20 18:38:06'),
(17, 2, '2023-06-14 13:00:00', 24750000, '2026-06-20 18:38:06'),
(18, 2, '2023-06-25 16:00:00', 23850000, '2026-06-20 18:38:06'),
(19, 2, '2023-07-05 09:00:00', 21150000, '2026-06-20 18:38:06'),
(20, 2, '2023-07-14 13:00:00', 20925000, '2026-06-20 18:38:06'),
(21, 2, '2023-07-25 16:00:00', 23175000, '2026-06-20 18:38:06'),
(22, 2, '2023-08-05 09:00:00', 22950000, '2026-06-20 18:38:06'),
(23, 2, '2023-08-14 13:00:00', 22950000, '2026-06-20 18:38:06'),
(24, 2, '2023-08-25 16:00:00', 23850000, '2026-06-20 18:38:06'),
(25, 2, '2023-09-05 09:00:00', 20475000, '2026-06-20 18:38:06'),
(26, 2, '2023-09-14 13:00:00', 20700000, '2026-06-20 18:38:06'),
(27, 2, '2023-09-25 16:00:00', 20700000, '2026-06-20 18:38:06'),
(28, 2, '2023-10-05 09:00:00', 18450000, '2026-06-20 18:38:06'),
(29, 2, '2023-10-14 13:00:00', 18900000, '2026-06-20 18:38:06'),
(30, 2, '2023-10-25 16:00:00', 17775000, '2026-06-20 18:38:06'),
(31, 2, '2023-11-05 09:00:00', 19350000, '2026-06-20 18:38:06'),
(32, 2, '2023-11-14 13:00:00', 18450000, '2026-06-20 18:38:06'),
(33, 2, '2023-11-25 16:00:00', 19125000, '2026-06-20 18:38:06'),
(34, 2, '2023-12-05 09:00:00', 19350000, '2026-06-20 18:38:06'),
(35, 2, '2023-12-14 13:00:00', 19800000, '2026-06-20 18:38:06'),
(36, 2, '2023-12-25 16:00:00', 20475000, '2026-06-20 18:38:06'),
(37, 2, '2024-01-05 09:00:00', 13725000, '2026-06-20 18:38:06'),
(38, 2, '2024-01-14 13:00:00', 14400000, '2026-06-20 18:38:06'),
(39, 2, '2024-01-25 16:00:00', 14625000, '2026-06-20 18:38:06'),
(40, 2, '2024-02-05 09:00:00', 11925000, '2026-06-20 18:38:06'),
(41, 2, '2024-02-14 13:00:00', 12825000, '2026-06-20 18:38:06'),
(42, 2, '2024-02-25 16:00:00', 11700000, '2026-06-20 18:38:06'),
(43, 2, '2024-03-05 09:00:00', 18000000, '2026-06-20 18:38:06'),
(44, 2, '2024-03-14 13:00:00', 17550000, '2026-06-20 18:38:06'),
(45, 2, '2024-03-25 16:00:00', 18450000, '2026-06-20 18:38:06'),
(46, 2, '2024-04-05 09:00:00', 15975000, '2026-06-20 18:38:06'),
(47, 2, '2024-04-14 13:00:00', 15750000, '2026-06-20 18:38:06'),
(48, 2, '2024-04-25 16:00:00', 15975000, '2026-06-20 18:38:06'),
(49, 2, '2024-05-05 09:00:00', 18450000, '2026-06-20 18:38:06'),
(50, 2, '2024-05-14 13:00:00', 18450000, '2026-06-20 18:38:06'),
(51, 2, '2024-05-25 16:00:00', 17100000, '2026-06-20 18:38:06'),
(52, 2, '2024-06-05 09:00:00', 15525000, '2026-06-20 18:38:06'),
(53, 2, '2024-06-14 13:00:00', 15300000, '2026-06-20 18:38:06'),
(54, 2, '2024-06-25 16:00:00', 17550000, '2026-06-20 18:38:06'),
(55, 2, '2024-07-05 09:00:00', 20700000, '2026-06-20 18:38:06'),
(56, 2, '2024-07-14 13:00:00', 20700000, '2026-06-20 18:38:06'),
(57, 2, '2024-07-25 16:00:00', 21375000, '2026-06-20 18:38:06'),
(58, 2, '2024-08-05 09:00:00', 22050000, '2026-06-20 18:38:06'),
(59, 2, '2024-08-14 13:00:00', 22275000, '2026-06-20 18:38:06'),
(60, 2, '2024-08-25 16:00:00', 22050000, '2026-06-20 18:38:06'),
(61, 2, '2024-09-05 09:00:00', 18900000, '2026-06-20 18:38:06'),
(62, 2, '2024-09-14 13:00:00', 19350000, '2026-06-20 18:38:06'),
(63, 2, '2024-09-25 16:00:00', 18225000, '2026-06-20 18:38:06'),
(64, 2, '2024-10-05 09:00:00', 15300000, '2026-06-20 18:38:06'),
(65, 2, '2024-10-14 13:00:00', 14400000, '2026-06-20 18:38:06'),
(66, 2, '2024-10-25 16:00:00', 15300000, '2026-06-20 18:38:06'),
(67, 2, '2024-11-05 09:00:00', 20025000, '2026-06-20 18:38:06'),
(68, 2, '2024-11-14 13:00:00', 20475000, '2026-06-20 18:38:06'),
(69, 2, '2024-11-25 16:00:00', 21375000, '2026-06-20 18:38:06'),
(70, 2, '2024-12-05 09:00:00', 22275000, '2026-06-20 18:38:06'),
(71, 2, '2024-12-14 13:00:00', 22950000, '2026-06-20 18:38:06'),
(72, 2, '2024-12-25 16:00:00', 22275000, '2026-06-20 18:38:06'),
(73, 2, '2025-01-05 09:00:00', 16650000, '2026-06-20 18:38:06'),
(74, 2, '2025-01-14 13:00:00', 17550000, '2026-06-20 18:38:06'),
(75, 2, '2025-01-25 16:00:00', 16425000, '2026-06-20 18:38:06'),
(76, 2, '2025-02-05 09:00:00', 14625000, '2026-06-20 18:38:06'),
(77, 2, '2025-02-14 13:00:00', 14175000, '2026-06-20 18:38:06'),
(78, 2, '2025-02-25 16:00:00', 15075000, '2026-06-20 18:38:06'),
(79, 2, '2025-03-05 09:00:00', 19125000, '2026-06-20 18:38:06'),
(80, 2, '2025-03-14 13:00:00', 18900000, '2026-06-20 18:38:06'),
(81, 2, '2025-03-25 16:00:00', 19125000, '2026-06-20 18:38:06'),
(82, 2, '2025-04-05 09:00:00', 20925000, '2026-06-20 18:38:06'),
(83, 2, '2025-04-14 13:00:00', 20925000, '2026-06-20 18:38:06'),
(84, 2, '2025-04-25 16:00:00', 19575000, '2026-06-20 18:38:06'),
(85, 2, '2025-05-05 09:00:00', 20925000, '2026-06-20 18:38:06'),
(86, 2, '2025-05-14 13:00:00', 20700000, '2026-06-20 18:38:06'),
(87, 2, '2025-05-25 16:00:00', 22500000, '2026-06-20 18:38:06'),
(88, 2, '2025-06-05 09:00:00', 16200000, '2026-06-20 18:38:06'),
(89, 2, '2025-06-14 13:00:00', 16200000, '2026-06-20 18:38:06'),
(90, 2, '2025-06-25 16:00:00', 17100000, '2026-06-20 18:38:06'),
(91, 2, '2025-07-05 09:00:00', 21825000, '2026-06-20 18:38:06'),
(92, 2, '2025-07-14 13:00:00', 22050000, '2026-06-20 18:38:06'),
(93, 2, '2025-07-25 16:00:00', 22050000, '2026-06-20 18:38:06'),
(94, 2, '2025-08-05 09:00:00', 27225000, '2026-06-20 18:38:06'),
(95, 2, '2025-08-14 13:00:00', 27675000, '2026-06-20 18:38:06'),
(96, 2, '2025-08-25 16:00:00', 26100000, '2026-06-20 18:38:06'),
(97, 2, '2025-09-05 09:00:00', 19800000, '2026-06-20 18:38:06'),
(98, 2, '2025-09-14 13:00:00', 18900000, '2026-06-20 18:38:06'),
(99, 2, '2025-09-25 16:00:00', 19800000, '2026-06-20 18:38:06'),
(100, 2, '2025-10-05 09:00:00', 17100000, '2026-06-20 18:38:06'),
(101, 2, '2025-10-14 13:00:00', 17550000, '2026-06-20 18:38:06'),
(102, 2, '2025-10-25 16:00:00', 18225000, '2026-06-20 18:38:06'),
(103, 2, '2025-11-05 09:00:00', 17325000, '2026-06-20 18:38:06'),
(104, 2, '2025-11-14 13:00:00', 18000000, '2026-06-20 18:38:06'),
(105, 2, '2025-11-25 16:00:00', 17550000, '2026-06-20 18:38:06'),
(106, 2, '2025-12-05 09:00:00', 18000000, '2026-06-20 18:38:06'),
(107, 2, '2025-12-14 13:00:00', 17325000, '2026-06-20 18:38:06'),
(108, 2, '2025-12-25 16:00:00', 19125000, '2026-06-20 18:38:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan_online`
--

CREATE TABLE `pesanan_online` (
  `id_pesanan` int NOT NULL,
  `id_pelanggan` int NOT NULL,
  `id_penjualan` int DEFAULT NULL,
  `tanggal_pesanan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nama_penerima` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_telepon` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat_pengiriman` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `metode_pembayaran` enum('transfer_bank','cod') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer_bank',
  `status_pembayaran` enum('menunggu_pembayaran','menunggu_konfirmasi','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_pembayaran',
  `status_pengiriman` enum('menunggu_pembayaran','dikemas','dikirim','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_pembayaran',
  `total_harga` int NOT NULL,
  `bukti_transfer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `dibayar_pada` datetime DEFAULT NULL,
  `dikirim_pada` datetime DEFAULT NULL,
  `selesai_pada` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `stok` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `harga`, `stok`, `created_at`) VALUES
(1, 'Minyak Kayu Putih 60ml', 13500, 1088, '2026-06-20 18:38:06'),
(2, 'Minyak Kayu Putih 120ml', 27000, 594, '2026-06-20 18:38:06'),
(3, 'Minyak Kayu Putih 210ml', 47250, 339, '2026-06-20 18:38:06'),
(4, 'Minyak Kayu Putih 1Kg', 225000, 3133, '2026-06-20 18:38:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('pemilik','kasir','pelanggan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`, `id_pelanggan`, `created_at`) VALUES
(1, 'Pemilik Toko', 'admin', '$2y$12$ZgHEvOBUxx1DE/VtZZbkwexD02aCe/QVmq7ZtmbCIix1lNEZ.IDGa', 'pemilik', NULL, '2026-06-20 18:38:06'),
(2, 'Kasir Toko', 'kasir', '$2y$12$.FGSXgS5XBDhkDFGotWJbespJfXMaLWRgjrWKSQGQtl8NWBcNJBUO', 'kasir', NULL, '2026-06-20 18:38:06');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_penjualan` (`id_penjualan`),
  ADD KEY `fk_detail_produk` (`id_produk`);

--
-- Indeks untuk tabel `detail_pesanan_online`
--
ALTER TABLE `detail_pesanan_online`
  ADD PRIMARY KEY (`id_detail_pesanan`),
  ADD KEY `fk_detail_pesanan_online` (`id_pesanan`),
  ADD KEY `fk_detail_pesanan_produk` (`id_produk`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD KEY `fk_penjualan_pelanggan` (`id_pelanggan`);

--
-- Indeks untuk tabel `pesanan_online`
--
ALTER TABLE `pesanan_online`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `fk_pesanan_online_pelanggan` (`id_pelanggan`),
  ADD KEY `fk_pesanan_online_penjualan` (`id_penjualan`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `nama_produk` (`nama_produk`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan_online`
--
ALTER TABLE `detail_pesanan_online`
  MODIFY `id_detail_pesanan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesanan_online`
--
ALTER TABLE `pesanan_online`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `fk_detail_penjualan` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Ketidakleluasaan untuk tabel `detail_pesanan_online`
--
ALTER TABLE `detail_pesanan_online`
  ADD CONSTRAINT `fk_detail_pesanan_online` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan_online` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_pesanan_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `fk_penjualan_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);

--
-- Ketidakleluasaan untuk tabel `pesanan_online`
--
ALTER TABLE `pesanan_online`
  ADD CONSTRAINT `fk_pesanan_online_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `fk_pesanan_online_penjualan` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
