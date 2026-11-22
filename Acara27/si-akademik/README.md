# SI Akademik

Aplikasi sederhana berbasis **PHP Native + MVC** untuk studi kasus Workshop Sistem
Informasi Web Server (TIF330805). Aplikasi dibangun bertahap dari pertemuan ke
pertemuan dan disimpan menggunakan **Git** sejak Acara 11.

## Struktur Folder

```
si-akademik/
├── public/                # entry point (front controller) + asset
│   ├── index.php
│   └── .htaccess
├── app/
│   ├── Core/              # Controller, Router, Database, Middleware
│   ├── Controllers/       # Home, Auth, Dashboard, Mahasiswa, Prodi, Matakuliah
│   ├── Models/            # Model (objek + validasi)
│   ├── Repositories/      # akses data (PDO + prepared statement)
│   └── Views/             # template HTML (layout + partials + halaman)
├── config/                # konfigurasi (database, dst.)
├── database/              # skema SQL + seed
└── routes/                # definisi route web
```

## Cara Menjalankan

1. Salin folder ke `C:\xampp\htdocs\si-akademik` (jalankan XAMPP -> Apache + MySQL).
2. Import `database/si_akademik.sql` ke phpMyAdmin untuk membuat database dan data awal.
3. Buka `http://localhost/si-akademik/public`.
4. Login: username `admin`, password `admin123` (sementara; keamanan login ditingkatkan mulai Acara 14).

## Riwayat Pengembangan (Git)

Repositori Git diinisialisasi pada **Acara 11**. Setiap pertemuan menghasilkan
commit berisi progres pengembangan aplikasi. Lihat riwayat:

```bash
git log --oneline --graph
```

## Perintah Git Dasar

Dokumentasi lengkap ada di `docs/git-perintah-11.md`.