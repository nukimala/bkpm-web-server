# Dokumen Pengujian API: Blackbox Testing & Equivalence Partitioning (Acara 23)

Dokumen ini memuat rancangan, eksekusi, dan evaluasi hasil pengujian perangkat lunak (*software testing*) untuk seluruh endpoint RESTful API Sistem Informasi Akademik (SI-Akademik).

---

## 1. Metodologi Pengujian: Equivalence Partitioning

Metode pengujian yang diterapkan adalah **Blackbox Testing** dengan teknik **Equivalence Partitioning (EP)**.
Prinsip kerja EP:
- Domain masukan (*input domain*) dibagi menjadi dua kelompok partisi:
  1. **Partisi Valid (Equivalence Class Valid):** Nilai input yang sah dan diharapkan oleh sistem (misalnya NIM berupa angka unik, format string sesuai, token valid).
  2. **Partisi Tidak Valid (Equivalence Class Invalid):** Nilai input di luar spesifikasi yang diizinkan (misalnya string kosong, format salah, token palsu/kedaluwarsa, nilai duplikat).
- Pengujian dilakukan dengan mengambil perwakilan data dari masing-masing partisi untuk memastikan sistem merespons secara tepat:
  - Partisi valid menghasilkan respons sukses (`200 OK`, `201 Created`, `204 No Content`).
  - Partisi tidak valid ditangani secara elegan (*graceful failure*) dengan kode status error yang tepat (`400 Bad Request`, `401 Unauthorized`, `404 Not Found`, `422 Unprocessable Entity`).

---

## 2. Matriks Rencana & Hasil Pengujian (Test Case Matrix)

Berikut adalah daftar pengujian yang mencakup skenario dasar (TC-01 s/d TC-06) dan **Tugas Praktik Mandiri** penambahan skenario pengujian `update` dan `delete` (TC-07 s/d TC-14).

| ID Test Case | Endpoint & Action | Skenario Pengujian | Kelas Partisi | Data Uji (Input) | Hasil yang Diharapkan | Hasil Aktual | Kode HTTP | Status |
|:------------:|:-------------------|:-------------------|:--------------|:-----------------|:----------------------|:-------------|:---------:|:------:|
| **TC-01** | `POST /api/mahasiswa` | Tambah mahasiswa data lengkap dan NIM unik | Valid | `{"nim":"2601001","nama":"Ahmad Fauzi","prodi_id":1,"status":"aktif"}` | Respons sukses data berhasil dibuat | Data berhasil disimpan ke database | `201 Created` | **PASS** |
| **TC-02** | `POST /api/mahasiswa` | Tambah mahasiswa dengan NIM kosong | Invalid (Kosong) | `{"nim":"","nama":"Ahmad Fauzi","prodi_id":1,"status":"aktif"}` | Validasi gagal, error spesifik pada field nim | Respons validasi gagal, nim wajib diisi | `422 Unprocessable` | **PASS** |
| **TC-03** | `POST /api/mahasiswa` | Tambah mahasiswa dengan NIM yang sudah ada (duplikat) | Invalid (Duplikat) | `{"nim":"2401001","nama":"Budi Duplikat","prodi_id":1,"status":"aktif"}` | Validasi gagal, pesan bahwa NIM sudah digunakan | Pesan NIM 2401001 sudah digunakan | `422 Unprocessable` | **PASS** |
| **TC-04** | `POST /api/auth/login` | Login menggunakan username dan password yang benar | Valid | `{"username":"admin","password":"admin123"}` | Login berhasil, menerima token Bearer 64-hex | Token dan data user berhasil diterima | `200 OK` | **PASS** |
| **TC-05** | `POST /api/auth/login` | Login dengan password salah | Invalid (Kredensial Salah) | `{"username":"admin","password":"passwordsalah"}` | Ditolak Unauthorized | Pesan username atau password salah | `401 Unauthorized` | **PASS** |
| **TC-06** | `POST /api/mahasiswa` | Akses endpoint modifikasi data tanpa header Authorization | Invalid (Tanpa Token) | Header: *None* | Akses ditolak karena ketiadaan token | Token tidak diberikan atau format salah | `401 Unauthorized` | **PASS** |
| **TC-07** | `PUT /api/mahasiswa/{id}` | Perbarui data mahasiswa dengan parameter valid & lengkap | Valid | ID: `1`, Body: `{"nim":"2401001","nama":"Budi Santoso, S.Kom","prodi_id":1,"status":"aktif"}` | Data berhasil diperbarui | Mahasiswa berhasil diperbarui | `200 OK` | **PASS** |
| **TC-08** | `PUT /api/mahasiswa/{id}` | Perbarui mahasiswa dengan nama kosong | Invalid (Kosong) | ID: `1`, Body: `{"nim":"2401001","nama":"","prodi_id":1,"status":"aktif"}` | Validasi gagal, error nama wajib diisi | Pesan nama wajib diisi | `422 Unprocessable` | **PASS** |
| **TC-09** | `PUT /api/mahasiswa/{id}` | Perbarui data mahasiswa dengan ID tidak ada | Invalid (Boundary) | ID: `99999`, Body: `{"nim":"9999999","nama":"Non-Existent","prodi_id":1,"status":"aktif"}` | Record tidak ditemukan | Data mahasiswa tidak ditemukan | `404 Not Found` | **PASS** |
| **TC-10** | `PUT /api/mahasiswa/{id}` | Perbarui data mahasiswa tanpa token Bearer | Invalid (Tanpa Token) | ID: `1`, Header: *None* | Ditolak Unauthorized | Token tidak diberikan atau format salah | `401 Unauthorized` | **PASS** |
| **TC-11** | `DELETE /api/mahasiswa/{id}` | Hapus data mahasiswa yang valid dengan token | Valid | ID: `5`, Header: `Bearer <valid_token>` | Record berhasil dihapus | HTTP status 204 No Content | `204 No Content` | **PASS** |
| **TC-12** | `DELETE /api/mahasiswa/{id}` | Hapus data mahasiswa dengan ID tidak ada | Invalid (Boundary) | ID: `99999`, Header: `Bearer <valid_token>` | Record tidak ditemukan | Data mahasiswa tidak ditemukan | `404 Not Found` | **PASS** |
| **TC-13** | `DELETE /api/mahasiswa/{id}` | Hapus data mahasiswa tanpa token Bearer | Invalid (Tanpa Token) | ID: `1`, Header: *None* | Ditolak Unauthorized | Token tidak diberikan atau format salah | `401 Unauthorized` | **PASS** |
| **TC-14** | `DELETE /api/prodi/{id}` | Hapus program studi yang masih memiliki mahasiswa terdaftar | Invalid (Integritas Relasi) | ID: `1` (Prodi TI memiliki relasi mhs) | Ditolak karena melanggar referential integrity | Ditolak dengan pesan relasi mahasiswa | `422 Unprocessable` | **PASS** |

---

## 3. Evaluasi & Analisis Hasil Pengujian

1. **Efektivitas Partisi Ekuivalensi:** Penggunaan teknik *Equivalence Partitioning* berhasil memangkas ribuan kombinasi input acak menjadi 14 skenario terukur yang mampu menguji seluruh logika validasi bisnis (*business logic*) di `MahasiswaService` dan `ProdiService`.
2. **Kesesuaian Kode Status HTTP:** Tidak ditemukan kebocoran error `500 Internal Server Error` saat input salah diberikan. Seluruh kegagalan input tertangkap oleh lapisan Validator dan Exception Handler dengan kode HTTP `400`, `401`, `404`, atau `422`.
3. **Status Akhir Sistem:** 14 dari 14 skenario (100%) dinyatakan **PASS**, menunjukkan stabilitas dan kesiapan API untuk diintegrasikan dengan frontend.
