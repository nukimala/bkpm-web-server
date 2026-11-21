# Catatan Pelaksanaan Praktikum: Acara 22

**Pokok Bahasan:** Dokumentasi Endpoint (OpenAPI Specification 3.0 / Swagger)  
**Nama Program:** Sistem Informasi Akademik (SI-Akademik)  
**Tahun:** 2026  

---

## 1. Ringkasan Pelaksanaan
Pada Acara 22, dokumentasi API dikembangkan dari format Markdown (Acara 21) menjadi format standar industri **OpenAPI Specification 3.0.0** (`docs/openapi.yaml` dan `docs/openapi.json`).
Keunggulan OpenAPI:
- Format terstandarisasi yang machine-readable dan human-readable.
- Kompatibel dengan Swagger Editor, Swagger UI, Postman Import, dan generator SDK klien.
- Mendukung visualisasi interaktif "Try-it-out" secara langsung dari browser.

## 2. Pemenuhan Tugas Praktik Mandiri
1. **Lengkapi seluruh path OpenAPI:** Telah didaftarkan seluruh operasi CRUD (`GET`, `POST`, `PUT`, `DELETE`) untuk resource `/api/mahasiswa`, `/api/prodi`, `/api/matakuliah`, serta endpoint autentikasi `/api/auth/login` dan `/api/auth/logout`.
2. **Komponen securitySchemes:** Bagian `components/securitySchemes` telah dilengkapi skema `bearerAuth` bertipe `http` dengan scheme `bearer`, menandai endpoint mana saja yang membutuhkan token.

## 3. Visualisasi Swagger UI
Disediakan viewer Swagger UI interaktif di `public/docs/index.html` yang memuat spesifikasi OpenAPI secara visual untuk memudahkan pengujian endpoint secara langsung.
