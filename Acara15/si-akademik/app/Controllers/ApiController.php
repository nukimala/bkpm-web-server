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
                'GET  /api/mahasiswa',
                'GET  /api/mahasiswa?id=1',
                'GET  /api/mahasiswa/{nim}',
                'POST /api/mahasiswa',
                'GET  /api/prodi',
                'GET  /api/matakuliah',
            ],
            'waktu'    => date('c'),
        ], 'API berjalan normal.');
    }

    public function mahasiswa(): void
    {
        $id = $_GET['id'] ?? null;

        if ($id !== null && $id !== '') {
            if (!ctype_digit((string)$id)) {
                ApiResponse::error('Parameter id tidak valid.', 400);
                return;
            }
            $mhs = $this->mahasiswa->find((int)$id);
            if ($mhs === null) {
                ApiResponse::error('Mahasiswa dengan id ' . $id . ' tidak ditemukan.', 404);
            }
            ApiResponse::success($mhs);
        }

        ApiResponse::success($this->mahasiswa->all());
    }

    public function mahasiswaStore(): void
    {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw ?: '', true);

        if (!is_array($input)) {
            ApiResponse::error('Body JSON tidak valid.', 400);
            return;
        }

        $errors = [];
        $nim    = trim((string)($input['nim'] ?? ''));
        $nama   = trim((string)($input['nama'] ?? ''));
        $prodiId = (int)($input['prodi_id'] ?? 0);

        if ($nim === '' || !ctype_digit($nim)) {
            $errors['nim'] = 'NIM wajib diisi dan harus berupa angka.';
        } elseif ($this->mahasiswa->findByNim($nim) !== null) {
            $errors['nim'] = 'NIM sudah terdaftar.';
        }

        if ($nama === '') {
            $errors['nama'] = 'Nama wajib diisi.';
        }

        if ($prodiId <= 0 || $this->prodi->find($prodiId) === null) {
            $errors['prodi_id'] = 'Program studi tidak ditemukan.';
        }

        $status = $input['status'] ?? 'aktif';
        if (!in_array($status, ['aktif', 'nonaktif'], true)) {
            $errors['status'] = 'Status harus aktif atau nonaktif.';
        }

        if (!empty($errors)) {
            ApiResponse::error('Data tidak valid.', 400, $errors);
            return;
        }

        try {
            $created = $this->mahasiswa->create([
                'nim'      => $nim,
                'nama'     => $nama,
                'prodi_id' => $prodiId,
                'status'   => $status,
                'alamat'   => $input['alamat'] ?? '',
            ]);
        } catch (Throwable $e) {
            ApiResponse::error('Gagal menyimpan data mahasiswa.', 500);
            return;
        }

        if (!$created) {
            ApiResponse::error('Gagal menyimpan data mahasiswa.', 500);
            return;
        }

        ApiResponse::success(
            $this->mahasiswa->findByNim($nim),
            'Data mahasiswa berhasil ditambahkan',
            201
        );
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