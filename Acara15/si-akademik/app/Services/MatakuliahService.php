<?php
// app/Services/MatakuliahService.php
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Repositories/MatakuliahRepository.php';

class MatakuliahService
{
    private MatakuliahRepository $repository;
    private Validator $validator;

    public function __construct(Database $database)
    {
        $this->repository = new MatakuliahRepository($database);
        $this->validator = new Validator();
    }

    private function rules(): array
    {
        return [
            'kode' => 'required|max:10',
            'nama' => 'required|min:3|max:100',
            'sks'  => 'required|numeric|range:1,6',
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
            $errors['kode'][] = 'Kode matakuliah sudah digunakan.';
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
            $errors['kode'][] = 'Kode matakuliah sudah digunakan data lain.';
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