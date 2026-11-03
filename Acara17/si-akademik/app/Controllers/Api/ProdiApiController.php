<?php
require_once __DIR__ . '/../../Core/ApiResponse.php';
require_once __DIR__ . '/../../Core/ApiRouter.php';
require_once __DIR__ . '/../../Services/ProdiService.php';

class ProdiApiController
{
    private ProdiService $service;

    public function __construct()
    {
        $this->service = new ProdiService(Database::getInstance());
    }

    public function index(): void
    {
        ApiResponse::success($this->service->index());
    }

    public function show(string $id): void
    {
        $prodi = $this->service->find((int)$id);
        if ($prodi === null) {
            ApiResponse::error('Prodi tidak ditemukan.', 404);
        }
        ApiResponse::success($prodi);
    }

    public function store(): void
    {
        $result = $this->service->create(ApiRouter::input());

        if (!$result['ok']) {
            ApiResponse::error('Data prodi gagal divalidasi.', 422, $result['errors']);
        }
        ApiResponse::success([], 'Data prodi berhasil dibuat.', 201);
    }

    public function update(string $id): void
    {
        if ($this->service->find((int)$id) === null) {
            ApiResponse::error('Prodi tidak ditemukan.', 404);
        }

        $result = $this->service->update((int)$id, ApiRouter::input());
        if (!$result['ok']) {
            ApiResponse::error('Data prodi gagal divalidasi.', 422, $result['errors']);
        }
        ApiResponse::success([], 'Data prodi berhasil diperbarui.');
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