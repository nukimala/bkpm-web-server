# Dokumen Rencana Pengujian API (Test Cases)
**Proyek:** SI-Kepegawaian RESTful API  
**Standar Kurikulum:** BKPM Acara 23 (Software QA & Testing)

---

## 1. Lingkungan Pengujian
* **URL Pengujian:** `http://localhost/webserver/si-kepegawaian/public`
* **Tools Pengujian:** Postman / cURL CLI / PHP Native Testing
* **Metrik Keberhasilan:** Seluruh respon sesuai dengan HTTP Status Code dan JSON Envelope yang diharapkan.

---

## 2. Matriks Kasus Uji (Test Matrix)

### A. Pengujian Positif (Happy Path)
| ID TC | Skenario Uji | Method & Endpoint | Input Data | Status Diharapkan | Status Aktual |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-01** | Cek kesiapan server (Ping) | `GET /api/ping` | None | `200 OK` | **PASS** |
| **TC-02** | Mengambil semua daftar pegawai | `GET /api/pegawai` | None | `200 OK` | **PASS** |
| **TC-03** | Mengambil detail pegawai valid | `GET /api/pegawai/1` | None | `200 OK` | **PASS** |
| **TC-04** | Menambah pegawai baru | `POST /api/pegawai` | JSON lengkap & NIP baru | `201 Created` | **PASS** |
| **TC-05** | Memperbarui data pegawai | `PUT /api/pegawai/{id}` | JSON update valid | `200 OK` | **PASS** |
| **TC-06** | Menghapus pegawai | `DELETE /api/pegawai/{id}` | ID valid | `200 OK` | **PASS** |

---

### B. Pengujian Negatif (Negative & Edge Cases)
| ID TC | Skenario Uji | Method & Endpoint | Input Data | Status Diharapkan | Status Aktual |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-07** | Cari pegawai ID tidak ada | `GET /api/pegawai/999` | ID 999 | `404 Not Found` | **PASS** |
| **TC-08** | Tambah pegawai kolom wajib kosong | `POST /api/pegawai` | `{"nama": "Tes"}` (NIP kosong) | `400 Bad Request` | **PASS** |
| **TC-09** | Tambah pegawai format email salah | `POST /api/pegawai` | `email: "bukan-email"` | `400 Bad Request` | **PASS** |
| **TC-10** | Tambah pegawai dengan NIP kembar | `POST /api/pegawai` | NIP yang sudah ada di DB | `400 Bad Request` | **PASS** |
| **TC-11** | Update pegawai ID tidak ditemukan | `PUT /api/pegawai/999` | Data valid | `404 Not Found` | **PASS** |
| **TC-12** | Hapus pegawai ID tidak ditemukan | `DELETE /api/pegawai/999`| ID 999 | `404 Not Found` | **PASS** |
| **TC-13** | Request ke rute yang tidak terdaftar | `GET /api/ngawur` | None | `404 Not Found` | **PASS** |

---

## 3. Kesimpulan Pengujian
Semua skenario positif dan negatif telah ditangani secara terstruktur oleh `Router.php`, `PegawaiController.php`, dan `Response.php` dengan HTTP Status Code yang tepat dan tidak ada kebocoran error fatal PHP (Uncaught Exception).