# Encoding JSON, Status Code HTTP, dan CORS (Acara 18)

## 1. Encoding JSON Terpusat

Seluruh respon API melewati `ApiResponse::json()` yang menjamin encoding
seragam:

- `JSON_UNESCAPED_UNICODE`  : karakter latin (nada, tanda) tampil apa adanya.
- `JSON_UNESCAPED_SLASHES`  : `/` tidak di-escape menjadi `\/`.
- `JSON_PRETTY_PRINT`       : output ter-format rapi untuk konsumen.

## 2. Status Code HTTP yang Dipakai

| Kode | Arti | Kemunculan |
|------|------|-----------|
| 200  | OK                  | GET berhasil |
| 201  | Created             | POST berhasil |
| 204  | No Content          | DELETE berhasil / preflight OPTIONS |
| 400  | Bad Request         | error umum |
| 404  | Not Found           | endpoint/data tidak ada |
| 405  | Method Not Allowed  | path ada, method salah |
| 422  | Unprocessable       | validasi input gagal |
| 500  | Internal Server Error | kesalahan tidak terduga |

Verifikasi status dengan `curl -i` atau `curl -X DELETE ... -w "%{http_code}"`.

## 3. Penanganan Error Terpusat

Blok API di `public/index.php` dibungkus `try/catch`:

- `AppException`  -> pesan + kode status (bila 400-599), selain itu `500`.
- `Throwable`     -> selalu `500` dengan pesan generik (rincian tidak dibocorkan).

## 4. CORS (Cross-Origin Resource Sharing)

Header dikirim di semua respon, termasuk `204`:

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

Request `OPTIONS` (preflight dari browser) langsung dijawab `204` di front
controller tanpa masuk controller.

## 5. Pengujian

```bash
curl -i http://localhost/si-akademik/public/api/mahasiswa
curl -X OPTIONS -i http://localhost/si-akademik/public/api/mahasiswa
curl -X DELETE -i http://localhost/si-akademik/public/api/mahasiswa/9999
```