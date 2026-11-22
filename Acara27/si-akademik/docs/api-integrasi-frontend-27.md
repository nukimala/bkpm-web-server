# Catatan Pelaksanaan Praktikum: Acara 27

**Pokok Bahasan:** Integrasi Backend-Frontend  
**Nama Program:** Sistem Informasi Akademik (SI-Akademik)  
**Tahun:** 2026  

---

## 1. Ringkasan Pelaksanaan
Pada Acara 27, dilakukan integrasi penuh antara antarmuka web (*frontend client*) dan layanan backend REST API yang telah dibangun pada acara-acara sebelumnya. Praktikum ini berfokus pada:

1. **Pengiriman Data Multipart melalui `FormData`:**
   - Menghubungkan form input dengan objek antarmuka `FormData` JavaScript untuk mentransmisikan data teks sekaligus berkas biner (foto mahasiswa) menggunakan pemanggilan `fetch()`.
   - Menghindari penyetelan manual header `Content-Type` agar browser secara otomatis menentukan batas (*boundary delimiter*) multipart data.

2. **Sinkronisasi Operasi CRUD Penuh (Create, Read, Update, Delete):**
   - **Create (Langkah 1):** Dibuat halaman mandiri `form_tambah.html` untuk menambahkan mahasiswa baru beserta upload foto melalui method `POST /api/mahasiswa` dengan header `Authorization: Bearer <token>`.
   - **Update (Langkah 2):** Dibuat halaman mandiri `form_edit.html` yang secara dinamis membaca parameter ID pada URL (`?id=...`), memuat data awal mahasiswa via `GET /api/mahasiswa/{id}`, lalu mengirimkan perubahan data via API update dengan header `Authorization: Bearer <token>`.
   - **Delete (Langkah 3):** Ditambahkan tombol hapus pada tabel dashboard yang meminta konfirmasi pengguna (`confirm()`), mengirimkan permintaan `DELETE /api/mahasiswa/{id}` dengan status balasan `204 No Content`, dan me-refresh tabel secara *asynchronous* (Single Page Application/SPA-like refresh) tanpa memuat ulang seluruh halaman.

3. **Pengujian Alur CRUD dari Sisi View (Langkah 4):**
   - Pengujian login sebagai administrator (`admin` / `admin123`).
   - Penambahan data mahasiswa melalui `form_tambah.html`.
   - Pembaruan data mahasiswa melalui `form_edit.html`.
   - Penghapusan data mahasiswa melalui tombol hapus pada tabel `dashboard.html`.

---

## 2. Pemenuhan Tugas Praktik Mandiri

### Tugas Mandiri 1: Notifikasi Visual (Toast / Alert Interaktif)
Setiap kali operasi Create, Update, maupun Delete dijalankan dari sisi View, pengguna mendapatkan umpan balik visual instan:
- **Komponen Visual:** Menggunakan komponen **Bootstrap 5 Toast** yang melayang elegan di pojok kanan atas layar (`.toast-container position-fixed top-0 end-0`).
- **Warna & Ikon Status:**
  - *Sukses (Hijau):* Latar belakang `bg-success` dengan ikon centang (`bi-check-circle-fill`) untuk pesan "Data mahasiswa berhasil ditambahkan!", "Data mahasiswa berhasil diperbarui!", atau "Mahasiswa berhasil dihapus."
  - *Gagal (Merah):* Latar belakang `bg-danger` dengan ikon peringatan (`bi-exclamation-triangle-fill`) untuk pesan penolakan akses (HTTP 403), validasi gagal (HTTP 422), atau gangguan jaringan.

```javascript
// Cuplikan fungsi notifikasi visual pada dashboard.js dan form halaman
function showToast(message, isSuccess = true) {
    const toastEl = document.querySelector('#liveToast');
    const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
    toastEl.className = `toast align-items-center text-white border-0 shadow bg-${isSuccess ? 'success' : 'danger'}`;
    document.querySelector('#toast-icon').className = `bi me-2 fs-5 ${isSuccess ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'}`;
    document.querySelector('#toast-message').textContent = message;
    toast.show();
}
```

---

### Tugas Mandiri 2: Validasi Sisi View (Client-Side Validation)
Sebagai implementasi prinsip keamanan *defense-in-depth*, validasi tidak hanya dilakukan di sisi backend (Model/Service), tetapi juga diperiksa terlebih dahulu di sisi client sebelum request HTTP dikirim:
1. **Validasi NIM:** Memastikan field NIM tidak kosong dan hanya berisi karakter angka dengan panjang minimal 7 digit menggunakan ekspresi reguler `/^\d{7,20}$/`.
2. **Validasi Nama:** Memastikan nama mahasiswa minimal 3 karakter huruf.
3. **Validasi Program Studi:** Memastikan pengguna telah memilih salah satu program studi dari daftar opsi.
4. **Validasi Berkas Foto:** 
   - Memeriksa batas ukuran berkas tidak melebihi 2MB (`file.size <= 2 * 1024 * 1024`).
   - Memeriksa tipe MIME file hanya berupa `image/jpeg` atau `image/png`.
   - Memberikan umpan balik kelas Bootstrap `.is-invalid` dan pesan `.invalid-feedback` merah di bawah kolom input jika data tidak memenuhi syarat.

```javascript
// Cuplikan validasi sisi view
function validateClientSide() {
    let isValid = true;
    const nimVal = nimInput.value.trim();
    if (!nimVal || !/^\d{7,20}$/.test(nimVal)) {
        nimInput.classList.add('is-invalid');
        isValid = false;
    } else {
        nimInput.classList.remove('is-invalid');
    }
    // ... validasi nama, prodi, dan foto ...
    return isValid;
}
```

---

## 3. Struktur Berkas yang Terlibat
```text
Acara27/si-akademik/
├── docs/
│   └── api-integrasi-frontend-27.md  <- Catatan evaluasi praktikum Acara 27
└── public/
    └── views/
        ├── dashboard.html             <- Tampilan utama tabel mahasiswa & ringkasan statistik
        ├── dashboard.js               <- Logika fetch asynchronous, RBAC, dan delete
        ├── form_tambah.html           <- View mandiri tambah mahasiswa (Langkah 1 BKPM)
        ├── form_edit.html             <- View mandiri edit mahasiswa (Langkah 2 BKPM)
        ├── login.html                 <- Halaman autentikasi client
        └── login.js                   <- Pengelolaan token JWT & role pada localStorage
```
