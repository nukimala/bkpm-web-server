# SI Akademik — Acara 6

Middleware, Auth Sederhana, dan Struktur Folder Lengkap.
BKPM Workshop SI Web Server (TIF330805) — Politeknik Negeri Jember.

## Menjalankan aplikasi

1. Letakkan folder project di document root Laragon (`C:\laragon\www`).
2. Buka `http://localhost/webserver/Acara6/si-akademik/public/`.

`BASE_PATH` dihitung otomatis dari lokasi `public/index.php`, jadi project tetap bisa dipindah folder.

## Akun demo

| Username | Password  |
|----------|-----------|
| admin    | admin123  |

Kredensial ini masih di-hardcode di `app/Controllers/AuthController.php` dan akan diganti
database + `password_hash()` di acara berikutnya.

## Rute

| Rute                | Handler                        | Middleware       |
|---------------------|--------------------------------|------------------|
| `/`                 | `HomeController@index`         | —                |
| `/login`            | `AuthController@loginForm`     | —                |
| `/auth`             | `AuthController@login`         | —                |
| `/logout`           | `AuthController@logout`        | —                |
| `/dashboard`        | `DashboardController@index`    | `AuthMiddleware` |
| `/mahasiswa`        | `MahasiswaController@index`    | `AuthMiddleware` |
| `/mahasiswa/create` | `MahasiswaController@create`   | `AuthMiddleware` |
| `/mahasiswa/{id}`   | `MahasiswaController@show`     | `AuthMiddleware` |
| `/prodi`            | `ProdiController@index`        | `AuthMiddleware` |
| `/matakuliah`       | `MatakuliahController@index`   | `AuthMiddleware` |

Route `middleware` dijalankan Router **sebelum** controller dipanggil. Akses `/dashboard`
atau `/mahasiswa` tanpa login akan di-redirect ke `/login`.

## Struktur folder

```
si-akademik/
├── app/
│   ├── Controllers/   Home, Auth, Dashboard, Mahasiswa, Prodi, Matakuliah
│   ├── Core/          Router, Controller (base), Model (base), Middleware/AuthMiddleware
│   ├── Models/        Mahasiswa
│   ├── Repositories/  (disiapkan untuk layer akses database)
│   ├── Services/      (disiapkan untuk logika bisnis)
│   └── Views/         layouts, partials, auth, mahasiswa, prodi, matakuliah
├── config/            app.php (BASE_PATH), database.php (konfigurasi koneksi)
├── public/            index.php, .htaccess, assets/css, assets/js
├── routes/            web.php
├── storage/logs/      tempat log aplikasi
├── vendor/            (untuk dependency Composer)
├── composer.json
└── .gitignore
```

Hanya isi `public/` yang boleh diakses langsung dari browser. Folder `app/`, `config/`,
`routes/`, dan `storage/` dilindungi oleh `.htaccess` sehingga tidak bisa diakses langsung.

## Tugas Mandiri

Flash message di `app/Core/Controller.php`:

- Setelah login sukses → "Selamat datang, Admin" tampil di dashboard.
- Setelah logout → "Anda telah logout" tampil di halaman login.

Pesan disimpan di session, dibaca sekali, lalu otomatis dihapus.