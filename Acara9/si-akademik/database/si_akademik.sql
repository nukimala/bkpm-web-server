-- database/si_akademik.sql
-- Skema database SI Akademik (Acara 8 - PDO, Prepared Statement, Relasi, CRUD Lengkap).
--
-- Database terpisah dari project lain supaya aman untuk latihan CRUD:
-- setiap added/updated/deleted pada project ini tidak memengaruhi project lain.
--
-- Catatan relasi: satu prodi memiliki banyak mahasiswa (One-to-Many),
-- dan satu prodi memiliki banyak matakuliah (One-to-Many).

CREATE DATABASE IF NOT EXISTS si_akademik_a9
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;
USE si_akademik_a9;

-- Tabel Prodi
CREATE TABLE IF NOT EXISTS prodi (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    kode       VARCHAR(10)  NOT NULL UNIQUE,
    nama       VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Mahasiswa
-- Kolom status = Tugas Mandiri Acara 7 (ENUM aktif/cuti/lulus).
CREATE TABLE IF NOT EXISTS mahasiswa (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nim        VARCHAR(20)  NOT NULL UNIQUE,
    nama       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    prodi_id   INT NOT NULL,
    angkatan   YEAR NOT NULL,
    status     ENUM('aktif', 'cuti', 'lulus') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mahasiswa_prodi FOREIGN KEY (prodi_id)
        REFERENCES prodi(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Matakuliah
CREATE TABLE IF NOT EXISTS matakuliah (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    kode       VARCHAR(10)  NOT NULL UNIQUE,
    nama       VARCHAR(150) NOT NULL,
    sks        TINYINT NOT NULL,
    prodi_id   INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_matakuliah_prodi FOREIGN KEY (prodi_id)
        REFERENCES prodi(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data awal (seeding)
INSERT INTO prodi (kode, nama) VALUES
('TI', 'Teknik Informatika'),
('SI', 'Sistem Informasi'),
('TK', 'Teknik Komputer');

-- Mahasiswa jumlahnya dibuat lebih dari 3 supaya pagination di halaman index terlihat.
INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status) VALUES
('2401001', 'Budi Santoso',   'budi@email.com',    1, 2024, 'aktif'),
('2401002', 'Ani Wijaya',     'ani@email.com',      1, 2024, 'aktif'),
('2401003', 'Citra Lestari',  'citra@email.com',    2, 2024, 'cuti'),
('2401004', 'Dedi Kurniawan', 'dedi@email.com',     1, 2025, 'aktif'),
('2401005', 'Eka Pratiwi',    'eka@email.com',      2, 2025, 'aktif'),
('2402001', 'Fajar Nugroho',  'fajar@email.com',    3, 2024, 'lulus'),
('2402002', 'Gita Ayu',       'gita@email.com',     3, 2024, 'aktif'),
('2501001', 'Hendra Saputra', 'hendra@email.com',   1, 2025, 'aktif'),
('2501002', 'Indah Permata',  'indah@email.com',    2, 2025, 'cuti'),
('2502001', 'Joko Susilo',    'joko@email.com',     3, 2025, 'aktif'),
('2502002', 'Kartika Sari',   'kartika@email.com',  1, 2025, 'aktif'),
('2503001', 'Lina Marlina',   'lina@email.com',     2, 2025, 'aktif');

INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES
('TI101', 'Pemrograman Dasar', 3, 1),
('TI102', 'Basis Data', 3, 1),
('SI101', 'Pengantar Sistem Informasi', 2, 2),
('SI102', 'Analisis dan Perancangan Sistem', 3, 2),
('TK101', 'Jaringan Komputer', 3, 3);
