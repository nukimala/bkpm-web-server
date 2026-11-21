-- ======================================================
-- Database: db_kepegawaian
-- Studi Kasus: Sistem Informasi Kepegawaian (SI-Kepegawaian)
-- Kurikulum BKPM Acara 15 - 27
-- ======================================================

CREATE DATABASE IF NOT EXISTS `db_kepegawaian`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `db_kepegawaian`;

-- --------------------------------------------------------
-- 1. Tabel: pegawai
-- Menyimpan entitas biodata pegawai dan nama berkas foto profil (Acara 17 & 26)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pegawai` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nip` VARCHAR(20) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `jabatan` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `no_hp` VARCHAR(20) NULL,
    `alamat` TEXT NULL,
    `foto` VARCHAR(255) NULL DEFAULT 'default.png',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- 2. Tabel: users
-- Menyimpan kredensial autentikasi & role RBAC (Acara 20 & 25)
-- Berelasi 1-to-1 opsional dengan tabel pegawai melalui pegawai_id
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `pegawai_id` INT NULL,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin_hr', 'pegawai') NOT NULL DEFAULT 'pegawai',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_users_pegawai`
        FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai`(`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- Data Awal (Seeding) untuk Keperluan Testing
-- --------------------------------------------------------

-- 1. Insert Data Pegawai Awal
INSERT INTO `pegawai` (`id`, `nip`, `nama`, `jabatan`, `email`, `no_hp`, `alamat`, `foto`) VALUES
(1, '198501012010011001', 'Budi Santoso, S.Kom.', 'Kepala HRD', 'admin.hr@kantor.go.id', '081234567890', 'Jl. Merdeka No. 45, Jakarta', 'default.png'),
(2, '199203152019032002', 'Siti Aminah, A.Md.', 'Staff Administrasi', 'siti.aminah@kantor.go.id', '081298765432', 'Jl. Sudirman No. 12, Jakarta', 'default.png');

-- 2. Insert Data Akun Pengguna Awal
-- Password admin_hr: admin123
-- Password pegawai1: pegawai123
INSERT INTO `users` (`id`, `pegawai_id`, `username`, `password`, `role`) VALUES
(1, 1, 'admin_hr', '$2y$10$HeTxjOTrreGJKP7tR4z6FuvQYbF771NEXmKQTsztI2U9SmZQ8LZwS', 'admin_hr'),
(2, 2, 'pegawai1', '$2y$10$07FWAeIkeo9vrbPAzQ7YKO/fhECTyytSgMk.I5EZn.umr3Gu/jBKW', 'pegawai');
