<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaController extends Controller
{
    private array $data;

    public function __construct()
    {
        $this->data = [
            1 => new Mahasiswa('2401001', 'Budi Santoso', 'Teknik Informatika'),
            2 => new Mahasiswa('2401002', 'Ani Wijaya', 'Teknik Informatika'),
            3 => new Mahasiswa('2402001', 'Citra Lestari', 'Sistem Informasi'),
            4 => new Mahasiswa('2501003', 'Dedi Kurniawan', 'Teknik Informatika'),
            5 => new Mahasiswa('2502002', 'Eka Pratiwi', 'Sistem Informasi'),
        ];
    }

    public function index(): void
    {
        $this->render('mahasiswa/index.php', ['mahasiswa' => $this->data]);
    }

    public function create(): void
    {
        $this->render('mahasiswa/create.php');
    }

    public function show(string $id): void
    {
        if (!ctype_digit($id)) {
            http_response_code(404);
            echo '404 - Halaman tidak ditemukan';
            return;
        }

        $this->render('mahasiswa/show.php', ['mhs' => $this->data[(int) $id] ?? null]);
    }
}