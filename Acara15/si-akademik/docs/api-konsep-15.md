# API dan JSON (Acara 15)

Konsep dasar: **API** adalah perantara yang memungkinkan dua aplikasi bertukar
data. Pada praktikum ini API dikembangkan di dalam project SI Akademik dengan
prefix `/api` dan format **JSON**.

## 1. Menguji Endpoint dengan curl

Jalankan Apache + MySQL (XAMPP), lalu coba dari terminal:

```bash
curl http://localhost/si-akademik/public/api
curl http://localhost/si-akademik/public/api/mahasiswa
curl http://localhost/si-akademik/public/api/mahasiswa/2401001
curl http://localhost/si-akademik/public/api/prodi
curl http://localhost/si-akademik/public/api/matakuliah
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

