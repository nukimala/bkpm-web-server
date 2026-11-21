# RESTful API dan Request/Response (Acara 16)

Pada acara ini API SI Akademik dikembangkan menjadi **RESTful**: satu resource
diwakili satu URI, dengan tindakan ditentukan oleh **HTTP method**.

## 1. Pemetaan Endpoint

| Method | URI                | Aksi            | Status sukses |
|--------|--------------------|-----------------|---------------|
| GET    | /api/mahasiswa     | tampil semua    | 200           |
| GET    | /api/mahasiswa/{id}| tampil satu     | 200           |
| POST   | /api/mahasiswa     | tambah data     | 201           |
| PUT    | /api/mahasiswa/{id}| ubah data       | 200           |
| DELETE | /api/mahasiswa/{id}| hapus data      | 204           |

Pola yang sama berlaku untuk `prodi` dan `matakuliah`.

## 2. Request dan Response (contoh curl)

Tambah data (POST) - kirim JSON:

```bash
curl -X POST http://localhost/si-akademik/public/api/mahasiswa \
  -H "Content-Type: application/json" \
  -d "{ \"nim\": \"2601001\", \"nama\": \"Fitri Handayani\", \"prodi_id\": 1, \"status\": \"aktif\", \"alamat\": \"Jl. Semarang No. 9, Jember\" }"
```

Respons:

```json
{ "success": true, "message": "Data mahasiswa berhasil dibuat.", "data": [] }
```

Ubah data (PUT):

```bash
curl -X PUT http://localhost/si-akademik/public/api/mahasiswa/6 \
  -H "Content-Type: application/json" \
  -d "{ \"nim\": \"2601001\", \"nama\": \"Fitri Handayani Sari\", \"prodi_id\": 2, \"status\": \"aktif\", \"alamat\": \"...\" }"
```

Hapus data (DELETE) - respons 204 tanpa isi:

```bash
curl -X DELETE http://localhost/si-akademik/public/api/mahasiswa/6 -i
```

Respons 404 bila id tidak ada:

```json
{ "success": false, "message": "Mahasiswa tidak ditemukan.", "errors": [] }
```

## 3. Kesalahan Status Code

- `404 Not Found`  : path endpoint tidak ditemukan / data tidak ada.
- `405 Method Not Allowed` : path ada tetapi method tidak cocok (mis. DELETE pada /api/mahasiswa tanpa id).
- `500 Internal Server Error` : kegagalan sisi server dalam menyimpan.

## 4. Komponen Baru

| File | Fungsi |
|------|--------|
| `app/Core/ApiRouter.php` | router API aware method; `dispatch()`, `input()` (baca JSON body) |
| `app/Controllers/Api/*ApiController.php` | balik suara REST per resource |
| `routes/api.php` | definisi route format `'METHOD /api/path'` |

Validasi data pada POST/PUT menyusul di Acara 17 (API CRUD lengkap).