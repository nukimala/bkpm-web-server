<?php
// app/Controllers/Api/MatakuliahApiController.php
// API CRUD lengkap matakuliah (Acara 17): memakai Service Layer (validasi + business logic).
require_once __DIR__ . '/../../Core/ApiResponse.php';
require_once __DIR__ . '/../../Core/ApiRouter.php';
require_once __DIR__ . '/../../Services/MatakuliahService.php';

class MatakuliahApiController
{
    private MatakuliahService $service;

    public function __construct()
    {
        $this->service = new MatakuliahService(Database::getInstance());
    }

    public function index(): void
    {
        ApiResponse::success($this->service->index());
    }

    public function show(string $id): void
    {
        $item = $this->service->find((int)$id);
        if ($item === null) {
            ApiResponse::error('Matakuliah tidak ditemukan.', 404);
        }
        ApiResponse::success($item);
    }

    public function store(): void
    {
        $result = $this->service->create(ApiRouter::input());

        if (!$result['ok']) {
            ApiResponse::error('Data matakuliah gagal divalidasi.', 422, $result['errors']);
        }
        ApiResponse::success([], 'Data matakuliah berhasil dibuat.', 201);
    }

    public function update(string $id): void
    {
        if ($this->service->find((int)$id) === null) {
            ApiResponse::error('Matakuliah tidak ditemukan.', 404);
        }

        $result = $this->service->update((int)$id, ApiRouter::input());
        if (!$result['ok']) {
            ApiResponse::error('Data matakuliah gagal divalidasi.', 422, $result['errors']);
        }
        ApiResponse::success([], 'Data matakuliah berhasil diperbarui.');
    }

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