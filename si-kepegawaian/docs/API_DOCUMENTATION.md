# Dokumentasi RESTful API SI-Kepegawaian
**Studi Kasus:** Sistem Informasi Kepegawaian  
**Standar Kurikulum:** BKPM Praktikum Web Service (Acara 15 - 27)

---

## 1. Informasi Umum
* **Base URL:** `http://localhost/webserver/si-kepegawaian/public`
* **Format Request/Response:** `application/json`
* **Format Respon Standar (Response Envelope):**
```json
{
  "status": "success | error",
  "message": "Pesan deskriptif hasil proses",
  "data": null | { ... } | [ ... ]
}
```

---

## 2. Daftar HTTP Status Code yang Digunakan
| Kode | Keterangan | Penggunaan |
| :--- | :--- | :--- |
| **200** | OK | Request berhasil diproses (GET, PUT, DELETE). |
| **201** | Created | Data baru berhasil ditambahkan (POST). |
| **400** | Bad Request | Validasi data gagal (input wajib kosong, NIP/Email kembar). |
| **401** | Unauthorized | Kredensial tidak valid atau token belum disertakan. |
| **403** | Forbidden | Akses ditolak karena role tidak memiliki hak izin. |
| **404** | Not Found | Data atau endpoint yang dituju tidak ditemukan. |
| **500** | Internal Error | Terjadi kesalahan query database atau kegagalan server. |

---

## 3. Endpoints API Pegawai

### A. Health Check & Ping
* **URL:** `/api/ping`
* **Method:** `GET`
* **Deskripsi:** Memeriksa kesiapan dan status aktif server API.
* **Respon Sukses (200 OK):**
```json
{
  "status": "success",
  "message": "Pong! API Berjalan dengan baik.",
  "data": {
    "app": "SI-Kepegawaian",
    "version": "1.0.0",
    "time": "2026-11-22 05:58:19"
  }
}
```

---

### B. Mendapatkan Semua Data Pegawai
* **URL:** `/api/pegawai`
* **Method:** `GET`
* **Deskripsi:** Mengambil seluruh daftar pegawai yang aktif, diurutkan dari yang terbaru.
* **Respon Sukses (200 OK):**
```json
{
  "status": "success",
  "message": "Berhasil mengambil data pegawai",
  "data": [
    {
      "id": 1,
      "nip": "198501012010011001",
      "nama": "Budi Santoso, S.Kom.",
      "jabatan": "Kepala HRD",
      "email": "admin.hr@kantor.go.id",
      "no_hp": "081234567890",
      "alamat": "Jl. Merdeka No. 45, Jakarta",
      "foto": "default.png",
      "created_at": "2026-11-21 10:21:13"
    }
  ]
}
```

---

### C. Mendapatkan Detail Pegawai
* **URL:** `/api/pegawai/{id}`
* **Method:** `GET`
* **Parameter URL:** `id` (integer) - ID Pegawai yang dicari.
* **Respon Sukses (200 OK):**
```json
{
  "status": "success",
  "message": "Berhasil mengambil data pegawai",
  "data": {
    "id": 1,
    "nip": "198501012010011001",
    "nama": "Budi Santoso, S.Kom.",
    "jabatan": "Kepala HRD",
    "email": "admin.hr@kantor.go.id",
    "no_hp": "081234567890",
    "alamat": "Jl. Merdeka No. 45, Jakarta",
    "foto": "default.png"
  }
}
```
* **Respon Gagal (404 Not Found):**
```json
{
  "status": "error",
  "message": "pegawai dengan ID 999 tidak ditemukan"
}
```

---

### D. Menambahkan Pegawai Baru
* **URL:** `/api/pegawai`
* **Method:** `POST`
* **Headers:** `Content-Type: application/json`
* **Request Body:**
```json
{
  "nip": "199505052020011003",
  "nama": "Ahmad Fauzi",
  "jabatan": "IT Support",
  "email": "fauzi@kantor.go.id",
  "no_hp": "085712345678",
  "alamat": "Jl. Thamrin No. 8"
}
```
* **Respon Sukses (201 Created):**
```json
{
  "status": "success",
  "message": "Berhasil membuat data pegawai",
  "data": {
    "id": 4,
    "nip": "199505052020011003",
    "nama": "Ahmad Fauzi",
    "jabatan": "IT Support",
    "email": "fauzi@kantor.go.id",
    "foto": "default.png"
  }
}
```
* **Respon Gagal (400 Bad Request):**
```json
{
  "status": "error",
  "message": "NIP 199505052020011003 sudah digunakan"
}
```

---

### E. Memperbarui Data Pegawai
* **URL:** `/api/pegawai/{id}`
* **Method:** `PUT`
* **Headers:** `Content-Type: application/json`
* **Request Body:**
```json
{
  "nip": "199505052020011003",
  "nama": "Ahmad Fauzi, S.T.",
  "jabatan": "Senior IT Support",
  "email": "fauzi@kantor.go.id",
  "no_hp": "085712345678",
  "alamat": "Jl. Gatot Subroto No. 99"
}
```
* **Respon Sukses (200 OK):**
```json
{
  "status": "success",
  "message": "Berhasil memperbarui data pegawai",
  "data": {
    "id": 4,
    "nama": "Ahmad Fauzi, S.T.",
    "jabatan": "Senior IT Support"
  }
}
```

---

### F. Menghapus Data Pegawai
* **URL:** `/api/pegawai/{id}`
* **Method:** `DELETE`
* **Respon Sukses (200 OK):**
```json
{
  "status": "success",
  "message": "Berhasil menghapus data pegawai"
}
```