# Catatan Pelaksanaan Praktikum: Acara 24

**Pokok Bahasan:** Integrasi API dengan Frontend  
**Nama Program:** Sistem Informasi Akademik (SI-Akademik)  
**Tahun:** 2026  

---

## 1. Ringkasan Pelaksanaan
Pada Acara 24, backend RESTful API SI-Akademik diintegrasikan dengan aplikasi frontend berbasis web menggunakan JavaScript modern:
1. **Penerapan Header CORS Terpusat:** Diaktifkan header `Access-Control-Allow-Origin: *`, `Methods`, dan `Headers` pada front controller `public/index.php` serta penanganan HTTP `OPTIONS` preflight berstatus `204 No Content`.
2. **Penggunaan Fetch API dengan Async/Await:** Kode asynchronous disusun rapi dan terstruktur dalam `public/views/app.js` untuk memanggil endpoint `GET /api/mahasiswa`.
3. **Pemisahan Tampilan:** Dibuat antarmuka statis `public/views/index.html` dengan desain responsif memanfaatkan Bootstrap 5 CDN.

## 2. Pemenuhan Tugas Praktik Mandiri
1. **Indikator Loading ("Memuat data..."):** Diimplementasikan elemen loading spinner dan teks dinamis pada tabel sebelum Promise dari `fetch()` berstatus fulfilled.
2. **Penanganan Kondisi Data Kosong:** Ditambahkan deteksi jumlah data (`data.length === 0`). Jika tidak ada data yang dikembalikan oleh API atau hasil pencarian nihil, tabel secara otomatis menampilkan pesan ramah: *"Belum ada data mahasiswa"*.
3. **Fitur Ekstra:** Dilengkapi dengan pencarian mahasiswa real-time dengan debouncing dan tombol pemuatan ulang (refresh).
