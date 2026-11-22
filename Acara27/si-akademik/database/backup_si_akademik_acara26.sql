-- -------------------------------------------------------------
-- Cadangan Basis Data (Database Backup) SI Akademik
-- Praktikum Workshop Web Server (Acara 26)
-- Format: SQL Dump
-- Waktu Pencadangan: 2026-11-20 20:00:00
-- Host: localhost    Database: si_akademik
-- -------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `si_akademik` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `si_akademik`;

-- --------------------------------------------------------
-- Struktur Tabel `prodi`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `mahasiswa`;
DROP TABLE IF EXISTS `api_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `matakuliah`;
DROP TABLE IF EXISTS `prodi`;

CREATE TABLE `prodi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `ka_prodi` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `prodi` (`id`, `kode`, `nama`, `ka_prodi`) VALUES
(1, 'TI', 'Teknik Informatika', 'Bapak Muhammad Soleh, S.Kom., M.Cs.'),
(2, 'SI', 'Sistem Informasi', 'Ibu Dewi Mulyani, S.Kom., M.Kom.'),
(3, 'TK', 'Teknik Komputer', 'Bapak Agus Hariyanto, S.T., M.T.'),
(4, 'RPL', 'Rekayasa Perangkat Lunak', 'Bapak Rendra Soekarno, S.Kom., M.Kom.'),
(5, 'BD', 'Bisnis Digital', 'Ibu Sri Wahyuni, S.E., M.M.');

-- --------------------------------------------------------
-- Struktur Tabel `mahasiswa` (dengan kolom foto Acara 26)
-- --------------------------------------------------------
CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nim` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `prodi_id` int(11) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nim` (`nim`),
  KEY `prodi_id` (`prodi_id`),
  CONSTRAINT `fk_mhs_prodi` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `prodi_id`, `status`, `alamat`, `foto`, `created_at`) VALUES
(1, '2401001', 'Budi Santoso', 1, 'aktif', 'Jl. Mastrip No. 12, Jember', NULL, '2026-10-01 08:00:00'),
(2, '2401002', 'Ani Wijaya', 1, 'aktif', 'Jl. Kalimantan No. 5, Jember', NULL, '2026-10-01 08:00:00'),
(3, '2402001', 'Citra Lestari', 2, 'aktif', 'Jl. Gajah Mada No. 88, Jember', NULL, '2026-10-01 08:00:00'),
(4, '2501003', 'Dedi Kurniawan', 1, 'nonaktif', 'Jl. Nusa Indah No. 2, Banyuwangi', NULL, '2026-10-01 08:00:00'),
(5, '2502002', 'Eka Pratiwi', 2, 'aktif', 'Jl. Veteran No. 21, Bondowoso', NULL, '2026-10-01 08:00:00');

-- --------------------------------------------------------
-- Struktur Tabel `matakuliah`
-- --------------------------------------------------------
CREATE TABLE `matakuliah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `sks` tinyint(4) NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `matakuliah` (`id`, `kode`, `nama`, `sks`) VALUES
(1, 'TIF330805', 'Workshop Sistem Web Server', 3),
(2, 'TIF330201', 'Pemrograman Berorientasi Objek', 3),
(3, 'TIF330402', 'Basis Data I', 3),
(4, 'TIF330704', 'Pemrograman Web', 3),
(5, 'TIF330903', 'Jaringan Komputer', 3);

-- --------------------------------------------------------
-- Struktur Tabel `users` (dengan role RBAC Acara 25)
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$4K0rtf8WLGOfxQtJ1aJXEOZwdQbD3HD.foblTwbRtP/hS/VHkPFDO', 'Administrator', 'admin', '2026-10-01 08:00:00'),
(2, 'user', '$2y$10$Js8v3NLdon0C2pvtLHJ/eeGcSyyisJYO5J6oHCDcNESs1eTER82WO', 'Mahasiswa Pengguna', 'user', '2026-10-01 08:00:00');

-- --------------------------------------------------------
-- Struktur Tabel `api_tokens`
-- --------------------------------------------------------
CREATE TABLE `api_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `fk_api_tokens_user` (`user_id`),
  CONSTRAINT `fk_api_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Selesai pencadangan basis data.
