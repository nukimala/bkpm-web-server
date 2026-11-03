<?php
// app/Controllers/ApiController.php
// Controller endpoint API Tahap 1 (Acara 15): mengembalikan data dalam format JSON.
// Belum ada autentikasi & belum mendukung metode POST/PUT/DELETE (menyusul Acara 16-17).
require_once __DIR__ . '/../Core/ApiResponse.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Repositories/MatakuliahRepository.php';

class ApiController
{
    private MahasiswaRepository $mahasiswa;
    private ProdiRepository $prodi;
    private MatakuliahRepository $matakuliah;

    public function __construct()
    {
        $database = Database::getInstance();
        $this->mahasiswa = new MahasiswaRepository($database);
        $this->prodi = new ProdiRepository($database);
        $this->matakuliah = new MatakuliahRepository($database);
    }

    // GET /api -> info dasar API dan format respons.
    public function info(): void
    {
        ApiResponse::success([
            'aplikasi' => 'SI Akademik API',
            'versi'    => '1.0.0',
            'format'   => 'JSON (application/json)',
            'endpoint' => [
                '/api/mahasiswa',
                '/api/mahasiswa/{nim}',
                '/api/prodi',
                '/api/matakuliah',
            ],
            'waktu'    => date('c'),
        ], 'API berjalan normal.');
    }

    // GET /api/mahasiswa -> seluruh data mahasiswa.
    public function mahasiswa(): void
    {
        ApiResponse::success($this->mahasiswa->all());
    }

    // GET /api/mahasiswa/{nim} -> detail satu mahasiswa.
    public function mahasiswaDetail(string $nim): void
    {
        $mhs = $this->mahasiswa->findByNim($nim);
        if ($mhs === null) {
            ApiResponse::error('Mahasiswa dengan NIM ' . $nim . ' tidak ditemukan.', 404);
        }
        ApiResponse::success($mhs);
    }

    // GET /api/prodi -> seluruh data prodi.
    public function prodi(): void
    {
        ApiResponse::success($this->prodi->all());
    }

    // GET /api/matakuliah -> seluruh data matakuliah.
    public function matakuliah(): void
    {
        ApiResponse::success($this->matakuliah->all());
    }
}