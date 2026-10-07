<?php
// app/Controllers/MahasiswaController.php
require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaController
{
    private array $data;

    public function __construct()
    {
        // Sementara: data di-hardcode. Di Acara 7-8 akan diambil dari database.
        $this->data = [
            1 => new Mahasiswa("2401001", "Budi Santoso", "Teknik Informatika"),
            2 => new Mahasiswa("2401002", "Ani Wijaya", "Teknik Informatika"),
            3 => new Mahasiswa("2402001", "Citra Lestari", "Sistem Informasi"),
            4 => new Mahasiswa("2501003", "Dedi Kurniawan", "Teknik Informatika"),
            5 => new Mahasiswa("2502002", "Eka Pratiwi", "Sistem Informasi"),
        ];
    }

    public function index(): void
    {
        $mahasiswa = $this->data;
        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    // Halaman form tambah mahasiswa. Data belum disimpan (belum ada database).
    public function create(): void
    {
        $title = 'Tambah Mahasiswa';
        $content = __DIR__ . '/../Views/mahasiswa/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    // Proses form tambah (POST). Belum ada database, jadi data hanya divalidasi
    // lalu di-redirect kembali ke daftar mahasiswa (pola redirect Acara 5).
    public function store(): void
    {
        $nim  = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');

        if ($nim === '' || $nama === '') {
            header('Location: ' . BASE_PATH . '/mahasiswa/create');
            exit; // penting! stop eksekusi agar kode di bawahnya tidak jalan
        }

        // Penyimpanan permanen ke database dibahas di Acara 7-8.
        header('Location: ' . BASE_PATH . '/mahasiswa?saved=1');
        exit;
    }

    // Tugas Mandiri: /mahasiswa/{id} -> menampilkan detail mahasiswa berdasarkan id.
    public function show(int $id): void
    {
        $mhs = $this->data[$id] ?? null;
        $content = __DIR__ . '/../Views/mahasiswa/show.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}