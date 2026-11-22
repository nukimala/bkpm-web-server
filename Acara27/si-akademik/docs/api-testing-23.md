# Catatan Pelaksanaan Praktikum: Acara 23

**Pokok Bahasan:** Manual Testing dan Blackbox Testing (Equivalence Partitioning)  
**Nama Program:** Sistem Informasi Akademik (SI-Akademik)  
**Tahun:** 2026  

---

## 1. Ringkasan Pelaksanaan
Pada Acara 23, dilakukan pengujian perangkat lunak sistematis pada seluruh endpoint RESTful API SI-Akademik dengan menerapkan metode **Blackbox Testing** dan teknik **Equivalence Partitioning**.
Pengujian difokuskan pada:
1. Pemisahan domain masukan ke dalam partisi valid dan tidak valid.
2. Pengujian respon sistem terhadap kredensial login (sukses vs salah password).
3. Pengujian penolakan endpoint terproteksi tanpa header Authorization (token Bearer).
4. Verifikasi validasi input data mahasiswa (NIM unik, field required, status).

## 2. Pemenuhan Tugas Praktik Mandiri
1. **Penambahan Test Case Update & Delete:** Telah ditambahkan 8 test case baru (TC-07 hingga TC-14) yang mencakup operasi `PUT /api/mahasiswa/{id}`, `DELETE /api/mahasiswa/{id}`, serta pengujian integritas referensial foreign key saat menghapus Program Studi yang masih memiliki data mahasiswa.
2. **Evaluasi dan Hasil Uji:** Seluruh skenario pengujian dieksekusi secara manual dan melalui uji skrip, dengan hasil 100% berstatus **PASS** tanpa kebocoran error 500 ke client. Hasil pengujian didokumentasikan lengkap dalam `docs/TEST_CASE.md`.
