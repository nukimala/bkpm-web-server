# Dokumentasi Lengkap RESTful API SI-Akademik (Acara 21)

Dokumentasi resmi seluruh endpoint antarmuka pemrograman aplikasi (RESTful API) Sistem Informasi Akademik berbasis PHP Native MVC, Service Layer, Repository Pattern, dan Token Bearer Authentication.

---

## 1. Tabel Ringkasan Endpoint & Controller (Tugas Praktik Mandiri)

Tabel berikut menyajikan pemetaan komprehensif seluruh endpoint API, HTTP Method, Controller & Action penanganannya, status proteksi autentikasi, serta deskripsi fungsional.

| No | HTTP Method | Endpoint URI | Controller & Action | Autentikasi | Deskripsi Fungsional |
|:--:|:-----------:|:-------------|:--------------------|:-----------:|:---------------------|
| 1  | `GET`       | `/api` | `ApiController@info` | Publik | Katalog & spesifikasi ringkas seluruh endpoint |
| 2  | `POST`      | `/api/auth/login` | `Api\AuthApiController@login` | Publik | Otentikasi kredensial pengguna & penerbitan Bearer Token (24 jam) |
| 3  | `POST`      | `/api/auth/logout` | `Api\AuthApiController@logout` | Bearer Token | Pencabutan (revokasi) Bearer token dari database |
| 4  | `GET`       | `/api/mahasiswa` | `Api\MahasiswaApiController@index` | Publik | Mengambil daftar seluruh mahasiswa (dukungan query `?search=`) |
| 5  | `GET`       | `/api/mahasiswa/{id}` | `Api\MahasiswaApiController@show` | Publik | Mengambil detail satu mahasiswa berdasarkan ID |
| 6  | `POST`      | `/api/mahasiswa` | `Api\MahasiswaApiController@store` | Bearer Token | Menambahkan data mahasiswa baru dengan validasi ketat |
| 7  | `PUT`       | `/api/mahasiswa/{id}` | `Api\MahasiswaApiController@update` | Bearer Token | Memperbarui data mahasiswa berdasarkan ID |
| 8  | `DELETE`    | `/api/mahasiswa/{id}` | `Api\MahasiswaApiController@delete` | Bearer Token | Menghapus record mahasiswa berdasarkan ID |
| 9  | `GET`       | `/api/prodi` | `Api\ProdiApiController@index` | Publik | Mengambil daftar seluruh Program Studi |
| 10 | `GET`       | `/api/prodi/{id}` | `Api\ProdiApiController@show` | Publik | Mengambil detail Program Studi berdasarkan ID |
| 11 | `POST`      | `/api/prodi` | `Api\ProdiApiController@store` | Bearer Token | Menambahkan record Program Studi baru |
| 12 | `PUT`       | `/api/prodi/{id}` | `Api\ProdiApiController@update` | Bearer Token | Memperbarui record Program Studi berdasarkan ID |
| 13 | `DELETE`    | `/api/prodi/{id}` | `Api\ProdiApiController@delete` | Bearer Token | Menghapus Program Studi (ditolak 422 bila ada relasi mahasiswa) |
| 14 | `GET`       | `/api/matakuliah` | `Api\MatakuliahApiController@index` | Publik | Mengambil daftar seluruh Mata Kuliah |
| 15 | `GET`       | `/api/matakuliah/{id}` | `Api\MatakuliahApiController@show` | Publik | Mengambil detail Mata Kuliah berdasarkan ID |
| 16 | `POST`      | `/api/matakuliah` | `Api\MatakuliahApiController@store` | Bearer Token | Menambahkan record Mata Kuliah baru |
| 17 | `PUT`       | `/api/matakuliah/{id}` | `Api\MatakuliahApiController@update` | Bearer Token | Memperbarui record Mata Kuliah berdasarkan ID |
| 18 | `DELETE`    | `/api/matakuliah/{id}` | `Api\MatakuliahApiController@delete` | Bearer Token | Menghapus record Mata Kuliah berdasarkan ID |

---

## 2. Format Standar Respons (Envelope)

Seluruh endpoint menghasilkan respons berformat JSON seragam:

### Respons Sukses (`200 OK` / `201 Created`)
```json
{
  "success": true,
  "message": "Deskripsi keberhasilan operasi",
  "data": { ... }
}
```

### Respons Gagal (`400`, `401`, `404`, `405`, `422`, `500`)
```json
{
  "success": false,
  "message": "Deskripsi kesalahan",
  "errors": {
    "field_name": ["Pesan detail validasi"]
  }
}
```

---

## 3. Detail Dokumentasi Endpoint

### 3.1 Informasi Sistem
#### `GET /api`
- **Controller:** `ApiController::info()`
- **Headers:** None
- **Response 200 OK:**
```json
{
  "success": true,
  "message": "Selamat datang di API SI Akademik v1.0.0",
  "data": {
    "name": "SI Akademik REST API",
    "version": "1.0.0",
    "endpoints": { ... }
  }
}
```

---

### 3.2 Autentikasi Pengguna
#### `POST /api/auth/login`
- **Controller:** `AuthApiController::login()`
- **Headers:** `Content-Type: application/json`
- **Request Body:**
```json
{
  "username": "admin",
  "password": "admin123"
}
```
- **Response 200 OK:**
```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "token": "3a7f8e3c...64_char_hex...",
    "type": "Bearer",
    "expires_at": "2026-11-21 18:00:00",
    "user": "admin"
  }
}
```
- **Response 401 Unauthorized:**
```json
{
  "success": false,
  "message": "Username atau password salah."
}
```
- **Response 422 Unprocessable Entity:**
```json
{
  "success": false,
  "message": "Username dan password wajib diisi."
}
```

#### `POST /api/auth/logout`
- **Controller:** `AuthApiController::logout()`
- **Headers:** `Authorization: Bearer <token>`
- **Response 200 OK:**
```json
{
  "success": true,
  "message": "Logout berhasil.",
  "data": []
}
```

---

### 3.3 Resource Mahasiswa
#### `GET /api/mahasiswa`
- **Controller:** `MahasiswaApiController::index()`
- **Query Params:** `?search={keyword}` (opsional)
- **Response 200 OK:**
```json
{
  "success": true,
  "message": "Data mahasiswa berhasil diambil.",
  "data": [
    {
      "id": 1,
      "nim": "2401001",
      "nama": "Budi Santoso",
      "prodi_id": 1,
      "prodi_nama": "Teknik Informatika",
      "status": "aktif",
      "alamat": "Jl. Mastrip No. 12, Jember"
    }
  ]
}
```

#### `GET /api/mahasiswa/{id}`
- **Controller:** `MahasiswaApiController::show()`
- **Response 200 OK:** (detail objek data mahasiswa)
- **Response 404 Not Found:**
```json
{
  "success": false,
  "message": "Data mahasiswa tidak ditemukan."
}
```

#### `POST /api/mahasiswa`
- **Controller:** `MahasiswaApiController::store()`
- **Headers:**
  - `Content-Type: application/json`
  - `Authorization: Bearer <token>`
- **Request Body:**
```json
{
  "nim": "2601099",
  "nama": "Siti Rahmawati",
  "prodi_id": 1,
  "status": "aktif",
  "alamat": "Jl. Riau No. 45, Jember"
}
```
- **Response 201 Created:**
```json
{
  "success": true,
  "message": "Mahasiswa berhasil ditambahkan.",
  "data": { "id": 6 }
}
```
- **Response 422 Unprocessable Entity (Validasi Gagal):**
```json
{
  "success": false,
  "message": "Data mahasiswa gagal divalidasi.",
  "errors": {
    "nim": ["NIM 2401001 sudah digunakan."]
  }
}
```
- **Response 401 Unauthorized:**
```json
{
  "success": false,
  "message": "Token tidak valid atau sudah kedaluwarsa."
}
```

#### `PUT /api/mahasiswa/{id}`
- **Controller:** `MahasiswaApiController::update()`
- **Headers:**
  - `Content-Type: application/json`
  - `Authorization: Bearer <token>`
- **Request Body:** (format sama seperti POST)
- **Response 200 OK:**
```json
{
  "success": true,
  "message": "Mahasiswa berhasil diperbarui.",
  "data": []
}
```

#### `DELETE /api/mahasiswa/{id}`
- **Controller:** `MahasiswaApiController::delete()`
- **Headers:** `Authorization: Bearer <token>`
- **Response 204 No Content:** (HTTP 204 tanpa body)
- **Response 404 Not Found:** ID mahasiswa tidak ditemukan.

---

### 3.4 Resource Prodi & Matakuliah
Menerapkan pola CRUD seragam:
- `GET /api/prodi` & `GET /api/matakuliah` (Publik)
- `POST`, `PUT`, `DELETE` (Membutuhkan Bearer Token)
- Proteksi Integritas Relasi: Penghapusan Prodi yang memiliki relasi mahasiswa aktif ditolak dengan HTTP 422.
