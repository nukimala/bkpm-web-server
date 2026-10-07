<?php
// app/Services/MahasiswaService.php
// Service Layer: bertanggung jawab atas business logic + validasi mahasiswa (Acara 13).
// Controller menjadi "tipis": memanggil service, memutuskan arah halaman (PRG).
require_once __DIR__ . '/../Core/Validator.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class MahasiswaService
{
    private MahasiswaRepository $repository;
    private ProdiRepository $prodiRepository;
    private Validator $validator;

    public function __construct(Database $database)
    {
        $this->repository = new MahasiswaRepository($database);
        $this->prodiRepository = new ProdiRepository($database);
        $this->validator = new Validator();
    }

    // Aturan validasi bersama untuk create & update.
    private function rules(): array
    {
        return [
            'nim'       => 'required|digits|min:7|max:15',
            'nama'      => 'required|min:3|max:100',
            'prodi_id'  => 'required|numeric',
            'status'    => 'required|in:aktif,nonaktif',
            'alamat'    => 'max:255',
        ];
    }

    public function index(string $search = ''): array
    {
        return $search !== ''
            ? $this->repository->search($search)
            : $this->repository->all();
    }

    public function prodiList(): array
    {
        return $this->prodiRepository->all();
    }

    public function find(int $id): ?Mahasiswa
    {
        return $this->repository->find($id);
    }

    public function findByNim(string $nim): ?Mahasiswa
    {
        return $this->repository->findByNim($nim);
    }

    // Business logic: validasi + NIM harus unik.
    public function create(array $data): array
    {
        $errors = $this->validator->validate($data, $this->rules());

        if ($errors === [] && $this->repository->existsByNim($data['nim'] ?? '')) {
            $errors['nim'][] = 'NIM sudah terdaftar. Gunakan NIM lain.';
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

        if ($errors === [] && $this->repository->existsByNim($data['nim'] ?? '', $id)) {
            $errors['nim'][] = 'NIM sudah terdaftar untuk mahasiswa lain.';
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
}