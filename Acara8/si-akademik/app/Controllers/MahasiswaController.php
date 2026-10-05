<?php
// app/Controllers/MahasiswaController.php
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class MahasiswaController
{
    private string $base_path = BASE_PATH;
    private MahasiswaRepository $repo;

    public function __construct()
    {
        AuthMiddleware::requireLogin($this->base_path, false);
        $this->repo = new MahasiswaRepository();
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

        $result        = $this->repo->paginate($page, $keyword);
        $mahasiswa     = $result['data'];
        $total         = $result['total'];
        $pages         = $result['pages'];
        $page          = $result['page'];
        $offset        = $result['offset'];

        $content = __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $prodi   = (new ProdiRepository())->options();
        $content = __DIR__ . '/../Views/mahasiswa/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        $data = [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0),
            'status'   => $_POST['status'] ?? 'aktif',
        ];

        if ($data['nim'] === '' || $data['nama'] === '') {
            $this->redirect('/mahasiswa/create', null, 'NIM dan nama wajib diisi.');
        }
        if ($data['email'] === '' || $data['prodi_id'] === 0 || $data['angkatan'] === 0) {
            $this->redirect('/mahasiswa/create', null, 'Email, prodi, dan angkatan wajib diisi.');
        }

        try {
            $this->repo->create($data);
            $this->redirect('/mahasiswa', 'Data mahasiswa berhasil ditambahkan.');
        } catch (PDOException $e) {
            $this->redirect('/mahasiswa/create', null, 'Gagal menyimpan: ' . $this->pesanError($e));
        }
    }

    public function show(string $nim): void
    {
        $mhs     = $this->repo->findByNim($nim);
        $content = __DIR__ . '/../Views/mahasiswa/show.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function edit(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/mahasiswa', null, 'Id mahasiswa tidak valid.');
        }

        $mhs = $this->repo->find((int) $id);
        if (!$mhs) {
            $this->redirect('/mahasiswa', null, 'Mahasiswa tidak ditemukan.');
        }

        $prodi   = (new ProdiRepository())->options();
        $content = __DIR__ . '/../Views/mahasiswa/edit.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/mahasiswa', null, 'Id mahasiswa tidak valid.');
        }

        $data = [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0),
            'status'   => $_POST['status'] ?? 'aktif',
        ];

        if ($data['nim'] === '' || $data['nama'] === '' || $data['email'] === '') {
            $this->redirect('/mahasiswa', null, 'NIM, nama, dan email wajib diisi.');
        }

        try {
            $this->repo->update((int) $id, $data);
            $this->redirect('/mahasiswa', 'Data mahasiswa berhasil diperbarui.');
        } catch (PDOException $e) {
            $this->redirect('/mahasiswa/edit/' . (int) $id, null, 'Gagal memperbarui: ' . $this->pesanError($e));
        }
    }

    public function delete(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/mahasiswa', null, 'Id mahasiswa tidak valid.');
        }

        try {
            $this->repo->delete((int) $id);
            $this->redirect('/mahasiswa', 'Data mahasiswa berhasil dihapus.');
        } catch (PDOException $e) {
            $this->redirect('/mahasiswa', null, 'Gagal menghapus: ' . $this->pesanError($e));
        }
    }

    private function pesanError(PDOException $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Duplicate entry')) {
            return 'NIM sudah terdaftar, gunakan NIM lain.';
        }
        if (str_contains($message, 'foreign key constraint fails')) {
            return 'Prodi yang dipilih tidak valid.';
        }
        return 'terjadi kesalahan pada database.';
    }
}