# Catatan Pelaksanaan Praktikum: Acara 21

**Pokok Bahasan:** Dokumentasi Endpoint  
**Nama Program:** Sistem Informasi Akademik (SI-Akademik)  
**Tahun:** 2026  

---

## 1. Ringkasan Pelaksanaan
Pada Acara 21, dilakukan standarisasi dan penyusunan dokumentasi resmi untuk seluruh endpoint RESTful API SI-Akademik. Dokumentasi disusun dalam file `docs/API_DOCUMENTATION.md` yang memuat spesifikasi teknis lengkap:
- Pemetaan Router ke Controller & Method.
- Standar HTTP Method, Header, Request Body JSON, Response Body Envelope, serta HTTP Status Code (200, 201, 204, 400, 401, 404, 405, 422, 500).
- Skema autentikasi Bearer Token untuk endpoint manipulasi data (POST, PUT, DELETE).

## 2. Pemenuhan Tugas Praktik Mandiri
Telah ditambahkan tabel ringkasan komprehensif di bagian awal `docs/API_DOCUMENTATION.md` yang merangkum seluruh 18 endpoint API, controller penanganannya, status autentikasi, serta keterangan fungsionalnya.

## 3. Koleksi Pengujian Postman
Koleksi Postman pada `docs/api.postman_collection.json` diperbarui dengan deskripsi lengkap per request dan contoh respons (success & error examples) untuk memudahkan pengembang dan penguji lain mengintegrasikan API.
