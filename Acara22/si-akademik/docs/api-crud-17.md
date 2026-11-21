# API CRUD Lengkap dengan Validasi (Acara 17)

Endpoint API di Acara 16 masih memanggil repository secara langsung sehingga
data tidak divalidasi. Pada Acara 17 seluruh endpoint POST/PUT/DELETE memakai
**Service Layer** (yang dibuat di Acara 13) sehingga validasi dan business
logic yang sama dengan aplikasi web ikut berlaku.

## 1. Apa yang Berubah

| Aspek | Acara 16 | Acara 17 |
|-------|----------|----------|
| Akses data | Repository langsung | Service + Repository |
| Validasi input | tidak ada | Validator (required, numeric, unik) |
| Error validasi | `500` | `422 Unprocessable Entity` |
| Error data tidak ada | `404` | `404` |

## 2. Contoh Request yang Gagal Validasi

POST `/api/mahasiswa` tanpa mengirim `nama`:

```bash
curl -X POST http://localhost/si-akademik/public/api/mahasiswa \
  -H "Content-Type: application/json" \
  -d "{ \"nim\": \"2601002\" }"
```

Respons `422`:

```json
{
    "success": false,
    "message": "Data mahasiswa gagal divalidasi.",
    "errors": {
        "nama": ["Nama wajib diisi."],
        "prodi_id": ["Prodi wajib diisi."],
        "status": ["Status wajib diisi."]
    }
}
```

NIM yang sudah dipakai data lain:

```json
{
    "success": false,
    "message": "Data mahasiswa gagal divalidasi.",
    "errors": { "nim": ["NIM 2401001 sudah digunakan."] }
}
```

## 3. Endpoint (Ringkasan)

| Method | URI | Sukses | Gagal |
|--------|-----|--------|-------|
| GET    | /api/mahasiswa?search=... | 200 | - |
| GET    | /api/mahasiswa/{id} | 200 | 404 |
| POST   | /api/mahasiswa | 201 | 422 |
| PUT    | /api/mahasiswa/{id} | 200 | 404/422 |
| DELETE | /api/mahasiswa/{id} | 204 | 404/422 |

Sama untuk `prodi` dan `matakuliah`. Penghapusan prodi ditolak (422) bila masih
memiliki mahasiswa terdaftar.