-- database/si_akademik.sql
-- Membuat database, tabel, dan data awal SI Akademik.


-- Tabel Prodi
CREATE TABLE IF NOT EXISTS prodi (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    kode       VARCHAR(10)  NOT NULL,
    nama       VARCHAR(100) NOT NULL,
    ka_prodi   VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Mahasiswa (Acara 26: ditambahkan kolom foto untuk upload file gambar)
CREATE TABLE IF NOT EXISTS mahasiswa (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nim        VARCHAR(15)  NOT NULL UNIQUE,
    nama       VARCHAR(100) NOT NULL,
    prodi_id   INT NOT NULL,
    -- Tugas Mandiri Acara 7: menambahkan kolom status
    status     ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    alamat     TEXT DEFAULT NULL,
    foto       VARCHAR(255) DEFAULT NULL,
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

-- Tabel Users (Acara 14: autentikasi, Acara 25: Role-Based Access Control / RBAC)
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    nama       VARCHAR(100) NOT NULL,
    role       ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Token API (Acara 19: autentikasi berbasis token Bearer untuk konsumen API)
CREATE TABLE IF NOT EXISTS api_tokens (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NOT NULL,
    token      VARCHAR(64)  NOT NULL UNIQUE,
    expires_at DATETIME     NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_api_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data awal User (Acara 25: admin & user role untuk RBAC)
INSERT INTO users (username, password, nama, role) VALUES
('admin', '$2y$10$4K0rtf8WLGOfxQtJ1aJXEOZwdQbD3HD.foblTwbRtP/hS/VHkPFDO', 'Administrator', 'admin'),
('user',  '$2y$10$Js8v3NLdon0C2pvtLHJ/eeGcSyyisJYO5J6oHCDcNESs1eTER82WO', 'Mahasiswa Pengguna', 'user');

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
