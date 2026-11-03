<?php
// app/Controllers/Api/ProdiApiController.php
// RESTful API prodi (Acara 16): GET/POST/PUT/DELETE dengan respon JSON.
require_once __DIR__ . '/../../Core/ApiResponse.php';
require_once __DIR__ . '/../../Core/ApiRouter.php';
require_once __DIR__ . '/../../Repositories/ProdiRepository.php';

class ProdiApiController
{
    private ProdiRepository $repo;

    public function __construct()
    {
        $this->repo = new ProdiRepository(Database::getInstance());
    }

    public function index(): void
    {
        ApiResponse::success($this->repo->all());
    }

    public function show(string $id): void
    {
        $prodi = $this->repo->find((int)$id);
        if ($prodi === null) {
            ApiResponse::error('Prodi tidak ditemukan.', 404);
        }
        ApiResponse::success($prodi);
    }

    public function store(): void
    {
        $data = ApiRouter::input();
        if ($this->repo->create($data)) {
            ApiResponse::success([], 'Data prodi berhasil dibuat.', 201);
        }
        ApiResponse::error('Gagal menyimpan data prodi.', 500);
    }

    public function update(string $id): void
    {
        if ($this->repo->find((int)$id) === null) {
            ApiResponse::error('Prodi tidak ditemukan.', 404);
        }
        $data = ApiRouter::input();
        if ($this->repo->update((int)$id, $data)) {
            ApiResponse::success([], 'Data prodi berhasil diperbarui.');
        }
        ApiResponse::error('Gagal memperbarui data prodi.', 500);
    }

    public function delete(string $id): void
    {
        if ($this->repo->find((int)$id) === null) {
            ApiResponse::error('Prodi tidak ditemukan.', 404);
        }
        if ($this->repo->delete((int)$id)) {
            ApiResponse::noContent();
        }
        ApiResponse::error('Gagal menghapus data prodi.', 500);
    }
}