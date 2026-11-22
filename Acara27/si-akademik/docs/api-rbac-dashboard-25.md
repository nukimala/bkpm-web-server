# Catatan Pelaksanaan Praktikum: Acara 25

**Pokok Bahasan:** Authentication, Authorization dan Dashboard Admin (RBAC)  
**Nama Program:** Sistem Informasi Akademik (SI-Akademik)  
**Tahun:** 2026  

---

## 1. Ringkasan Pelaksanaan
Pada Acara 25, Sistem Informasi Akademik disempurnakan dengan mekanisme keamanan menyeluruh yang membedakan **Authentication** (siapa Anda) dan **Authorization** (apa yang boleh Anda lakukan) berbasis **Role-Based Access Control (RBAC)**:
1. **Model Pengendalian Akses (RBAC):** Tabel `users` diperluas dengan kolom `role ENUM('admin', 'user')`.
   - `admin`: Hak akses penuh membaca dan memodifikasi data (Create, Update, Delete).
   - `user`: Hak akses terbatas hanya membaca data (*Read-Only*).
2. **Backend Protection (`requireAdmin`):** Menambahkan method `requireAdmin()` pada middleware `ApiAuthMiddleware`. Endpoint pemodifikasi data (`POST`, `PUT`, `DELETE`) secara tegas menolak request dari role bukan admin dengan kode status HTTP `403 Forbidden` (`Akses ditolak, hanya admin yang diizinkan`).
3. **Portal Login & Sesi Token Klien:** Dibuat antarmuka `public/views/login.html` dan `login.js` yang memverifikasi kredensial ke `/api/auth/login` dan menyimpan token serta role pada `localStorage`.
4. **Proteksi Route Dashboard:** Dibuat proteksi halaman pada `public/views/dashboard.html` di mana akses tanpa token langsung diarahkan kembali ke `login.html`.
5. **Ringkasan Data Statistik:** Dashboard menampilkan card counter dinamis: Total Mahasiswa, Total Program Studi, dan Total Mata Kuliah.

## 2. Pemenuhan Tugas Praktik Mandiri
1. **Tombol Logout:** Disediakan tombol Logout pada navbar `dashboard.html` yang mencabut token melalui API `/api/auth/logout`, membersihkan seluruh data sesi pada `localStorage`, dan mengalihkan pengguna kembali ke `login.html`.
2. **Penyembunyian Elemen UI Sesuai Role (Defense-in-Depth):** Pada `dashboard.js`, JavaScript mendeteksi role pengguna yang login. Jika pengguna ber-role `user`, seluruh tombol tindakan manipulasi (tombol "Tambah Mahasiswa", ikon "Edit", dan ikon "Hapus") secara otomatis disembunyikan dari DOM. Jika user berusaha membypass tampilan melalui Postman/cURL, lapisan backend tetap mengamankan data dengan penolakan `403 Forbidden`.
