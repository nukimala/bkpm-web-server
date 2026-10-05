<?php
// app/Controllers/MahasiswaController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class MahasiswaController extends Controller
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    public function __construct(Database $db)
    {
        AuthMiddleware::requireLogin($this->basePath, false);

        $this->repo     = new MahasiswaRepository($db);
        $this->prodiRepo = new ProdiRepository($db);
    }

    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');
        $page    = max(1, (int) ($_GET['page'] ?? 1));

        $result = $this->repo->paginate($page, $keyword);

        $this->view('mahasiswa/index', [
            'mahasiswa' => $result['data'],
            'total'     => $result['total'],
            'pages'     => $result['pages'],
            'page'      => $result['page'],
            'offset'    => $result['offset'],
            'keyword'   => $keyword,
        ]);
    }

    public function create(): void
    {
        $this->view('mahasiswa/create', ['prodi' => $this->prodiRepo->options()]);
    }

    public function store(): void
    {
        $data = $this->dataDariForm();

        if ($data['nim'] === '' || $data['nama'] === '') {
            $this->redirect('/mahasiswa/create', null, 'NIM dan nama wajib diisi.');
        }
        if ($data['email'] === '' || $data['prodi_id'] === 0 || $data['angkatan'] === 0) {
            $this->redirect('/mahasiswa/create', null, 'Email, prodi, dan angkatan wajib diisi.');
        }
        if (!ctype_digit($data['nim'])) {
            $this->redirect('/mahasiswa/create', null, 'NIM harus berupa angka.');
        }
        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->redirect('/mahasiswa/create', null, 'Format email tidak valid.');
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
        $this->view('mahasiswa/show', ['mhs' => $this->repo->findByNim($nim)]);
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

        $this->view('mahasiswa/edit', [
            'mhs'   => $mhs,
            'prodi' => $this->prodiRepo->options(),
        ]);
    }

    public function update(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/mahasiswa', null, 'Id mahasiswa tidak valid.');
        }

        $data = $this->dataDariForm();
        if ($data['nim'] === '' || $data['nama'] === '' || $data['email'] === '') {
            $this->redirect('/mahasiswa', null, 'NIM, nama, dan email wajib diisi.');
        }
        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->redirect('/mahasiswa/edit/' . (int) $id, null, 'Format email tidak valid.');
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

    private function dataDariForm(): array
    {
        return [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0),
            'status'   => $_POST['status'] ?? 'aktif',
        ];
    }
}