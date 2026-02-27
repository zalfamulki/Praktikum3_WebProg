-- =============================================
-- DATABASE SCHEMA: kampus
-- CORE SYSTEM - Sistem Informasi Akademik
-- =============================================

CREATE DATABASE IF NOT EXISTS kampus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kampus;

-- Tabel Mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    npm      VARCHAR(20)  NOT NULL UNIQUE,
    nama     VARCHAR(100) NOT NULL,
    jurusan  VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Dosen
CREATE TABLE IF NOT EXISTS dosen (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nidn  VARCHAR(20)  NOT NULL UNIQUE,
    nama  VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Mata Kuliah
CREATE TABLE IF NOT EXISTS matakuliah (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    kode_mk  VARCHAR(20)  NOT NULL UNIQUE,
    nama_mk  VARCHAR(100) NOT NULL,
    sks      TINYINT      NOT NULL DEFAULT 3,
    semester TINYINT      NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Nilai
CREATE TABLE IF NOT EXISTS nilai (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id  INT NOT NULL,
    mk_id         INT NOT NULL,
    skor          DECIMAL(5,2) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (mk_id)        REFERENCES matakuliah(id) ON DELETE CASCADE
);

-- =============================================
-- SAMPLE DATA
-- =============================================

INSERT INTO mahasiswa (npm, nama, jurusan) VALUES
('247006111001', 'Ahmad Fauzi',        'Informatika'),
('247006111002', 'Budi Santoso',       'Informatika'),
('247006111003', 'Citra Dewi',         'Sistem Informasi'),
('247006111004', 'Diana Putri',        'Sistem Informasi'),
('247006111005', 'Eko Prasetyo',       'Teknik Komputer'),
('247006111009', 'Zaskia Janualita',   'Informatika');

INSERT INTO dosen (nidn, nama, email) VALUES
('0101019001', 'Dr. Hendra Kusuma, M.Kom', 'hendra.kusuma@unsil.ac.id'),
('0202029002', 'Dr. Sri Wahyuni, M.T.',    'sri.wahyuni@unsil.ac.id'),
('0303039003', 'Ir. Budi Santosa, M.Eng.', 'budi.santosa@unsil.ac.id');

INSERT INTO matakuliah (kode_mk, nama_mk, sks, semester) VALUES
('IF401', 'Pemrograman Web',       3, 4),
('IF402', 'Basis Data Lanjut',     3, 4),
('IF403', 'Rekayasa Perangkat Lunak', 3, 4),
('IF301', 'Pemrograman Berorientasi Objek', 3, 3),
('IF201', 'Algoritma & Pemrograman', 3, 2);

INSERT INTO nilai (mahasiswa_id, mk_id, skor) VALUES
(1, 1, 88), (1, 2, 75), (1, 3, 90),
(2, 1, 70), (2, 2, 65), (2, 3, 80),
(3, 1, 92), (3, 2, 85), (3, 4, 78),
(4, 1, 60), (4, 2, 55), (4, 5, 72),
(5, 1, 95), (5, 3, 88), (5, 5, 91),
(6, 1, 82), (6, 2, 79), (6, 3, 85);
