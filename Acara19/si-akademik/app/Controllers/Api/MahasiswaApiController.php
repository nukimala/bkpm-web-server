<?php
// app/Controllers/Api/MahasiswaApiController.php
// API CRUD lengkap mahasiswa (Acara 17): memakai Service Layer sehingga
// validasi + business logic otomatis berjalan, error validasi -> 422.
require_once __DIR__ . '/../../Core/ApiResponse.php';
require_once __DIR__ . '/../../Core/ApiRouter.php';
require_once __DIR__ . '/../../Services/MahasiswaService.php';

class MahasiswaApiController
{
    private MahasiswaService $service;

    public function __construct()
    {
        $this->service = new MahasiswaService(Database::getInstance());
    }

    // GET /api/mahasiswa
    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');
        ApiResponse::success($this->service->index($search));
    }

    // GET /api/mahasiswa/{id}
    public function show(string $id): void
    {
        $mhs = $this->service->find((int)$id);
        if ($mhs === null) {
            ApiResponse::error('Mahasiswa tidak ditemukan.', 404);
        }
        ApiResponse::success($mhs);
    }

    // POST /api/mahasiswa
    public function store(): void
    {
        $result = $this->service->create(ApiRouter::input());

        if (!$result['ok']) {
            ApiResponse::error('Data mahasiswa gagal divalidasi.', 422, $result['errors']);
        }
        ApiResponse::success([], 'Data mahasiswa berhasil dibuat.', 201);
    }

    // PUT /api/mahasiswa/{id}
    public function update(string $id): void
    {
        if ($this->service->find((int)$id) === null) {
            ApiResponse::error('Mahasiswa tidak ditemukan.', 404);
        }

        $result = $this->service->update((int)$id, ApiRouter::input());
        if (!$result['ok']) {
            ApiResponse::error('Data mahasiswa gagal divalidasi.', 422, $result['errors']);
        }
        ApiResponse::success([], 'Data mahasiswa berhasil diperbarui.');
    }

    // DELETE /api/mahasiswa/{id}
    public function delete(string $id): void
    {
        $result = $this->service->delete((int)$id);

        if (!$result['ok']) {
            $first = reset($result['errors']);
            $message = $first[0] ?? 'Data gagal dihapus.';
            $status = str_contains($message, 'ditemukan') ? 404 : 422;
            ApiResponse::error($message, $status, $result['errors']);
        }
        ApiResponse::noContent();
    }
}