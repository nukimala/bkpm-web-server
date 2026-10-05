<?php
// app/Controllers/ProdiController.php
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class ProdiController
{
    private string $base_path = BASE_PATH;
    private ProdiRepository $repo;

    public function __construct()
    {
        AuthMiddleware::requireLogin($this->base_path, false);
        $this->repo = new ProdiRepository();
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

        $result  = $this->repo->paginate($page, $keyword);
        $prodi   = $result['data'];
        $total   = $result['total'];
        $pages   = $result['pages'];
        $page    = $result['page'];
        $offset  = $result['offset'];

        $content = __DIR__ . '/../Views/prodi/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $content = __DIR__ . '/../Views/prodi/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
        ];

        if ($data['kode'] === '' || $data['nama'] === '') {
            $this->redirect('/prodi/create', null, 'Kode dan nama prodi wajib diisi.');
        }

        try {
            $this->repo->create($data);
            $this->redirect('/prodi', 'Data prodi berhasil ditambahkan.');
        } catch (PDOException $e) {
            $this->redirect('/prodi/create', null, 'Gagal menyimpan: ' . $this->pesanError($e));
        }
    }

    public function edit(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/prodi', null, 'Id prodi tidak valid.');
        }

        $prodi = $this->repo->find((int) $id);
        if (!$prodi) {
            $this->redirect('/prodi', null, 'Prodi tidak ditemukan.');
        }

        $content = __DIR__ . '/../Views/prodi/edit.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/prodi', null, 'Id prodi tidak valid.');
        }

        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
        ];

        if ($data['kode'] === '' || $data['nama'] === '') {
            $this->redirect('/prodi', null, 'Kode dan nama prodi wajib diisi.');
        }

        try {
            $this->repo->update((int) $id, $data);
            $this->redirect('/prodi', 'Data prodi berhasil diperbarui.');
        } catch (PDOException $e) {
            $this->redirect('/prodi/edit/' . (int) $id, null, 'Gagal memperbarui: ' . $this->pesanError($e));
        }
    }

    public function delete(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/prodi', null, 'Id prodi tidak valid.');
        }

        try {
            $this->repo->delete((int) $id);
            $this->redirect('/prodi', 'Data prodi berhasil dihapus.');
        } catch (PDOException $e) {
            // Prodi yang masih dipakai mahasiswa/matakuliah tidak boleh dihapus (FK RESTRICT).
            $this->redirect('/prodi', null, 'Gagal menghapus: ' . $this->pesanError($e));
        }
    }

    private function pesanError(PDOException $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Duplicate entry')) {
            return 'kode prodi sudah dipakai prodi lain.';
        }
        if (str_contains($message, 'foreign key constraint fails')) {
            return 'prodi masih dipakai mahasiswa atau matakuliah.';
        }
        return 'terjadi kesalahan pada database.';
    }
}