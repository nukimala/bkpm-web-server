<?php
// app/Services/MahasiswaService.php
// Service Layer: business logic + validasi mahasiswa + manajemen upload file (Acara 13, 26).
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

    // Acara 26: Validasi keamanan & proses upload berkas foto
    public function handleUpload(?array $file, ?string $oldPhoto = null): array
    {
        if ($file === null || empty($file['tmp_name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return ['ok' => true, 'filename' => $oldPhoto];
        }

        if (($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Gagal mengunggah berkas foto.'];
        }

        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'], true)) {
            return ['ok' => false, 'error' => 'Berkas harus berupa gambar JPG atau PNG yang valid.'];
        }

        // Validasi signature gambar nyata (mencegah pemalsuan ekstensi skrip)
        $imageType = function_exists('exif_imagetype') ? @exif_imagetype($file['tmp_name']) : false;
        if (!$imageType && !@getimagesize($file['tmp_name'])) {
            return ['ok' => false, 'error' => 'Berkas bukan gambar yang valid (signature rusak).'];
        }

        // Batas ukuran 2MB
        if ($file['size'] > 2 * 1024 * 1024) {
            return ['ok' => false, 'error' => 'Ukuran berkas foto maksimal 2MB.'];
        }

        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $namaBaru = uniqid('foto_', true) . '.' . $ekstensi;
        $tujuan = $uploadDir . $namaBaru;

        if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
            return ['ok' => false, 'error' => 'Gagal menyimpan berkas ke direktori server.'];
        }

        // Tugas Praktik Mandiri 1: Hapus berkas foto lama menggunakan unlink()
        if ($oldPhoto !== null && $oldPhoto !== '') {
            $oldPath = $uploadDir . $oldPhoto;
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return ['ok' => true, 'filename' => $namaBaru];
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

    // Business logic: validasi + NIM harus unik + upload foto (Acara 26)
    public function create(array $data, ?array $file = null): array
    {
        $errors = $this->validator->validate($data, $this->rules());

        if ($errors === [] && $this->repository->findByNim($data['nim'] ?? '')) {
            $errors['nim'][] = 'NIM sudah terdaftar. Gunakan NIM lain.';
        }

        $fotoName = null;
        if ($file !== null && !empty($file['tmp_name'])) {
            $uploadResult = $this->handleUpload($file);
            if (!$uploadResult['ok']) {
                $errors['foto'][] = $uploadResult['error'];
            } else {
                $fotoName = $uploadResult['filename'];
            }
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors];
        }

        $data['foto'] = $fotoName;
        $this->repository->create($data);
        return ['ok' => true, 'errors' => []];
    }

    public function update(int $id, array $data, ?array $file = null): array
    {
        $errors = $this->validator->validate($data, $this->rules());

        $existing = $this->repository->findByNim($data['nim'] ?? '');
        if ($errors === [] && $existing !== null && $existing->getId() !== $id) {
            $errors['nim'][] = 'NIM sudah terdaftar untuk mahasiswa lain.';
        }

        $currentMhs = $this->repository->find($id);
        $oldPhoto = $currentMhs ? $currentMhs->getFoto() : null;
        $fotoName = $oldPhoto;

        if ($file !== null && !empty($file['tmp_name'])) {
            $uploadResult = $this->handleUpload($file, $oldPhoto);
            if (!$uploadResult['ok']) {
                $errors['foto'][] = $uploadResult['error'];
            } else {
                $fotoName = $uploadResult['filename'];
            }
        }

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors];
        }

        $data['foto'] = $fotoName;
        $this->repository->update($id, $data);
        return ['ok' => true, 'errors' => []];
    }

    public function delete(int $id): array
    {
        $mhs = $this->repository->find($id);
        if ($mhs === null) {
            return ['ok' => false, 'errors' => ['id' => ['Data tidak ditemukan.']]];
        }

        // Hapus foto terkait jika ada
        if ($mhs->getFoto()) {
            $path = __DIR__ . '/../../public/uploads/' . $mhs->getFoto();
            if (file_exists($path) && is_file($path)) {
                @unlink($path);
            }
        }

        $this->repository->delete($id);
        return ['ok' => true, 'errors' => []];
    }
}
