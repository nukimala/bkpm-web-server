# API dan JSON (Acara 15)

Konsep dasar: **API** adalah perantara yang memungkinkan dua aplikasi bertukar
data. Pada praktikum ini API dikembangkan di dalam project SI Akademik dengan
prefix `/api` dan format **JSON**.

## 1. Menguji Endpoint dengan curl

Jalankan Apache + MySQL (XAMPP), lalu coba dari terminal:

```bash
curl http://localhost/si-akademik/public/api
curl http://localhost/si-akademik/public/api/mahasiswa
curl "http://localhost/si-akademik/public/api/mahasiswa?id=1"
curl http://localhost/si-akademik/public/api/mahasiswa/2401001
curl http://localhost/si-akademik/public/api/prodi
curl http://localhost/si-akademik/public/api/matakuliah
```

Endpoint `GET /api/mahasiswa?id=1` mengambil satu mahasiswa berdasarkan ID
primary key; endpoint `GET /api/mahasiswa/{nim}` mengambil berdasarkan NIM.

### POST /api/mahasiswa

Kirim body JSON (di Postman: Body → raw → JSON):

```bash
curl -X POST http://localhost/si-akademik/public/api/mahasiswa \
  -H "Content-Type: application/json" \
  -d '{"nim":"2501010","nama":"Dewi Lestari","prodi_id":1}'
```

Respons berhasil (HTTP 201 Created):

```json
{
    "success": true,
    "message": "Data mahasiswa berhasil ditambahkan",
    "data": {
        "id": 6,
        "nim": "2501010",
        "nama": "Dewi Lestari",
        "prodi_id": 1,
        "prodi": "Teknik Informatika",
        "status": "aktif",
        "alamat": "",
        "angkatan": 2025
    }
}
```

Respons gagal (HTTP 400):

```json
{
    "success": false,
    "message": "Data tidak valid.",
    "errors": {
        "nim": "NIM sudah terdaftar."
    }
}
```

## 2. Contoh Respons JSON

`GET /api`:

```json
{
    "success": true,
    "message": "API berjalan normal.",
    "data": {
        "aplikasi": "SI Akademik API",
        "versi": "1.0.0",
        "format": "JSON (application/json)"
    }
}
```

`GET /api/mahasiswa/2401001`:

```json
{
    "success": true,
    "message": "OK",
    "data": {
        "id": 1,
        "nim": "2401001",
        "nama": "Budi Santoso",
        "prodi_id": 1,
        "prodi": "Teknik Informatika",
        "status": "aktif",
        "angkatan": 2024
    }
}
```

`GET /api/mahasiswa/999999` (tidak ada):

```json
{
    "success": false,
    "message": "Mahasiswa dengan NIM 999999 tidak ditemukan.",
    "errors": []
}
```

## 3. Komponen yang Digunakan

| File | Fungsi |
|------|--------|
| `app/Core/ApiResponse.php` | helper respon JSON (`json`, `success`, `error`) |
| `app/Controllers/ApiController.php` | logika endpoint API |
| `routes/api.php` | daftar route API |
| `public/index.php` | dispatch request ber-prefix `/api` |
| `app/Models/Mahasiswa.php` | implementasi `JsonSerializable` agar objek bisa `json_encode` |

