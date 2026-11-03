<?php
// app/Controllers/Api/MatakuliahApiController.php
// RESTful API matakuliah (Acara 16): GET/POST/PUT/DELETE dengan respon JSON.
require_once __DIR__ . '/../../Core/ApiResponse.php';
require_once __DIR__ . '/../../Core/ApiRouter.php';
require_once __DIR__ . '/../../Repositories/MatakuliahRepository.php';

class MatakuliahApiController
{
    private MatakuliahRepository $repo;

    public function __construct()
    {
        $this->repo = new MatakuliahRepository(Database::getInstance());
    }

    public function index(): void
    {
        ApiResponse::success($this->repo->all());
    }

    public function show(string $id): void
    {
        $item = $this->repo->find((int)$id);
        if ($item === null) {
            ApiResponse::error('Matakuliah tidak ditemukan.', 404);
        }
        ApiResponse::success($item);
    }

    public function store(): void
    {
        $data = ApiRouter::input();
        if ($this->repo->create($data)) {
            ApiResponse::success([], 'Data matakuliah berhasil dibuat.', 201);
        }
        ApiResponse::error('Gagal menyimpan data matakuliah.', 500);
    }

    public function update(string $id): void
    {
        if ($this->repo->find((int)$id) === null) {
            ApiResponse::error('Matakuliah tidak ditemukan.', 404);
        }
        $data = ApiRouter::input();
        if ($this->repo->update((int)$id, $data)) {
            ApiResponse::success([], 'Data matakuliah berhasil diperbarui.');
        }
        ApiResponse::error('Gagal memperbarui data matakuliah.', 500);
    }

    public function delete(string $id): void
    {
        if ($this->repo->find((int)$id) === null) {
            ApiResponse::error('Matakuliah tidak ditemukan.', 404);
        }
        if ($this->repo->delete((int)$id)) {
            ApiResponse::noContent();
        }
        ApiResponse::error('Gagal menghapus data matakuliah.', 500);
    }
}