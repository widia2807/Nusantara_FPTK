-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Nov 2025 pada 05.04
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_pengajuan_karyawan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bussiness`
--

CREATE TABLE `bussiness` (
  `HO` int(11) NOT NULL,
  `Cabang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cabang`
--

CREATE TABLE `cabang` (
  `id_cabang` int(11) NOT NULL,
  `nama_cabang` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cabang`
--

INSERT INTO `cabang` (`id_cabang`, `nama_cabang`, `lokasi`) VALUES
(1, 'BJM1', 'Banjarmasin'),
(2, 'BJM2', ''),
(3, 'HO-MTH', 'Jakarta Selatan'),
(4, 'HONDA-BKS', 'Bekasi'),
(5, 'HONDA-BPP', 'Balikpapan'),
(6, 'HONDA-MTH', 'Jakarta Selatan'),
(7, 'HONDA-SMD', 'Samarinda'),
(8, 'NCI-MTH', 'Jakarta Selatan'),
(9, 'PBNI-MTH', 'Jakarta Selatan'),
(10, 'RE-ANTASARI', 'Jakarta Selatan'),
(11, 'RE-BALI', 'Bali'),
(12, 'RE-BDG', 'Bandung'),
(13, 'RE-BEKASI', 'Bekasi'),
(14, 'RE-BOGOR', 'Bogor'),
(15, 'RE-DEPOK', 'Depok'),
(16, 'RE-JKT', 'Jakarta Selatan'),
(17, 'RE-MAKASSAR', 'Makassar'),
(18, 'RE-MEDAN', 'Medan'),
(19, 'RE-MTH', 'Jakarta Selatan'),
(20, 'RE-SERPONG', 'Tangerang Selatan'),
(21, 'RE-SURABAYA', 'Surabaya'),
(22, 'RE-YOGJAKARTA', 'Yogyakarta'),
(23, 'SCOMADI-BALI', 'Bali'),
(24, 'SCOMADI-BDG', 'Bandung'),
(25, 'SCOMADI-BKS', 'Bekasi'),
(26, 'SCOMADI-MDN', 'Medan'),
(27, 'SCOMADI-MKS', 'Makassar'),
(28, 'SCOMADI-MTH', 'Jakarta Selatan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` int(11) NOT NULL,
  `nama_divisi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`) VALUES
(1, 'ACC-AUDIT'),
(2, 'ACCOUNTING'),
(3, 'ADMINISTRASI'),
(4, 'AFS-HONDA'),
(5, 'AFS-PART'),
(7, 'AFS-SERVICE'),
(8, 'AFTERSALES'),
(9, 'CLEANING'),
(10, 'DRIVER'),
(11, 'FINANCE'),
(12, 'GA-MAINTENANCE'),
(13, 'HR'),
(14, 'IMPORTASI'),
(15, 'IR'),
(16, 'IT'),
(17, 'MKT'),
(18, 'OD'),
(19, 'PAYROLL'),
(20, 'PERSONALIA'),
(21, 'PROJECT'),
(22, 'RECRUITMENT'),
(23, 'SECURITY'),
(24, 'SLS'),
(25, 'TAX'),
(26, 'TRAINING'),
(39, 'SECURITY');

-- --------------------------------------------------------

--
-- Struktur dari tabel `history`
--

CREATE TABLE `history` (
  `id_history` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `role_user` enum('HR','Management','Rekrutmen') NOT NULL,
  `action` enum('Approve','Reject','Review','Finish') NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-09-08-000000', 'App\\Database\\Migrations\\CreateUser', 'default', 'App', 1757307154, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id_pengajuan` int(11) NOT NULL,
  `id_user_divisi` int(11) NOT NULL,
  `id_divisi` int(11) NOT NULL,
  `id_posisi` int(11) NOT NULL,
  `id_cabang` int(11) NOT NULL,
  `jumlah_karyawan` int(11) NOT NULL,
  `job_post_number` varchar(50) NOT NULL,
  `tipe_pekerjaan` enum('Intern','Kontrak','Tetap','Freelance') NOT NULL,
  `range_umur` varchar(20) DEFAULT NULL,
  `tempat_kerja` varchar(100) DEFAULT NULL,
  `request_type` enum('Penambahan','Pergantian') NOT NULL DEFAULT 'Penambahan',
  `replace_employee_name` varchar(100) DEFAULT NULL,
  `replace_employee_id` varchar(50) DEFAULT NULL,
  `replace_reason` varchar(200) DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `kualifikasi` text DEFAULT NULL,
  `status_hr` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `status_management` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `status_rekrutmen` enum('Pending','Selesai') DEFAULT 'Pending',
  `archived` tinyint(1) DEFAULT 0,
  `needs_hr_check` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan`
--

INSERT INTO `pengajuan` (`id_pengajuan`, `id_user_divisi`, `id_divisi`, `id_posisi`, `id_cabang`, `jumlah_karyawan`, `job_post_number`, `tipe_pekerjaan`, `range_umur`, `tempat_kerja`, `request_type`, `replace_employee_name`, `replace_employee_id`, `replace_reason`, `effective_date`, `kualifikasi`, `status_hr`, `status_management`, `status_rekrutmen`, `archived`, `needs_hr_check`, `created_at`) VALUES
(30, 26, 2, 10, 3, 2, 'JP/HO/2025.11/0001', 'Kontrak', '22-25 tahun', 'Jakarta Selatan', 'Penambahan', NULL, NULL, NULL, NULL, '<ul><li><u>okay</u></li></ul>', 'Pending', 'Pending', 'Pending', 0, 0, '2025-11-21 10:34:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `posisi`
--

CREATE TABLE `posisi` (
  `id_posisi` int(11) NOT NULL,
  `id_divisi` int(11) NOT NULL,
  `nama_posisi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `posisi`
--

INSERT INTO `posisi` (`id_posisi`, `id_divisi`, `nama_posisi`) VALUES
(1, 16, 'Backend Developer'),
(2, 16, 'Frontend Developer'),
(3, 16, 'IT Support'),
(6, 2, 'STAFF ACCOUNTING'),
(7, 24, 'SALES CONSULTANT'),
(8, 11, 'STAFF FINANCE'),
(9, 14, 'ADMIN IMPORTASI'),
(10, 2, 'Temporary- STAFF ACCOUNTING');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rangegaji`
--

CREATE TABLE `rangegaji` (
  `id_gaji` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `min_gaji` decimal(12,2) NOT NULL,
  `max_gaji` decimal(12,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('HR','Management','Rekrutmen','Divisi') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `full_name`, `password`, `role`, `is_active`, `created_at`, `profile_photo`) VALUES
(21, 'admin@gmail.com', 'Administrator', '$2y$10$Yb46TuLFYqsPvnCkaNw1YOmhkmsHWDikNxBGUa7UnUDMx8/EnhZ7a', 'HR', 1, '2025-09-28 22:28:30', 'user_21_1763696216.jpg'),
(25, 'rekrutmen@gmail.com', 'Rekrutmen', '$2y$10$i9p7DDtyNV7siSNedh2jse/1giIgVY.5j5DvhIvpnFtkhNkJuCQ8q', 'Rekrutmen', 1, '2025-11-21 10:17:20', 'user_25_1763695108.jpg'),
(26, 'accounting@gmail.com', 'Accounting', '$2y$10$uhopedu0c2upN1pPflbWdeOv0HmAULlp15fVmTrmhzyoeXNG7VwyC', 'Divisi', 1, '2025-11-21 10:31:04', 'user_26_1763696429.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expired_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`id_cabang`);

--
-- Indeks untuk tabel `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indeks untuk tabel `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `id_pengajuan` (`id_pengajuan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `id_user_divisi` (`id_user_divisi`),
  ADD KEY `id_divisi` (`id_divisi`),
  ADD KEY `id_posisi` (`id_posisi`),
  ADD KEY `id_cabang` (`id_cabang`);

--
-- Indeks untuk tabel `posisi`
--
ALTER TABLE `posisi`
  ADD PRIMARY KEY (`id_posisi`),
  ADD KEY `id_divisi` (`id_divisi`);

--
-- Indeks untuk tabel `rangegaji`
--
ALTER TABLE `rangegaji`
  ADD PRIMARY KEY (`id_gaji`),
  ADD KEY `id_pengajuan` (`id_pengajuan`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cabang`
--
ALTER TABLE `cabang`
  MODIFY `id_cabang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `history`
--
ALTER TABLE `history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `posisi`
--
ALTER TABLE `posisi`
  MODIFY `id_posisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `rangegaji`
--
ALTER TABLE `rangegaji`
  MODIFY `id_gaji` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id_pengajuan`) ON DELETE CASCADE,
  ADD CONSTRAINT `history_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `pengajuan_ibfk_1` FOREIGN KEY (`id_user_divisi`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `pengajuan_ibfk_2` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`),
  ADD CONSTRAINT `pengajuan_ibfk_3` FOREIGN KEY (`id_posisi`) REFERENCES `posisi` (`id_posisi`),
  ADD CONSTRAINT `pengajuan_ibfk_4` FOREIGN KEY (`id_cabang`) REFERENCES `cabang` (`id_cabang`);

--
-- Ketidakleluasaan untuk tabel `posisi`
--
ALTER TABLE `posisi`
  ADD CONSTRAINT `posisi_ibfk_1` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rangegaji`
--
ALTER TABLE `rangegaji`
  ADD CONSTRAINT `rangegaji_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id_pengajuan`) ON DELETE CASCADE,
  ADD CONSTRAINT `rangegaji_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
