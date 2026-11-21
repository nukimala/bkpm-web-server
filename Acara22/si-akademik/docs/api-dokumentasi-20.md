# Evaluasi Implementasi dan Dokumentasi Akhir (Acara 20)

Acara ini menutup rangkaian pengembangan aplikasi web + REST API SI Akademik.
Fokus: penyempurnaan, pembersihan, dan dokumentasi akhir.

## 1. Penyempurnaan yang Dilakukan

- **Endpoint dokumentasi diri**: `GET /api` kini menampilkan daftar seluruh
  endpoint (self-documented API).
- **Pembersihan token kadaluarsa**: `TokenRepository::purgeExpired()` dijalankan
  tiap kali login agar tabel `api_tokens` tidak menumpuk data basi.
- **Penanganan error terpusat**: seluruh respon memakai `ApiResponse`; exception
  tak terduga selalu `500` `application/json` tanpa membocorkan detail.

## 2. Dokumentasi Lengkap API (v1.0.0)

| Method | Endpoint | Auth | Sukses | Deskripsi |
|--------|----------|------|--------|-----------|
| GET    | `/api` | - | 200 | info & dokumentasi endpoint |
| POST   | `/api/auth/login` | - | 200 | login, terbitkan token (24 jam) |
| POST   | `/api/auth/logout` | Bearer | 200 | cabut token |
| GET    | `/api/mahasiswa` | - | 200 | daftar mahasiswa (`?search=`) |
| GET    | `/api/mahasiswa/{id}` | - | 200 | detail mahasiswa |
| POST   | `/api/mahasiswa` | Bearer | 201 | tambah mahasiswa |
| PUT    | `/api/mahasiswa/{id}` | Bearer | 200 | ubah mahasiswa |
| DELETE | `/api/mahasiswa/{id}` | Bearer | 204 | hapus mahasiswa |
| GET    | `/api/prodi` | - | 200 | daftar prodi |
| GET    | `/api/prodi/{id}` | - | 200 | detail prodi |
| POST   | `/api/prodi` | Bearer | 201 | tambah prodi |
| PUT    | `/api/prodi/{id}` | Bearer | 200 | ubah prodi |
| DELETE | `/api/prodi/{id}` | Bearer | 204 | hapus prodi (422 jika ada mhs) |
| GET    | `/api/matakuliah` | - | 200 | daftar matakuliah |
| GET    | `/api/matakuliah/{id}` | - | 200 | detail matakuliah |
| POST   | `/api/matakuliah` | Bearer | 201 | tambah matakuliah |
| PUT    | `/api/matakuliah/{id}` | Bearer | 200 | ubah matakuliah |
| DELETE | `/api/matakuliah/{id}` | Bearer | 204 | hapus matakuliah |

### Format Envelope Respons

```json
{ "success": true|false, "message": "...", "data": ..., "errors": [...] }
```

## 3. Checklist Evaluasi (Regression Test)

- [x] Web login (`/login`) menerima `admin / admin123` (bcrypt + session_regenerate_id).
- [x] Halaman terproteksi menolak akses tanpa login (redirect).
- [x] Form create/edit menolak submit tanpa CSRF token.
- [x] CRUD web mahasiswa/prodi/matakuliah berjalan dengan PRG + flash.
- [x] `GET /api/mahasiswa` mengembalikan array JSON (objek lewat `JsonSerializable`).
- [x] POST tanpa token -> `401`.
- [x] POST dengan token valid -> `201`; data tidak valid -> `422`.
- [x] DELETE data tidak ada -> `404`; DELETE berhasil -> `204`.
- [x] Request `OPTIONS` (preflight) -> `204` + header CORS.
- [x] Route web yang tidak dikenal -> 404 halaman; route API tidak dikenal -> `404` JSON.
- [x] `php -l` seluruh file: 0 error.

## 4. Perintah Uji Akhir (curl)

```bash
# Info API
curl http://localhost/si-akademik/public/api

# Login & ambil token (jq opsional untuk parsing)
TOKEN=$(curl -s -X POST http://localhost/si-akademik/public/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}' | jq -r .data.token)

# CRUD dengan token
curl -X POST http://localhost/si-akademik/public/api/mahasiswa \
  -H "Content-Type: application/json" -H "Authorization: Bearer $TOKEN" \
  -d '{"nim":"2601001","nama":"Fitri Handayani","prodi_id":1,"status":"aktif"}'

curl -s -X DELETE http://localhost/si-akademik/public/api/mahasiswa/1 \
  -H "Authorization: Bearer $TOKEN" -o /dev/null -w "%{http_code}\n"
```