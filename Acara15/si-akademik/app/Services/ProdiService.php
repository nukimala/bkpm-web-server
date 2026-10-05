<?php
// app/Services/ProdiService.php
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';

class ProdiService
{
    private ProdiRepository $repository;
    private MahasiswaRepository $mahasiswaRepository;
    private Validator $validator;

    public function __construct(Database $database)
    {
        $this->repository = new ProdiRepository($database);
        $this->mahasiswaRepository = new MahasiswaRepository($database);
        $this->validator = new Validator();
    }

    private function rules(): array
    {
        return [
            'kode'     => 'required|max:10',
            'nama'     => 'required|min:3|max:100',
            'ka_prodi' => 'max:100',
        ];
    }

    public function index(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): ?array
    {
        return $this->repository->find($id);
    }

    public function create(array $data): array
    {
        $errors = $this->validator->validate($data, $this->rules());

        if ($errors === [] && $this->kodeExists($data['kode'] ?? '')) {
            $errors['kode'][] = 'Kode prodi sudah digunakan.';
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors];
        }

        $this->repository->create($data);
        return ['ok' => true, 'errors' => []];
    }

    public function update(int $id, array $data): array
    {
        $errors = $this->validator->validate($data, $this->rules());

        $existing = $this->repository->find($id);
        $taken = $this->findByKode($data['kode'] ?? '');
        if ($errors === []
            && $existing !== null
            && $taken !== null
            && (int)$taken['id'] !== $id) {
            $errors['kode'][] = 'Kode prodi sudah digunakan prodi lain.';
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors];
        }

        $this->repository->update($id, $data);
        return ['ok' => true, 'errors' => []];
    }

    public function delete(int $id): array
    {
        if ($this->repository->find($id) === null) {
            return ['ok' => false, 'errors' => ['id' => ['Data tidak ditemukan.']]];
        }

        if ($this->mahasiswaRepository->countByProdi($id) > 0) {
            return [
                'ok' => false,
                'errors' => ['id' => ['Prodi masih memiliki mahasiswa aktif, tidak dapat dihapus.']],
            ];
        }

        $this->repository->delete($id);
        return ['ok' => true, 'errors' => []];
    }

    private function kodeExists(string $kode): bool
    {
        return $this->findByKode($kode) !== null;
    }

    private function findByKode(string $kode): ?array
    {
        foreach ($this->repository->all() as $row) {
            if ($row['kode'] === $kode) {
                return $row;
            }
        }
        return null;
    }
}