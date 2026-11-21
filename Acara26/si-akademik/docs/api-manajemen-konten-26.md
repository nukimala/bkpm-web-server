# Catatan Pelaksanaan Praktikum: Acara 26

**Pokok Bahasan:** Manajemen Konten, Upload File dan Database Management  
**Nama Program:** Sistem Informasi Akademik (SI-Akademik)  
**Tahun:** 2026  

---

## 1. Ringkasan Pelaksanaan
Pada Acara 26, Sistem Informasi Akademik ditingkatkan dengan fitur pengelolaan konten multimedia (unggah berkas foto mahasiswa) serta manajemen pencadangan dan pemulihan (*backup and restore*) basis data:

1. **Skema Basis Data:** Menambahkan kolom `foto VARCHAR(255) DEFAULT NULL` pada tabel `mahasiswa` di `database/si_akademik.sql`.
2. **Direktori Penyimpanan:** Menyediakan folder penyimpanan berkas publik di `public/uploads/` dengan hak akses yang aman.
3. **Validasi Keamanan File Upload (Security Best Practices):**
   - Validasi ekstensi file: hanya mengizinkan `jpg`, `jpeg`, dan `png`.
   - Validasi signature berkas nyata: menggunakan fungsi `exif_imagetype()` dan `getimagesize()` untuk memastikan file adalah gambar murni, bukan file skrip berbahaya (seperti `.php` atau `.phtml`) yang dipalsukan ekstensinya.
   - Pembatasan ukuran berkas: maksimal 2MB (`2 * 1024 * 1024` bytes).
   - Sanitasi nama berkas: menghasilkan nama acak unik dengan `uniqid('foto_', true)` untuk menghindari *path traversal* atau tumpang-tindih berkas.
   - Pemindahan file menggunakan `move_uploaded_file()` ke folder `public/uploads/`.
4. **Integrasi Frontend Multipart Form Data:**
   - Form modal mahasiswa pada `dashboard.html` diperluas dengan field upload berkas (`<input type="file" id="mhs-foto">`) dan preview thumbnail gambar.
   - Pada `dashboard.js`, pengiriman payload dialihkan menggunakan objek `FormData` agar mendukung transfer data binary multipart.

---

## 2. Pemenuhan Tugas Praktik Mandiri

### Tugas Mandiri 1: Penghapusan Berkas Foto Lama (`unlink()`)
Pada `MahasiswaService::update()` dan `MahasiswaService::delete()`, diimplementasikan logika pembersihan berkas lama secara otomatis:
- Ketika admin memperbarui profil mahasiswa dengan foto baru, file foto lama yang tersimpan di direktori `public/uploads/` diperiksa keberadaannya dan dihapus menggunakan fungsi bawaan PHP `unlink()`.
- Ketika data mahasiswa dihapus dari sistem, file foto terkait juga otomatis dihapus dari server sehingga tidak meninggalkan berkas sampah (*orphaned files*).

```php
// Potongan implementasi pada MahasiswaService
if ($oldPhoto !== null && $oldPhoto !== '') {
    $oldPath = $uploadDir . $oldPhoto;
    if (file_exists($oldPath) && is_file($oldPath)) {
        @unlink($oldPath);
    }
}
```

### Tugas Mandiri 2: Penjadwalan Backup Basis Data
Pencadangan basis data dicatat dan dijadwalkan secara teratur pada setiap akhir sesi praktikum untuk mengantisipasi kegagalan sistem atau kehilangan data.

#### Tabel Jadwal Rutin Pencadangan Basis Data:
| Frekuensi | Waktu Pelaksanaan | Tipe Backup | Target Penyimpanan | Media / Tools |
|:---------:|:-----------------:|:-----------:|:-------------------|:--------------|
| **Setiap Akhir Sesi** | Pukul 17.00 WIB (Pasca Praktikum) | Full Database Dump | `database/backup_si_akademik_acara26.sql` | phpMyAdmin Export / mysqldump |
| **Mingguan** | Setiap Hari Jumat | Kompresi `.sql.gz` | Cloud Storage / External Drive | Skrip shell / PowerShell |
| **Milestone** | Setiap Rilis Acara Baru | Versi Snapshot | Direktori project `database/` | Git Version Control |

#### Perintah Pencadangan & Pemulihan (CLI):
```bash
# Backup basis data via CLI
mysqldump -u root -p si_akademik > database/backup_si_akademik_acara26.sql

# Restore basis data via CLI
mysql -u root -p si_akademik < database/backup_si_akademik_acara26.sql
```
File cadangan resmi tersimpan di: `database/backup_si_akademik_acara26.sql`.
