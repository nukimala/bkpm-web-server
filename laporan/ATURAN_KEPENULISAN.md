# ATURAN KEPENULISAN (Writing Rules)

File ini adalah acuan wajib bagi AI (dan manusia) saat menyusun, mengedit, atau
melanjutkan dokumen di folder `sqa/`, agar semua keluaran selaras satu sama lain.

## 0. Hierarki Aturan

Jika ada aturan yang bertabrakan, ikuti urutan prioritas berikut:

1. Instruksi langsung dari pengguna pada sesi berjalan.
2. `BUKU PEDOMAN KARYA TULIS ILMIAH.pdf` (pedoman resmi Politeknik Negeri Jember / POLIJE).
3. File ini (`ATURAN_KEPENULISAN.md`) — ringkasan aturan agar cepat diterapkan.

Jangan mengubah keputusan proyek yang sudah dikunci; catatannya ada di
`../docs/CATATAN_KESESUAIAN.md`.

---

## 1. Identitas Bersama (Sampul Semua Dokumen)

Urutan baris sampul wajib sama persis:

cover fix.docx

Catatan penulisan nama pada sampul:

- Nama ditulis HURUF KAPITAL, diikuti NIM dalam kurung — format
  `NAMA (E4125xxxx)`.

---

## 2. Format Fisik (untuk output .docx)

- Kertas **A4** (21 cm × 29,7 cm), HVS 80 gram.
- Margin: **atas 4 cm, kiri 4 cm, kanan 3 cm, bawah 3 cm**.
- Font: **Times New Roman 12 pt** (kecuali yang dikecualikan di bawah).
- Spasi:
  - Naskah/paragraf biasa: **1,5 spasi**.
  - Abstrak, kutipan langsung, judul subbab, judul tabel, judul gambar: **1 spasi**.
  - Antara judul bab/subbab/subsubbab dengan uraian pertamanya: **3 spasi**.
  - Antara entri pada Daftar Pustaka: **2 spasi**.
  - Setelah tabel/gambar sebelum paragraf berikutnya: **3 spasi**.
- Awal paragraf: tab **1 cm** (5 ketukan) dari margin kiri.
- Setiap BAB dibuka pada **halaman baru**.

Pengecualian font:

- Kode program / algoritma: **Courier New 10 pt, spasi 1**.
- di dalam tabel 1 kolom dengan border 
- Catatan kaki (footnote): **10 pt**.

---

## 3. Struktur & Penomoran Judul

### 3.1 Gaya "BAB" (Laporan & Test Scenario/Case)

- Judul bab: `BAB 1. PENDAHULUAN` — HURUF KAPITAL, **tebal**, rata tengah,
  diakhiri titik pada nomor, tanpa titik di akhir judul.
- Subbab: `1.1 Latar Belakang` — rata kiri, **Title Case**, tebal.
- Subsubbab: `2.1.1 Portal Website MTs Al-Huda` — rata kiri, Title Case,
  **tidak tebal**.
- Setelah subsubbab habis: penomoran lanjutan `a.`, `1)`, `a)`, `(1)`, `(a)`.

### 3.2 Gaya numerik tanpa "BAB" (Test Plan & User Requirement)

- Judul bagian: `1 PENDAHULUAN`, `2 TEST METHODOLOGY` — HURUF KAPITAL,
  rata kiri/tebal mengikuti dokumen acuan (tanpa kata "BAB" dan tanpa titik).
- Sub: `1.1 Scope`, `1.1.1 In Scope` — Title Case.
- Nomor tidak diakhiri titik kecuali gaya BAB (titik setelah nomor bab).

### 3.3 Struktur wajib per dokumen

**LAPORAN** (`LAPORAN_FINAL.docx`):

1. `BAB 1. PENDAHULUAN` 
2. `BAB 2. HASIL PRAKTIK` - 2.1 DASAR TEORI 2.1.(n) sub pokok bahasan 2.2 LANGKAH PENGERJAAN 2.3 HASIL DAN PEMBAHASAN 2.4 LAMPIRAN CODE 2.4.1 struktur folder project 2.4.2 source code
3. `BAB 3. KESIMPULAN` 
4. `DAFTAR PUSTAKA`

Bagian awal umum: halaman sampul → (Changelog) → `DAFTAR ISI` → `DAFTAR TABEL`
→ isi → `DAFTAR PUSTAKA` → (Lampiran).

- Daftar Isi/Daftar Tabel dibuat otomatis; beri catatan:
  `[Daftar Isi tampil otomatis. Tekan Ctrl+A lalu F9 pada Word untuk memperbarui.]`
- Judul pada Daftar Isi: bab/khusus kapital+tebal; subbab Title Case+tebal.

---

## 4. Tabel, Gambar, dan Ilustrasi

- Penomoran: **`Tabel x.y`** dan **`Gambar x.y`** (x = nomor bab, y = urut di
  bab) — tanpa spasi setelah titik. Daftar memakai penomoran sama.
- Judul tabel berada di **ATAS** tubuh tabel; judul gambar di **BAWAH** gambar.
- Judul ditulis **Title Case** (huruf besar di awal kata, kecuali kata
  hubung/tunjuk/sambung), spasi 1.
- Setelah judul tabel dan setelah tabel: **3 spasi** sebelum paragraf berikut.
- Tabel **tidak boleh terpotong** antar halaman; jika terlalu besar, perkecil
  font di dalam tabel.
- Sumber data ditulis di bawah tabel: `Sumber: …` (font lebih kecil).
  Jika hasil adaptasi dokumen lain: `Sumber: disesuaikan dari Tabel …`.
- Kalimat rujukan dalam teks memakai huruf `T` kapital:
  `sebagaimana disajikan pada Tabel 2.1.`
- Selalu sediakan entri di **DAFTAR TABEL** untuk setiap tabel.

---

## 5. Sitasi (Innote) dan Daftar Pustaka

### 5.1 Dalam teks (sistem innote)

- Kutipan tidak langsung: `(Kumalasari dkk., 2024)` — nama + tahun saja.
- Kutipan langsung (verbatim): `(Nama, 2020: hal. 12)`.
- Jika nama mendahului kalimat: `Kumalasari dkk. (2024) menyatakan bahwa …`
  (nama di luar kurung, tahun di dalam kurung).
- Dua pengarang: `Pratiwi dan Prabowo (2021)`.
- Lebih dari tiga pengarang → **`dkk.`** untuk sumber Indonesia,
  **`et al.`** (miring bila memungkinkan) untuk sumber asing/Inggris.
- Sumber lembaga tanpa pengarang: `(ISO/IEC/IEEE, 2021)`.
- Kutipan dalam kutipan: `A (dalam B, 2021)`.
- Jangan pakai *ibid.*, *op. cit.*, *loc. cit.*.
- **Setiap sitasi wajib punya entri di Daftar Pustaka, dan sebaliknya.**

### 5.2 Daftar Pustaka (gaya POLIJE, sudah dipakai di LAPORAN_FINAL)

- Disusun **abjad** menurut nama pengarang/lembaga pertama, **tanpa nomor urut**,
  spasi antar entri 2, baris kedua masuk (hanging indent) ±1 cm.
- Urutan unsur: `Pengarang. Tahun. Judul. Kota: Penerbit.`
  (judul jurnal/artikel dalam tanda petik + `Dalam Nama Jurnal, vol(no). Hal. xx–yy.`)

```

- Nama pengarang: `Nama Belakang, Inisial.`; tanpa gelar akademik.
- Sumber yang dikutip harus benar-benar ada — **jangan mengarang referensi**.
  Jika sumber belum pasti, tandai `[PERLU VERIFIKASI]` dan laporkan ke pengguna.

---



---

---

## 8. Gaya Bahasa

- Bahasa Indonesia baku (EYD/PUEBI), kalimat efektif, ragam **ilmiah/formal**:
  tidak memakai gaya jurnalistik, sapaan, atau kata berlebihan.
- Orang: **penulis** (bukan "kami"/"saya" selain di Prakata).
- Paragraf: satu gagasan utama, 3–6 kalimat; hindari kalimat bertumpuk dengan
  "dan … dan …".
- Istilah asing teknis boleh memakai bahasa Inggris; tulis kepanjangannya saat
  kemunculan pertama, lalu singkatan:
  `Software Quality Assurance (SQA)` → `SQA`;
  `Requirement Traceability Matrix (RTM)` → `RTM`.
- Kalimat rujukan ke tabel/gambar selalu memakai pola:
  `… sebagaimana disajikan pada Tabel x.y.` /
  `… pada Tabel x.y berikut.`
- Angka: gunakan angka untuk data kuantitatif (≥ 95%, 6/6 platform, v0.2);
  kata angka untuk jumlah umum dalam kalimat.
- Pemisah ribuan memakai titik (`1.200`), desimal memakai koma (`4,5`).



- Daftar butir memakai penomoran `1.` atau bullet `•` konsisten; dalam laporan
  BAB 5 kesimpulan/saran memakai bullet `•`.
- Setiap tabel punya kalimat pengantar yang menyebutkan nomornya sebelum tabel.

---

## 9. Aturan Kerja File

1. **Backup dulu** sebelum mengedit besar: salin ke
   `NAMA_backup_<keterangan>.docx` (ikuti pola yang sudah ada,
   mis. `LAPORAN_backup_pre_3line_table.docx`).
2. Penamaan file dokumen utama: **HURUF BESAR + GARIS BAWAH** untuk jenis
   dokumen, lalu objek, mis. `TEST_PLAN_Website_MTs_Al-Huda_MADRASAHKU.docx`.
3. Dokumen hasil final diberi akhiran `_FINAL`; draf memakai `_draf_awal` /
   `_backup_*`.
4. Setelah menambah/menghapus judul atau tabel: perbarui **Daftar Isi** dan
   **Daftar Tabel** (Ctrl+A lalu F9 di Word).
5. Jangan mengubah dokumen yang dikunci/di-larang diubah oleh keputusan proyek
   (lihat `../docs/CATATAN_KESESUAIAN.md`).
6. Jika membutuhkan data yang tidak ada (angka, tanggal, nama), **tanyakan** —
   jangan mengarang. Tandai dengan `[PERLU DATA]` bila harus dibiarkan dulu.

---

## 10. Checklist Sebelum Selesai

- [ ] Sampul & identitas anggota sesuai bagian 1.
- [ ] Format: TNR 12, margin 4/4/3/3, spasi 1,5 (kecuali pengecualian).
- [ ] Struktur bab sesuai jenis dokumen (bagian 3.3).
- [ ] Penomoran `Tabel x.y` / `Gambar x.y`, judul di tempat yang benar, sumber
      di bawah tabel.
- [ ] Daftar Isi, Daftar Tabel, dan entri pustaka sinkron dengan isi.
- [ ] Semua sitasi punya pasangan di Daftar Pustaka (dan sebaliknya).
- [ ] ID mengikuti konvensi `BR-W/BR-A/TS/TC` + kolom ID Asal.
- [ ] Changelog bertambah satu baris bila ada perubahan.
- [ ] Istilah konsisten dengan tabel bagian 8; ejaan nama anggota konsisten.
- [ ] Tidak ada angka/fakta yang dikarang.
