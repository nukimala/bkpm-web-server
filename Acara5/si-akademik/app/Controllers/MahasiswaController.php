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

    // Tugas Mandiri: /mahasiswa/{id} -> menampilkan detail mahasiswa berdasarkan id.
    public function show(int $id): void
    {
        $mhs = $this->data[$id] ?? null;
        $content = __DIR__ . '/../Views/mahasiswa/show.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}