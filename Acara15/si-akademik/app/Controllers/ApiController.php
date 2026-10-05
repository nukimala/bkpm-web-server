<?php
// app/Controllers/ApiController.php

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

    public function mahasiswa(): void
    {
        ApiResponse::success($this->mahasiswa->all());
    }

    public function mahasiswaDetail(string $nim): void
    {
        $mhs = $this->mahasiswa->findByNim($nim);
        if ($mhs === null) {
            ApiResponse::error('Mahasiswa dengan NIM ' . $nim . ' tidak ditemukan.', 404);
        }
        ApiResponse::success($mhs);
    }

    public function prodi(): void
    {
        ApiResponse::success($this->prodi->all());
    }

    public function matakuliah(): void
    {
        ApiResponse::success($this->matakuliah->all());
    }
}