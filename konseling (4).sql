-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Nov 2026 pada 02.14
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
-- Database: `konseling`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `nama`, `password`) VALUES
(1, 'admin', 'Administrator', '$2y$12$UvHz1vOYo9xJLukcqMaOpemuHBaGHfEUCjoKUK7vDo5N.CmryG0.e');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru_bk`
--

CREATE TABLE `guru_bk` (
  `id_guru` int(10) UNSIGNED NOT NULL,
  `nip` varchar(20) NOT NULL COMMENT 'Nomor induk pegawai',
  `nama` varchar(100) NOT NULL COMMENT 'Nama lengkap',
  `password` varchar(255) NOT NULL COMMENT 'Untuk login (hash bcrypt)',
  `foto` varchar(255) DEFAULT NULL COMMENT 'Foto guru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `guru_bk`
--

INSERT INTO `guru_bk` (`id_guru`, `nip`, `nama`, `password`, `foto`) VALUES
(1, '198001012005011001', 'Pak Hanafi', '$2y$12$7OznCZ9ji2fvAA1x0gf1heLWqTGVKyjvnwEnn71AwRo.1uem05xv.', NULL),
(2, '198203152006041002', 'Pak Desnya', '$2y$12$IMzvTItiyqKOWRtiNJmaBuBciHrNjXfBeLO926YpELlyL8uxisPau', NULL),
(3, '198507202009032003', 'Bu April', '$2y$12$jaP43Df8F8aIBWTDWDBc0eAkSV5mzwUULJQqL1nDKBFkuyYNWJR4W', NULL),
(4, '198809102012011004', 'Pak Alam', '$2y$12$5MSxG/VrzJi4G0PwykEYgukgsDP.SG510ozIl0HqEmjPon73swtVe', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `konseling`
--

CREATE TABLE `konseling` (
  `id_konseling` int(10) UNSIGNED NOT NULL,
  `id_siswa` int(10) UNSIGNED NOT NULL COMMENT 'FK -> siswa.id_siswa',
  `id_guru` int(10) UNSIGNED NOT NULL COMMENT 'FK -> guru_bk.id_guru',
  `masalah` text NOT NULL COMMENT 'Permasalahan siswa',
  `solusi` text DEFAULT NULL COMMENT 'Rekomendasi / solusi',
  `tanggal` date NOT NULL COMMENT 'Tanggal konseling',
  `status` enum('terjadwal','selesai','batal') NOT NULL DEFAULT 'terjadwal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_11_10_000000_create_tabel_bk', 1),
(2, '0001_01_01_000000_create_users_table', 2),
(3, '0001_01_01_000001_create_cache_table', 2),
(4, '0001_01_01_000002_create_jobs_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_konseling`
--

CREATE TABLE `riwayat_konseling` (
  `id_riwayat` int(10) UNSIGNED NOT NULL,
  `id_konseling` int(10) UNSIGNED NOT NULL COMMENT 'FK -> konseling.id_konseling',
  `tanggal` date NOT NULL COMMENT 'Tanggal update',
  `catatan` text NOT NULL COMMENT 'Catatan lanjutan dari guru BK',
  `guru` int(10) UNSIGNED NOT NULL COMMENT 'FK -> guru_bk.id_guru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `nis` varchar(20) NOT NULL COMMENT 'Nomor induk siswa',
  `nama` varchar(100) NOT NULL COMMENT 'Nama lengkap',
  `kelas` varchar(20) NOT NULL COMMENT 'Contoh: X RPL 1',
  `jurusan` varchar(50) NOT NULL COMMENT 'Jurusan siswa',
  `password` varchar(255) NOT NULL COMMENT 'Untuk login (hash bcrypt)',
  `foto` varchar(255) DEFAULT NULL COMMENT 'Foto siswa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nama`, `kelas`, `jurusan`, `password`, `foto`) VALUES
(1, '2425001', 'Contoh Siswa Satu', 'X RPL 1', 'Rekayasa Perangkat Lunak', '$2y$12$Gt.0LKD2jEdsi721wbJKAOv.O7Y67y6.EuddY.Zvq4YYt4XXayjzm', NULL),
(2, '2425002', 'Contoh Siswa Dua', 'XI TKJ 2', 'Teknik Komputer dan Jaringan', '$2y$12$Gt.0LKD2jEdsi721wbJKAOv.O7Y67y6.EuddY.Zvq4YYt4XXayjzm', NULL),
(4, '0083776783', 'Islamezra Ramadhan', 'XII PPLG 1', 'PPLG', '$2y$12$Gt.0LKD2jEdsi721wbJKAOv.O7Y67y6.EuddY.Zvq4YYt4XXayjzm', NULL),
(5, '2425003', 'Siswa Baru', 'X RPL 1', 'Rekayasa Perangkat Lunak', '$2y$12$Gt.0LKD2jEdsi721wbJKAOv.O7Y67y6.EuddY.Zvq4YYt4XXayjzm', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `admin_username_unique` (`username`);

--
-- Indeks untuk tabel `guru_bk`
--
ALTER TABLE `guru_bk`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `guru_bk_nip_unique` (`nip`);

--
-- Indeks untuk tabel `konseling`
--
ALTER TABLE `konseling`
  ADD PRIMARY KEY (`id_konseling`),
  ADD KEY `konseling_id_siswa_foreign` (`id_siswa`),
  ADD KEY `konseling_id_guru_foreign` (`id_guru`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `riwayat_konseling`
--
ALTER TABLE `riwayat_konseling`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `riwayat_konseling_id_konseling_foreign` (`id_konseling`),
  ADD KEY `riwayat_konseling_guru_foreign` (`guru`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `siswa_nis_unique` (`nis`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `guru_bk`
--
ALTER TABLE `guru_bk`
  MODIFY `id_guru` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `konseling`
--
ALTER TABLE `konseling`
  MODIFY `id_konseling` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `riwayat_konseling`
--
ALTER TABLE `riwayat_konseling`
  MODIFY `id_riwayat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `konseling`
--
ALTER TABLE `konseling`
  ADD CONSTRAINT `konseling_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru_bk` (`id_guru`),
  ADD CONSTRAINT `konseling_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_konseling`
--
ALTER TABLE `riwayat_konseling`
  ADD CONSTRAINT `riwayat_konseling_guru_foreign` FOREIGN KEY (`guru`) REFERENCES `guru_bk` (`id_guru`),
  ADD CONSTRAINT `riwayat_konseling_id_konseling_foreign` FOREIGN KEY (`id_konseling`) REFERENCES `konseling` (`id_konseling`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
