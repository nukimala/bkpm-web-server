<?php
// app/Controllers/MahasiswaController.php
require_once __DIR__ . '/../Models/MahasiswaModel.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class MahasiswaController
{
    private string $base_path = BASE_PATH;

    public function __construct()
    {
        AuthMiddleware::requireLogin($this->base_path, false);
    }

    public function index(): void
    {
        $config = require __DIR__ . '/../../config/database.php';
        $namaDatabase = $config['dbname'];

        $mahasiswa = MahasiswaModel::all();
        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $title = 'Tambah Mahasiswa';
        $content = __DIR__ . '/../Views/mahasiswa/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function show(string $nim): void
    {
        $mhs = MahasiswaModel::findByNim($nim);
        $content = __DIR__ . '/../Views/mahasiswa/show.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}