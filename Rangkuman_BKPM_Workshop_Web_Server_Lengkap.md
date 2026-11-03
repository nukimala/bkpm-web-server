# Rangkuman BKPM Workshop Web Server: Dasar sampai Expert

Panduan belajar dari Acara 1 sampai 16 (PHP native, MVC, PDO, OOP, Git, Service Layer, Security, REST API) untuk mengerjakan project based learning dan menyelesaikan studi kasus nyata lain.

Catatan: bagian bertanda **\[Tambahan\]** adalah materi di luar BKPM yang saya tambahkan supaya level expert-nya utuh. Sisanya bersumber dari isi acara.

## 1. Peta Besar: Satu Alur dari Browser sampai Database

Seluruh 16 acara sebenarnya satu cerita yang makin lengkap:

```
Browser -> Apache -> Router -> Middleware -> Controller -> Service -> Repository -> MySQL
                                                                           |
Browser <- Apache <- View (HTML) / JSON  <----------------------------------+
```

Setiap acara menambah atau merapikan satu bagian dari alur ini.

| Level | Acara | Yang dikuasai |
| --- | --- | --- |
| Dasar | 1-3 | Client-server, HTTP, XAMPP, MVC, form, Bootstrap |
| Menengah | 4-8 | View/layout, OOP, routing, middleware, auth, database, PDO, CRUD |
| Lanjut | 9-10 | Dependency Injection, getter/setter, inheritance, Repository Pattern |
| Profesional | 11-14 | Git, branch, PR, Service Layer, security, exception, logging, PRG |
| Expert | 15-16 + tambahan | API, JSON, REST, Postman, lalu pola produksi |

## 2. Level Dasar (Acara 1-3)

### 2.1 Frontend, backend, client-server

- **Frontend**: apa yang dilihat dan diklik pengguna di browser (HTML = struktur, CSS = tampilan, JavaScript = interaksi).
- **Backend**: program di server yang mengurus logika bisnis, data, autentikasi, dan database (PHP, Apache, MySQL).
- Browser **tidak** menjalankan PHP. PHP dieksekusi di server, browser hanya menerima hasilnya (HTML).
- Alur singkat: URL diketik -> HTTP Request ke Apache -> Apache serahkan file `.php` ke PHP -> PHP query MySQL bila perlu -> PHP rangkai HTML -> Apache kirim HTTP Response -> browser merender.
- Kesalahan klasik: membuka PHP lewat `file:///` (tidak jalan), harus lewat `http://localhost/...`.

### 2.2 HTTP

- HTTP itu **stateless**: tiap request berdiri sendiri. Untuk "mengingat" pengguna dipakai Session.
- Request = method + URL + header + body. Response = status code + header + body.
- GET untuk mengambil data (parameter di URL, boleh di-bookmark). POST untuk mengirim data sensitif atau mengubah data (data di body).
- Status code: 200 OK, 302 redirect, 404 tidak ditemukan, 500 error server. 4xx = salah di client, 5xx = salah di server.
- Aturan emas: jangan kirim password lewat GET.

### 2.3 Tools

- **XAMPP**: Apache + MySQL + PHP + phpMyAdmin. Project diletakkan di `htdocs`.
- **Composer**: dependency manager PHP (autoloading dan library).
- **Git/GitHub**: riwayat perubahan dan penyimpanan online.

### 2.4 MVC dan Bootstrap

- **Model**: data dan aturan terkait data. **View**: tampilan saja. **Controller**: menerima request, memanggil Model, memilih View.
- Tujuannya *separation of concerns*: kode rapi, mudah diuji, mudah dikerjakan tim.
- Struktur awal: `public/index.php`, `app/Controllers`, `app/Models`, `app/Views`, `config`, `routes`.
- View tidak boleh berisi logika bisnis atau query database.
- Bootstrap 5 via CDN: `container`, `row`, `col`, `card`, `form-control`, `btn`, `table`, `alert`, `navbar`.

**Cek diri:** bisa menjelaskan kenapa `date()` di PHP menunjukkan waktu server, bukan waktu pengguna? Bisa menyebut apa yang terjadi di tiap langkah request?

## 3. Level Menengah (Acara 4-8)

### 3.1 Layout dan dasar OOP (Acara 4)

- `include` (warning bila gagal) vs `require` (fatal error). Untuk file penting pakai `require_once`.
- Layout dengan *partial*: `header.php`, `navbar.php`, `footer.php` ditulis sekali, dipakai semua halaman.
- **Class** = blueprint, **object** = instance. Visibility: `public`, `private`, `protected`.
- **Encapsulation**: sembunyikan data internal, buka lewat getter/setter.
- **Namespace** mencegah bentrok nama class (`App\Models\User` vs `Vendor\Auth\User`).

### 3.2 Routing (Acara 5)

- Routing memetakan URL + method ke `Controller@method`.
- `.htaccess` di `public/` meneruskan semua request yang bukan file nyata ke `index.php` (**front controller**), dengan `QSA` agar query string tetap ada.
- Alur front controller: ambil URL, ambil method, cari di array routes, panggil controller, kalau tidak ada tampilkan 404.
- Redirect pakai status 302 (sementara) atau 301 (permanen). Setelah `header('Location: ...')` selalu `exit` supaya kode di bawahnya tidak jalan.
- URL bersih (`/mahasiswa`) lebih rapi dan tidak membuka struktur folder.

### 3.3 Middleware dan auth (Acara 6)

- **Middleware** = lapisan antara request dan Controller: cek login, hak akses, logging, CSRF.
- **Session** menyimpan data pengguna di server (dikaitkan lewat session ID di cookie).
- `AuthMiddleware`: kalau belum login, redirect ke `/login`.
- Struktur lengkap: `app/Core` (Router, base Controller/Model, Middleware), `Services`, `Repositories`, `config`, `public` (hanya ini yang boleh diakses browser), `storage/logs`, `routes/web.php`, `.gitignore`, `composer.json`.
- Prinsip: hanya `public/` yang terbuka ke browser. `app/`, `config/`, `routes/` tidak boleh diakses langsung.

### 3.4 Database dan model (Acara 7)

- Tiga tabel: `prodi`, `mahasiswa`, `matakuliah`, dihubungkan **FOREIGN KEY** supaya data tidak yatim (mahasiswa tidak boleh punya `prodi_id` yang tidak ada).
- *Seeding*: isi data awal untuk pengujian.
- Model merepresentasikan tabel dan menyediakan method seperti `all()`.

### 3.5 PDO dan CRUD (Acara 8)

- **PDO + prepared statement** memisahkan query dan data, sehingga input berbahaya (`' OR '1'='1`) hanya dianggap data, bukan perintah SQL (mencegah **SQL Injection**).
- Gunakan parameter bernama (`:nim`) atau posisi (`?`), dan set `ERRMODE_EXCEPTION`.
- **Transaction**: beberapa query harus berhasil semua atau gagal semua.
- Relasi one-to-many dengan `JOIN` (satu prodi, banyak mahasiswa).
- CRUD standar per resource: `index`, `create`, `store`, `edit`, `update`, `destroy` (+ `show`). Operasi yang mengubah data wajib POST.
- Fitur pencarian: `LIKE` dengan prepared statement.

**Cek diri:** bisa menulis route yang dilindungi middleware dan CRUD satu resource dari nol tanpa melihat contoh?

## 4. Level Lanjut (Acara 9-10)

### 4.1 Object composition dan Dependency Injection

- Lebih baik "memiliki" object lain daripada mewarisi terlalu dalam: Controller memiliki Repository, Repository memiliki Database/PDO.
- **Constructor Injection**: dependency diberikan lewat constructor, bukan dibuat dengan `new PDO` di dalam method.
- Hasilnya: kode fleksibel, mudah diganti implementasinya, dan **testable**.
- **Getter/setter** mengontrol akses dan bisa menambahkan validasi (nama tidak boleh kosong, NIM harus angka).

### 4.2 Inheritance dan Repository Pattern

- **Inheritance**: `BaseController` berisi `view()` dan `redirect()`, lalu semua controller `extends BaseController`. Prinsip **DRY** (Don't Repeat Yourself).
- **Repository Pattern**: semua SQL hanya di Repository. Controller cukup memanggil `all()`, `find()`, `create()`, `update()`, `delete()` tanpa tahu SQL-nya.
- Manfaat: ganti DBMS atau struktur tabel cukup mengubah Repository.

**Cek diri:** buka Controller-mu. Kalau masih ada `SELECT` atau `new PDO` di dalamnya, refactor belum selesai.

## 5. Level Profesional (Acara 11-14)

### 5.1 Git dan GitHub (Acara 11)

Pola inti: **edit kode -> `git add` (pilih perubahan) -> `git commit` (simpan versi) -> `git push` (kirim ke GitHub)**.

- Perintah dasar: `git init`, `git status`, `git add .`, `git commit -m "pesan jelas"`, `git remote add origin ...`, `git push`, `git pull`, `git clone`.
- `.gitignore`: abaikan `/vendor/`, `/node_modules/`, `.env`, `/storage/logs/*`, `.DS_Store`, `Thumbs.db`. File tetap ada di komputer, hanya tidak dilacak Git.
- Pesan commit harus menjelaskan perubahan, bukan "update" atau "fix".

### 5.2 Branch, merge, conflict, PR (Acara 12)

- **Branch**: cabang untuk mengembangkan fitur tanpa mengganggu `main`.
- **Merge**: menggabungkan branch. **Conflict**: dua branch mengubah baris yang sama. Conflict bukan berarti project rusak: hapus penanda `<<<<<<<`, `=======`, `>>>>>>>`, tentukan kode akhir, lalu `git add` dan `git commit`.
- **Pull Request**: usulan menggabungkan branch ke `main` agar bisa direview. Tetap latih walau bekerja sendirian.
- Alur kerja tim: `main` selalu stabil, fitur dikerjakan di branch, masuk lewat PR.

### 5.3 Service Layer dan Flash Message (Acara 13)

- Pembagian tugas: **Controller mengatur alur -> Service mengatur logika bisnis -> Repository mengatur database.**
- Service memvalidasi (NIM wajib, NIM unik, email valid, prodi tersedia) lalu memanggil Repository. Controller jadi tipis.
- Service menerima Repository lewat constructor injection (contoh: `MahasiswaRepository` dan `ProdiRepository`).
- **Flash message** lewat `$_SESSION`: ditampilkan sekali lalu dihapus. Pesan: berhasil tambah/ubah, gagal simpan, NIM sudah terdaftar.

### 5.4 Security, exception, logging, PRG (Acara 14)

- **Validasi input**: jangan percaya data dari pengguna.
- **Prepared statement**: cegah SQL Injection.
- **Exception handling** (`try/catch`): pengguna hanya melihat pesan aman ("Data gagal disimpan"), tidak pernah detail error database.
- **Logging** ke `storage/logs/app.log` (waktu + pesan error). Jangan menulis password atau data sensitif ke log.
- **PRG (Post/Redirect/Get)**: setelah POST berhasil, redirect ke halaman GET supaya refresh tidak mengirim ulang form.
- Urutan akhir yang harus dihafal:

```
User -> Controller -> Service -> Validasi -> Repository -> MySQL
     -> (jika error) Exception -> Logging -> Flash Message -> Redirect (PRG) -> User melihat hasil
```

- Skenario uji wajib: data valid, NIM kosong, NIM duplikat, email salah, edit, hapus, refresh setelah tambah, simulasi error database, cek `app.log`, pastikan pesan error aman.

## 6. Level Expert (Acara 15-16)

### 6.1 API dan JSON (Acara 15)

- **API**: cara satu aplikasi berkomunikasi dengan aplikasi lain. **JSON**: salah satu format data yang dipakai. API dan JSON bukan hal yang sama.
- JSON = pasangan key-value, object `{ }` dan array `[ ]`.
- Di PHP: `json_encode()` (array -> JSON), `json_decode()` (JSON -> array). Kirim dengan header `Content-Type: application/json`.
- Format respons terstruktur: `{ "success": true, "message": "...", "data": [] }`.

### 6.2 RESTful API (Acara 16)

- Data dipandang sebagai **resource** (`mahasiswa`, `prodi`, `mata_kuliah`), tindakan ditentukan **HTTP method**:

| Method | Endpoint | Fungsi |
| --- | --- | --- |
| GET | `/api/mahasiswa` | Ambil semua |
| GET | `/api/mahasiswa/1` | Ambil satu |
| POST | `/api/mahasiswa` | Tambah (body JSON) |
| PUT | `/api/mahasiswa/1` | Ubah |
| DELETE | `/api/mahasiswa/1` | Hapus |

- Status code: 200 berhasil, 201 dibuat, 400 request tidak valid, 404 tidak ditemukan, 500 error server.
- **Postman** untuk menguji tanpa frontend: `Body -> raw -> JSON`. Uji juga kondisi error (ID tidak ada, NIM kosong, email tidak valid, JSON tidak lengkap).
- Rumus kompetensi akhir: **Endpoint + HTTP Method + Request -> RESTful API -> Database -> Response JSON.**

### 6.3 Yang membuat seseorang disebut expert

Expert bukan hafal lebih banyak sintaks. Bedanya:

1. Tahu **di lapisan mana** tiap tanggung jawab diletakkan, dan menolak mencampurnya.
2. Selalu memikirkan **kasus gagal** (input salah, error DB, duplikat, belum login) sebelum kasus sukses.
3. Mengerjakan dengan **Git** secara disiplin: commit kecil, branch per fitur, PR.
4. Membuat sesuatu yang bisa **diuji dan dipakai pihak lain** (API yang terdokumentasi).

## 7. Playbook: Menyelesaikan Studi Kasus Nyata Lain

Berikut kerangka yang bisa dipakai untuk kasus apa pun (perpustakaan, toko, klinik, inventaris, absensi, dll).

### Langkah 1: Pahami masalah

- Siapa penggunanya, dan peran apa saja (admin, user)?
- Data apa yang harus disimpan? Aksi apa yang bisa dilakukan?

### Langkah 2: Rancang data

- Tentukan **entitas** (jadi tabel) dan **relasi** (one-to-many lewat FOREIGN KEY).
- Contoh toko: `kategori` 1-N `produk`, `pelanggan` 1-N `pesanan`, `pesanan` 1-N `detail_pesanan` (pakai transaction saat simpan).

### Langkah 3: Rancang route

- Tiap entitas dapat CRUD standar. Tentukan mana yang butuh login.

### Langkah 4: Bangun per lapisan, urut dari bawah

1. Database + seeding
2. Repository (semua SQL, prepared statement)
3. Service (validasi + logika bisnis)
4. Controller (tipis)
5. View (Bootstrap)
6. Middleware untuk route terlindungi

### Langkah 5: Kuatkan

- Flash message, PRG, exception handling, logging.
- Daftar skenario uji (valid, kosong, duplikat, salah format, error DB).

### Langkah 6: Buka sebagai API (opsional)

- Reuse Service yang sama untuk endpoint REST. Logika bisnis tidak ditulis ulang, hanya "pintu masuk"-nya berbeda (View HTML vs JSON).

### Langkah 7: Kelola dengan Git

- Commit tiap langkah selesai, branch per fitur, PR ke `main`, tag per milestone.

### Contoh cepat: sistem peminjaman buku

| Pertanyaan | Jawaban |
| --- | --- |
| Entitas | `anggota`, `buku`, `peminjaman` |
| Relasi | `anggota` 1-N `peminjaman`, `buku` 1-N `peminjaman` |
| Aturan bisnis (Service) | Buku yang sedang dipinjam tidak boleh dipinjam lagi, maksimal 3 buku per anggota |
| Transaction | Simpan peminjaman + kurangi stok sekaligus |
| Middleware | Hanya petugas login yang boleh mencatat peminjaman |
| API | `GET /api/buku`, `POST /api/peminjaman` |

Perhatikan: struktur lapisannya sama persis dengan si-akademik, hanya nama entitas yang berbeda. Ini inti dari belajar berbasis proyek.

## 8. \[Tambahan\] Materi Lanjutan di Luar BKPM

Bagian ini bukan dari modul, tapi biasanya jadi pembeda saat project dipakai di dunia nyata.

- **Password**: simpan dengan `password_hash()` dan cek dengan `password_verify()`. Jangan pernah menyimpan password mentah (login hardcode di Acara 6 hanya untuk latihan).
- **XSS**: escape semua output dengan `htmlspecialchars()` di View.
- **CSRF**: sertakan token acak di setiap form POST dan verifikasi di middleware.
- **Session**: panggil `session_regenerate_id(true)` setelah login.
- **Konfigurasi rahasia**: simpan kredensial database di `.env` (sudah ada di `.gitignore`), bukan di kode.
- **Autoloading PSR-4** lewat Composer, agar tidak perlu `require` satu per satu.
- **Pagination dan indeks database** untuk data besar.
- **Autentikasi API**: token (misalnya Bearer token/JWT) pada header `Authorization`.
- **Dokumentasi API**: koleksi Postman atau OpenAPI.
- **Pengujian otomatis** (PHPUnit): inilah alasan Dependency Injection dan Repository Pattern dipelajari.
- **Deployment**: konfigurasi server, HTTPS, dan matikan `display_errors` di produksi.

## 9. Kesalahan Umum dan Perbaikannya

| Gejala | Penyebab | Perbaikan |
| --- | --- | --- |
| Kode PHP tampil mentah | Dibuka lewat `file:///` | Buka lewat `http://localhost/...` |
| "headers already sent" | Ada output sebelum `header()` | Panggil redirect sebelum output, dan `exit` setelahnya |
| URL bersih jadi 404 | `.htaccess` tidak aktif atau salah | Cek `mod_rewrite` dan isi `.htaccess` di `public/` |
| Data terkirim dua kali saat refresh | Tidak memakai PRG | Redirect setelah POST berhasil |
| Error database tampil ke pengguna | Tidak ada `try/catch` | Tangkap exception, log, tampilkan pesan aman |
| Controller panjang dan berantakan | Validasi dan SQL di Controller | Pindahkan ke Service dan Repository |
| Conflict Git membingungkan | Dua branch mengubah baris sama | Pilih kode akhir, hapus penanda, add + commit |
| NIM duplikat masuk | Tidak ada pengecekan | Cek `existsByNim()` di Service, tambah constraint UNIQUE |

## 10. Rencana Belajar 3 Tahap

**Tahap A: kuasai fondasi (Acara 1-8).** Bangun si-akademik sampai CRUD tiga resource berjalan. Targetmu: bisa menjelaskan setiap lapisan tanpa melihat catatan.

**Tahap B: rapikan arsitektur (Acara 9-14).** Refactor jadi DI, Repository, Service, lengkap dengan keamanan dan logging. Pakai Git dari awal: satu branch per acara, tag di akhir tiap acara.

**Tahap C: buka ke dunia luar (Acara 15-16 + tambahan).** Bungkus Service yang sama jadi REST API, uji dengan Postman. Lalu ulangi seluruh playbook di studi kasus baru tanpa melihat modul. Kalau kamu bisa melakukannya, kamu sudah melewati level belajar dan masuk level bekerja.

## 11. Checklist Akhir (cek sebelum menyebut sebuah project selesai)

- [ ] Struktur folder jelas, hanya `public/` yang terbuka ke browser
- [ ] URL bersih dengan routing dan front controller
- [ ] Route sensitif dilindungi middleware
- [ ] Semua SQL ada di Repository dan memakai prepared statement
- [ ] Validasi dan logika bisnis ada di Service
- [ ] Controller tipis, View tanpa logika bisnis
- [ ] Error ditangani, dicatat di log, pengguna hanya melihat pesan aman
- [ ] Flash message dan PRG berjalan
- [ ] Ada skenario uji tertulis dan sudah dijalankan
- [ ] Riwayat Git rapi dengan pesan commit jelas, `.gitignore` benar
- [ ] Endpoint API diuji di Postman, termasuk kasus error
- [ ] Tidak ada password atau kredensial di repository


---


## 12. Latihan Cek Pemahaman

Jawab dulu tanpa melihat catatan, baru cocokkan dengan kunci di bawahnya.

### Level Dasar (Acara 1-3)

1. Mengapa `date()` di PHP menampilkan jam server, bukan jam pengguna?
2. Mengapa membuka file PHP lewat `file:///` tidak menjalankan kodenya?
3. Apa arti "HTTP itu stateless", dan apa solusinya agar pengguna tetap "dikenali"?
4. Mengapa password tidak boleh dikirim lewat GET?
5. Sebutkan tugas Model, View, dan Controller dalam satu kalimat masing-masing.

### Level Menengah (Acara 4-8)

6. Apa beda `include` dan `require`? Mana yang dipakai untuk file penting?
7. Apa fungsi `.htaccess` di folder `public/` dan apa itu front controller?
8. Mengapa setelah `header('Location: ...')` harus ada `exit`?
9. Apa yang dilakukan middleware, dan apa yang terjadi bila pengguna belum login?
10. Jelaskan mengapa prepared statement menggagalkan input `' OR '1'='1`.
11. Kapan transaction wajib dipakai? Beri satu contoh.
12. Mengapa operasi hapus dan ubah harus memakai POST, bukan link GET?

### Level Lanjut (Acara 9-10)

13. Apa keuntungan Constructor Injection dibanding `new PDO` di dalam method?
14. Apa masalah yang diselesaikan `BaseController`? Prinsip apa yang dijaga?
15. Jika ada `SELECT` di dalam Controller, apa yang salah dan bagaimana memperbaikinya?

### Level Profesional (Acara 11-14)

16. Sebutkan empat langkah inti alur Git dari edit sampai ke GitHub.
17. Kapan conflict terjadi, dan langkah menyelesaikannya?
18. Pembagian tugas Controller, Service, dan Repository?
19. Mengapa pengguna tidak boleh melihat pesan error database asli?
20. Apa masalah yang diselesaikan PRG?

### Level Expert (Acara 15-16)

21. Apa beda API dan JSON?
22. Tulis lima endpoint REST untuk resource `mahasiswa` beserta method-nya.
23. Kapan memakai status 201, 400, 404, dan 500?
24. Apa saja kasus error yang wajib diuji di Postman?

### Kunci Jawaban Singkat

| No | Jawaban |
| --- | --- |
| 1 | PHP berjalan di server, jadi `date()` mengikuti zona waktu server. Browser hanya menerima HTML hasilnya. |
| 2 | `file:///` membaca file langsung tanpa melewati Apache dan PHP. Harus lewat `http://localhost/...`. |
| 3 | Setiap request berdiri sendiri tanpa ingatan. Solusinya Session (data di server, dikaitkan lewat session ID di cookie). |
| 4 | Data GET muncul di URL, tersimpan di riwayat browser dan log server. |
| 5 | Model: data dan aturannya. View: tampilan. Controller: menerima request, memanggil Model, memilih View. |
| 6 | `include` hanya warning bila gagal, `require` fatal error. Pakai `require_once` untuk file penting. |
| 7 | Meneruskan semua request yang bukan file nyata ke `index.php` agar routing ditangani satu pintu masuk (front controller). |
| 8 | Tanpa `exit`, kode di bawahnya tetap jalan padahal pengguna sudah dialihkan. |
| 9 | Lapisan pengecekan sebelum Controller (login, hak akses, CSRF). Belum login berarti redirect ke `/login`. |
| 10 | Query dan data dipisah. Input hanya diperlakukan sebagai nilai, bukan bagian perintah SQL. |
| 11 | Saat beberapa query harus sukses semua atau batal semua, misalnya simpan peminjaman sekaligus kurangi stok. |
| 12 | Link GET bisa terpicu tanpa sengaja (prefetch, crawler, klik salah) dan tidak bisa dilindungi token dengan baik. Perubahan data wajib lewat form POST. |
| 13 | Dependency bisa diganti dan diuji (mock), kode tidak terikat pada implementasi tertentu. |
| 14 | Kode `view()` dan `redirect()` berulang di tiap controller. Menjaga prinsip DRY. |
| 15 | SQL bukan tugas Controller. Pindahkan ke Repository. |
| 16 | Edit, `git add`, `git commit`, `git push`. |
| 17 | Dua branch mengubah baris yang sama. Buka file, pilih kode akhir, hapus penanda, `git add`, `git commit`. |
| 18 | Controller: alur. Service: validasi dan logika bisnis. Repository: akses database. |
| 19 | Detail error membuka struktur database dan celah serangan. Tampilkan pesan aman, simpan detail di log. |
| 20 | Refresh setelah POST mengirim ulang form. PRG mengalihkan ke halaman GET sehingga refresh aman. |
| 21 | API adalah cara aplikasi berkomunikasi. JSON hanyalah salah satu format datanya. |
| 22 | `GET /api/mahasiswa`, `GET /api/mahasiswa/{id}`, `POST /api/mahasiswa`, `PUT /api/mahasiswa/{id}`, `DELETE /api/mahasiswa/{id}`. |
| 23 | 201: data berhasil dibuat. 400: request tidak valid. 404: data tidak ada. 500: error server. |
| 24 | ID tidak ditemukan, NIM kosong, email tidak valid, JSON tidak lengkap, NIM duplikat. |

Skor 20 ke atas: siap lanjut ke studi kasus. Di bawah itu, ulangi bagian terkait di rangkuman.

---

## 13. Studi Kasus Terpandu: Sistem Klinik

Tujuan: menerapkan playbook bagian 7 pada kasus baru, tanpa menyalin si-akademik. Ikuti urutan langkahnya.

### Langkah 1: Pahami masalah

- Pengguna: **petugas pendaftaran** (login) dan **dokter** (hanya melihat jadwal).
- Data: pasien, dokter, kunjungan.
- Aksi: daftar pasien baru, buat kunjungan, lihat riwayat pasien, batalkan kunjungan.

### Langkah 2: Rancang data

| Entitas | Relasi |
| --- | --- |
| `poli` (nama poli) | 1-N `dokter` |
| `dokter` | 1-N `kunjungan` |
| `pasien` | 1-N `kunjungan` |
| `kunjungan` | milik satu pasien dan satu dokter |

```sql
CREATE TABLE poli (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL
);

CREATE TABLE dokter (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  poli_id INT NOT NULL,
  FOREIGN KEY (poli_id) REFERENCES poli(id)
);

CREATE TABLE pasien (
  id INT AUTO_INCREMENT PRIMARY KEY,
  no_rm VARCHAR(20) NOT NULL UNIQUE,
  nama VARCHAR(100) NOT NULL,
  tgl_lahir DATE NOT NULL,
  telepon VARCHAR(20)
);

CREATE TABLE kunjungan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pasien_id INT NOT NULL,
  dokter_id INT NOT NULL,
  tanggal DATE NOT NULL,
  keluhan TEXT,
  status ENUM('menunggu','selesai','batal') NOT NULL DEFAULT 'menunggu',
  FOREIGN KEY (pasien_id) REFERENCES pasien(id),
  FOREIGN KEY (dokter_id) REFERENCES dokter(id)
);
```

Isi seeding minimal: 2 poli, 3 dokter, 5 pasien.

### Langkah 3: Rancang route

| Method | URL | Controller@method | Middleware |
| --- | --- | --- | --- |
| GET | `/login` | AuthController@showLogin | - |
| POST | `/login` | AuthController@login | - |
| GET | `/pasien` | PasienController@index | Auth |
| GET | `/pasien/create` | PasienController@create | Auth |
| POST | `/pasien/store` | PasienController@store | Auth |
| GET | `/pasien/edit/{id}` | PasienController@edit | Auth |
| POST | `/pasien/update/{id}` | PasienController@update | Auth |
| POST | `/pasien/delete/{id}` | PasienController@destroy | Auth |
| GET | `/kunjungan` | KunjunganController@index | Auth |
| POST | `/kunjungan/store` | KunjunganController@store | Auth |
| POST | `/kunjungan/batal/{id}` | KunjunganController@batal | Auth |

### Langkah 4: Bangun dari bawah ke atas

**4a. Repository (semua SQL di sini)**

```php
<?php
namespace App\Repositories;

use PDO;

class PasienRepository
{
    public function __construct(private PDO $db) {}

    public function all(): array
    {
        return $this->db->query('SELECT * FROM pasien ORDER BY nama')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM pasien WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function existsByNoRm(string $noRm): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM pasien WHERE no_rm = :no_rm');
        $stmt->execute(['no_rm' => $noRm]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO pasien (no_rm, nama, tgl_lahir, telepon)
             VALUES (:no_rm, :nama, :tgl_lahir, :telepon)'
        );
        $stmt->execute($data);
        return (int) $this->db->lastInsertId();
    }
}
```

**4b. Service (aturan bisnis dan validasi)**

Aturan untuk kunjungan:

- Pasien dan dokter harus ada.
- Tanggal tidak boleh di masa lalu.
- Satu pasien tidak boleh punya dua kunjungan `menunggu` pada dokter dan tanggal yang sama.
- Kunjungan yang sudah `selesai` tidak boleh dibatalkan.

```php
<?php
namespace App\Services;

use App\Repositories\PasienRepository;
use InvalidArgumentException;

class PasienService
{
    public function __construct(private PasienRepository $pasien) {}

    public function tambah(array $input): int
    {
        $noRm = trim($input['no_rm'] ?? '');
        $nama = trim($input['nama'] ?? '');

        if ($noRm === '' || $nama === '') {
            throw new InvalidArgumentException('No. RM dan nama wajib diisi.');
        }
        if ($this->pasien->existsByNoRm($noRm)) {
            throw new InvalidArgumentException('No. RM sudah terdaftar.');
        }

        return $this->pasien->create([
            'no_rm'     => $noRm,
            'nama'      => $nama,
            'tgl_lahir' => $input['tgl_lahir'] ?? '',
            'telepon'   => $input['telepon'] ?? null,
        ]);
    }
}
```

**4c. Controller (tipis, memakai PRG dan flash message)**

```php
public function store(): void
{
    try {
        $this->service->tambah($_POST);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Pasien berhasil ditambahkan.'];
    } catch (\InvalidArgumentException $e) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => $e->getMessage()];
    } catch (\Throwable $e) {
        error_log('[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../../storage/logs/app.log');
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Data gagal disimpan.'];
    }
    $this->redirect('/pasien');
}
```

Perhatikan: pesan validasi boleh tampil ke pengguna karena memang ditulis untuk pengguna. Pesan dari exception database tidak boleh.

**4d. View**: tabel Bootstrap, form dengan `htmlspecialchars()` pada setiap output.

**4e. Middleware**: pasang `AuthMiddleware` pada semua route kecuali `/login`.

### Langkah 5: Kuatkan, lalu uji

Skenario uji tertulis (isi kolom hasil sendiri):

| No | Skenario | Hasil yang diharapkan | Lulus? |
| --- | --- | --- | --- |
| 1 | Tambah pasien data valid | Flash sukses, data muncul | |
| 2 | No. RM kosong | Flash gagal, tidak tersimpan | |
| 3 | No. RM duplikat | Flash "sudah terdaftar" | |
| 4 | Refresh setelah tambah | Tidak ada data ganda (PRG) | |
| 5 | Buat kunjungan tanggal kemarin | Ditolak Service | |
| 6 | Kunjungan ganda dokter dan tanggal sama | Ditolak Service | |
| 7 | Batalkan kunjungan berstatus selesai | Ditolak Service | |
| 8 | Akses `/pasien` tanpa login | Redirect ke `/login` | |
| 9 | Simulasi database mati | Pesan aman, detail di `app.log` | |
| 10 | Input `<script>alert(1)</script>` pada nama | Tampil sebagai teks, tidak dieksekusi | |

### Langkah 6: Buka sebagai API

Reuse `PasienService` yang sama. Hanya controller-nya berbeda:

```php
public function store(): void
{
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $this->json(['success' => false, 'message' => 'JSON tidak valid.'], 400);
        return;
    }
    try {
        $id = $this->service->tambah($input);
        $this->json(['success' => true, 'message' => 'Pasien dibuat.', 'data' => ['id' => $id]], 201);
    } catch (\InvalidArgumentException $e) {
        $this->json(['success' => false, 'message' => $e->getMessage()], 400);
    } catch (\Throwable $e) {
        // tulis ke log, lalu:
        $this->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
    }
}
```

Di `BaseController`, tambahkan helper:

```php
protected function json(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($payload);
}
```

### Langkah 7: Git

```
main
 ├─ feature/db-seeding
 ├─ feature/pasien-crud
 ├─ feature/kunjungan-service
 ├─ feature/auth-middleware
 └─ feature/rest-api
```

Satu branch per fitur, commit kecil dengan pesan jelas (misalnya `Tambah validasi No. RM unik di PasienService`), lalu PR ke `main`. Beri tag di tiap milestone (`v0.1-crud`, `v0.2-service`, `v1.0-api`).

---

## 14. Cheat Sheet Kode

### PDO

```php
$pdo = new PDO(
    'mysql:host=localhost;dbname=klinik;charset=utf8mb4',
    $user, $pass,
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);
```

### Transaction

```php
try {
    $pdo->beginTransaction();
    // query 1, query 2, ...
    $pdo->commit();
} catch (\Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $e;
}
```

### Pencarian dengan LIKE

```php
$stmt = $pdo->prepare('SELECT * FROM pasien WHERE nama LIKE :q');
$stmt->execute(['q' => '%' . $keyword . '%']);
```

### Flash message

```php
// set
$_SESSION['flash'] = ['type' => 'success', 'msg' => 'Berhasil.'];

// tampil sekali, lalu hapus (di layout)
if (!empty($_SESSION['flash'])) {
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    echo '<div class="alert alert-' . htmlspecialchars($f['type']) . '">'
       . htmlspecialchars($f['msg']) . '</div>';
}
```

### Middleware auth

```php
class AuthMiddleware
{
    public function handle(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
}
```

### Login aman (tambahan di luar BKPM)

```php
// simpan saat registrasi
$hash = password_hash($password, PASSWORD_DEFAULT);

// cek saat login
if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user'] = ['id' => $user['id'], 'nama' => $user['nama']];
}
```

### CSRF token (tambahan di luar BKPM)

```php
// buat token (sekali per sesi)
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));

// di form
echo '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';

// verifikasi di middleware untuk setiap POST
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(419);
    exit('Token tidak valid.');
}
```

### Perintah Git yang paling sering dipakai

```bash
git switch -c feature/nama-fitur      # buat dan pindah branch
git add .
git commit -m "Pesan yang menjelaskan perubahan"
git push -u origin feature/nama-fitur
git switch main && git pull           # sinkron sebelum merge
git merge feature/nama-fitur
git tag v0.1-crud && git push --tags
git log --oneline --graph             # lihat riwayat
```

### Postman: uji endpoint POST

```
Method : POST
URL    : http://localhost/klinik/public/api/pasien
Body   : raw -> JSON
{
  "no_rm": "RM-001",
  "nama": "Budi Santoso",
  "tgl_lahir": "1998-04-12",
  "telepon": "081234567890"
}
Harapan: status 201 dan body {"success": true, ...}
```

---

## 15. Tantangan Mandiri (Pilih Satu)

Kerjakan dengan playbook yang sama, tanpa melihat studi kasus klinik.

| Tantangan | Entitas | Aturan bisnis untuk Service | Tingkat |
| --- | --- | --- | --- |
| Toko online sederhana | `kategori`, `produk`, `pelanggan`, `pesanan`, `detail_pesanan` | Stok tidak boleh negatif, total dihitung di server, simpan pesanan + kurangi stok dalam satu transaction | Sedang |
| Absensi kuliah | `kelas`, `mahasiswa`, `pertemuan`, `absensi` | Satu mahasiswa hanya satu absensi per pertemuan, batas waktu absen | Sedang |
| Inventaris lab | `ruang`, `barang`, `peminjaman_barang` | Barang yang sedang dipinjam tidak bisa dipinjam, wajib ada tanggal kembali | Mudah |
| Perpustakaan | `anggota`, `buku`, `peminjaman` | Maksimal 3 buku per anggota, denda keterlambatan | Sedang |

Kriteria lulus: semua item pada checklist akhir (bagian 11) terpenuhi.

---

## 16. Roadmap Setelah Acara 16

Urutan yang disarankan, dari yang paling berdampak:

1. **Keamanan nyata**: `password_hash`, CSRF, escape output, `.env` untuk kredensial.
2. **Pengujian otomatis**: PHPUnit untuk Service. Gunakan mock Repository (inilah gunanya DI).
3. **Composer PSR-4**: autoloading rapi, lalu kenali library populer (misalnya `vlucas/phpdotenv`, `monolog/monolog`).
4. **Pagination dan indeks database**: wajib begitu data lebih dari ratusan baris.
5. **Autentikasi API**: Bearer token atau JWT, plus batas laju request (rate limiting).
6. **Framework**: pelajari Laravel setelah paham fondasinya. Router, middleware, Service, dan Repository di Laravel akan terasa sebagai versi lengkap dari yang sudah Anda tulis sendiri.
7. **Deployment**: VPS atau hosting, HTTPS, `display_errors` dimatikan, backup database.
8. **Dokumentasi API**: ekspor koleksi Postman atau tulis OpenAPI.

Pegangan terakhir: kalau bingung meletakkan sebuah kode, tanyakan **"ini tugas siapa?"** (alur, aturan bisnis, atau database) lalu letakkan di lapisan yang sesuai.
