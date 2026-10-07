-- database/si_akademik.sql
-- Membuat database, tabel, dan data awal SI Akademik.

CREATE DATABASE IF NOT EXISTS si_akademik_a15
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;
USE si_akademik_a15;

-- Tabel Prodi
CREATE TABLE IF NOT EXISTS prodi (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    kode       VARCHAR(10)  NOT NULL,
    nama       VARCHAR(100) NOT NULL,
    ka_prodi   VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nim        VARCHAR(15)  NOT NULL UNIQUE,
    nama       VARCHAR(100) NOT NULL,
    prodi_id   INT NOT NULL,
    -- Tugas Mandiri Acara 7: menambahkan kolom status
    status     ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    alamat     TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prodi_id) REFERENCES prodi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Matakuliah
CREATE TABLE IF NOT EXISTS matakuliah (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    kode  VARCHAR(10)  NOT NULL UNIQUE,
    nama  VARCHAR(100) NOT NULL,
    sks   TINYINT NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Users (Acara 14: autentikasi berbasis database + password_hash)
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    nama       VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data awal User (password "admin123" disimpan sebagai hash bcrypt)
INSERT INTO users (username, password, nama) VALUES
('admin', '$2y$10$jZPmrz463azopmrCmZldYu1OdYZ3gq2jLqO.S/nVt/HhmDOePwrNe', 'Administrator');

-- Data awal Prodi
INSERT INTO prodi (kode, nama, ka_prodi) VALUES
('TI', 'Teknik Informatika', 'Bapak Muhammad Soleh, S.Kom., M.Cs.'),
('SI', 'Sistem Informasi',   'Ibu Dewi Mulyani, S.Kom., M.Kom.'),
('TK', 'Teknik Komputer',    'Bapak Agus Hariyanto, S.T., M.T.'),
('RPL', 'Rekayasa Perangkat Lunak', 'Bapak Rendra Soekarno, S.Kom., M.Kom.'),
('BD', 'Bisnis Digital',     'Ibu Sri Wahyuni, S.E., M.M.');

-- Data awal Mahasiswa
INSERT INTO mahasiswa (nim, nama, prodi_id, status, alamat) VALUES
('2401001', 'Budi Santoso',    1, 'aktif',    'Jl. Mastrip No. 12, Jember'),
('2401002', 'Ani Wijaya',      1, 'aktif',    'Jl. Kalimantan No. 5, Jember'),
('2402001', 'Citra Lestari',   2, 'aktif',    'Jl. Gajah Mada No. 88, Jember'),
('2501003', 'Dedi Kurniawan',  1, 'nonaktif', 'Jl. Nusa Indah No. 2, Banyuwangi'),
('2502002', 'Eka Pratiwi',     2, 'aktif',    'Jl. Veteran No. 21, Bondowoso');

-- Data awal Matakuliah
INSERT INTO matakuliah (kode, nama, sks) VALUES
('TIF330805', 'Workshop Sistem Web Server',      3),
('TIF330201', 'Pemrograman Berorientasi Objek',  3),
('TIF330402', 'Basis Data I',                    3),
('TIF330704', 'Pemrograman Web',                 3),
('TIF330903', 'Jaringan Komputer',               3);