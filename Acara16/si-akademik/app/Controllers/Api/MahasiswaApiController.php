<?php
// app/Controllers/Api/MahasiswaApiController.php
// RESTful API mahasiswa (Acara 16): GET/POST/PUT/DELETE dengan respon JSON.
require_once __DIR__ . '/../../Core/ApiResponse.php';
require_once __DIR__ . '/../../Core/ApiRouter.php';
require_once __DIR__ . '/../../Repositories/MahasiswaRepository.php';

class MahasiswaApiController
{
    private MahasiswaRepository $repo;

    public function __construct()
    {
        $this->repo = new MahasiswaRepository(Database::getInstance());
    }

    // GET /api/mahasiswa
    public function index(): void
    {
        ApiResponse::success($this->repo->all());
    }

    // GET /api/mahasiswa/{id}
    public function show(string $id): void
    {
        $mhs = $this->repo->find((int)$id);
        if ($mhs === null) {
            ApiResponse::error('Mahasiswa tidak ditemukan.', 404);
        }
        ApiResponse::success($mhs);
    }

    // POST /api/mahasiswa  (data dikirim sebagai JSON)
    public function store(): void
    {
        $data = ApiRouter::input();
        $errors = $this->validate($data);
        if (!empty($errors)) {
            ApiResponse::error('Data mahasiswa gagal divalidasi.', 422, $errors);
        }
        if ($this->repo->create($data)) {
            ApiResponse::success([], 'Data mahasiswa Successfully created.', 201);
        }
        ApiResponse::error('Gagal menyimpan data mahasiswa.', 500);
    }

    // PUT /api/mahiswa/{id}
    public function update(string $id): void
    {
        if ($this->repo->find((int)$id) === null) {
            ApiResponse::error('Mahasiswa tidak ditemukan.', 404);
        }
        $data = ApiRouter::input();
        $errors = $this->validate($data);
        if (!empty($errors)) {
            ApiResponse::error('Data mahasiswa gagal divalidasi.', 422, $errors);
        }
        if ($this->repo->update((int)$id, $data)) {
            ApiResponse::success([], 'Data mahasiswa Successfully updated.');
        }
        ApiResponse::error('Gagal memperbarui data mahasiswa.', 500);
    }

    // DELETE /api/mahiswa/{id}
    public function delete(string $id): void
    {
        if ($this->repo->find((int)$id) === null) {
            ApiResponse::error('Mahasiswa tidak ditemukan.', 404);
        }
        if ($this->repo->delete((int)$id)) {
            ApiResponse::noContent();
        }
        ApiResponse::error('Gagal menghapus data mahasiswa.', 500);
    }

    // Validasi sederhana (Acara 16: pelengkapan error handling).
    private function validate(array $data): array
    {
        $errors = [];
        if (empty($data['nim'] ?? '')) {
            $errors['nim'] = ['NIM tidak boleh kosong.'];
        }
        if (empty($data['nama'] ?? '')) {
            $errors['nama'] = ['Nama tidak boleh kosong.'];
        }
        if (empty($data['prodi_id'] ?? '')) {
            $errors['prodi_id'] = ['Prodi tidak boleh kosong.'];
        }
        if (empty($data['status'] ?? '')) {
            $errors['status'] = ['Status tidak boleh kosong.'];
        }
        return $errors;
    }
}