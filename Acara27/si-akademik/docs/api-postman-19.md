# Pengujian API dengan Postman (Acara 19)

Seluruh endpoint API (Acara 15-18) diuji dengan **Postman**. Endpoint yang
memodifikasi data (POST/PUT/DELETE) dilindungi **token Bearer**.

## 1. Koleksi

File `docs/api.postman_collection.json` berisi koleksi lengkap:

- Folder **Autentikasi**: Login, Logout.
- Folder **Mahasiswa / Prodi / Matakuliah**: GET list, GET detail, POST, PUT, DELETE.

Variabel koleksi:
- `base_url`   : `http://localhost/si-akademik/public`
- `auth_token` : token yang diisi otomatis oleh script test pada request Login.

## 2. Cara Menguji

1. Buka Postman, `Import` -> pilih `api.postman_collection.json`.
2. Klik request **Login**, lalu klik **Send**. Script test menyimpan token ke
   variabel `{{auth_token}}`.
3. Jalankan request lain (mis. GET `/api/mahasiswa`). Endpoint yang butuh token
   sudah memakai `Authorization: Bearer {{auth_token}}` secara otomatis.

## 3. Contoh respons Login

```json
{
    "success": true,
    "message": "Login berhasil.",
    "data": {
        "token": "9f2c...64-heksadesimal...",
        "type": "Bearer",
        "expires_at": "2026-09-29 10:00:00",
        "user": "admin"
    }
}
```

## 4. Alur Keamanan

| Element | Peran |
|---------|-------|
| `POST /api/auth/login` | verifikasi username/password via `password_verify`, terbitkan token acak 64 hex |
| Tabel `api_tokens` | menyimpan token + `expires_at` (24 jam), FK ke `users` |
| `ApiAuthMiddleware` | memvalidasi `Authorization: Bearer <token>` di tiap endpoint non-GET |
| `POST /api/auth/logout` | mencabut (menghapus) token |

Token yang salah atau kedaluwarsa menghasilkan `401`: