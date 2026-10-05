<?php
// app/Controllers/MatakuliahController.php
require_once __DIR__ . '/../Repositories/MatakuliahRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class MatakuliahController
{
    private string $base_path = BASE_PATH;
    private MatakuliahRepository $repo;

    public function __construct()
    {
        AuthMiddleware::requireLogin($this->base_path, false);
        $this->repo = new MatakuliahRepository();
    }

    private function redirect(string $to, ?string $flash = null, ?string $error = null): void
    {
        if ($flash !== null) {
            $_SESSION['flash'] = $flash;
        }
        if ($error !== null) {
            $_SESSION['flash_error'] = $error;
        }
        header('Location: ' . $this->base_path . $to);
        exit;
    }

    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');
        $page    = max(1, (int) ($_GET['page'] ?? 1));

        $result     = $this->repo->paginate($page, $keyword);
        $matakuliah = $result['data'];
        $total      = $result['total'];
        $pages      = $result['pages'];
        $page       = $result['page'];
        $offset     = $result['offset'];

        $content = __DIR__ . '/../Views/matakuliah/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $prodi   = (new ProdiRepository())->options();
        $content = __DIR__ . '/../Views/matakuliah/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        $data = [
            'kode'     => trim($_POST['kode'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];

        if ($data['kode'] === '' || $data['nama'] === '') {
            $this->redirect('/matakuliah/create', null, 'Kode dan nama matakuliah wajib diisi.');
        }
        if ($data['sks'] < 1 || $data['sks'] > 6) {
            $this->redirect('/matakuliah/create', null, 'SKS harus antara 1 sampai 6.');
        }
        if ($data['prodi_id'] === 0) {
            $this->redirect('/matakuliah/create', null, 'Prodi wajib dipilih.');
        }

        try {
            $this->repo->create($data);
            $this->redirect('/matakuliah', 'Data matakuliah berhasil ditambahkan.');
        } catch (PDOException $e) {
            $this->redirect('/matakuliah/create', null, 'Gagal menyimpan: ' . $this->pesanError($e));
        }
    }

    public function edit(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/matakuliah', null, 'Id matakuliah tidak valid.');
        }

        $matakuliah = $this->repo->find((int) $id);
        if (!$matakuliah) {
            $this->redirect('/matakuliah', null, 'Matakuliah tidak ditemukan.');
        }

        $prodi   = (new ProdiRepository())->options();
        $content = __DIR__ . '/../Views/matakuliah/edit.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/matakuliah', null, 'Id matakuliah tidak valid.');
        }

        $data = [
            'kode'     => trim($_POST['kode'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];

        if ($data['kode'] === '' || $data['nama'] === '') {
            $this->redirect('/matakuliah', null, 'Kode dan nama matakuliah wajib diisi.');
        }

        try {
            $this->repo->update((int) $id, $data);
            $this->redirect('/matakuliah', 'Data matakuliah berhasil diperbarui.');
        } catch (PDOException $e) {
            $this->redirect('/matakuliah/edit/' . (int) $id, null, 'Gagal memperbarui: ' . $this->pesanError($e));
        }
    }

    public function delete(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/matakuliah', null, 'Id matakuliah tidak valid.');
        }

        try {
            $this->repo->delete((int) $id);
            $this->redirect('/matakuliah', 'Data matakuliah berhasil dihapus.');
        } catch (PDOException $e) {
            $this->redirect('/matakuliah', null, 'Gagal menghapus: ' . $this->pesanError($e));
        }
    }

    private function pesanError(PDOException $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Duplicate entry')) {
            return 'kode matakuliah sudah dipakai matakuliah lain.';
        }
        if (str_contains($message, 'foreign key constraint fails')) {
            return 'prodi yang dipilih tidak valid.';
        }
        return 'terjadi kesalahan pada database.';
    }
}