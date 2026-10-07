<?php
// app/Controllers/MahasiswaController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';
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

        if ($data['prodi_id'] === 0 || $data['angkatan'] === 0) {
            $this->redirect('/mahasiswa/create', null, 'Prodi dan angkatan wajib diisi.');
        }
        if ($data['email'] === '') {
            $this->redirect('/mahasiswa/create', null, 'Email wajib diisi.');
        }

        // Validasi NIM, nama, email, dan status dijalankan oleh setter class Mahasiswa.
        try {
            $mhs = new Mahasiswa(
                $data['nim'],
                $data['nama'],
                $data['email'],
                '',
                $data['status'],
                0,
                $data['prodi_id'],
                $data['angkatan']
            );
        } catch (InvalidArgumentException $e) {
            $this->redirect('/mahasiswa/create', null, $e->getMessage());
        }

        try {
            $this->repo->create($this->dariObject($mhs));
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

        if ($data['prodi_id'] === 0 || $data['angkatan'] === 0) {
            $this->redirect('/mahasiswa/edit/' . (int) $id, null, 'Prodi dan angkatan wajib diisi.');
        }
        if ($data['email'] === '') {
            $this->redirect('/mahasiswa/edit/' . (int) $id, null, 'Email wajib diisi.');
        }

        // Validasi lewat setter class Mahasiswa (jalur tulis create/update).
        try {
            $mhs = new Mahasiswa(
                $data['nim'],
                $data['nama'],
                $data['email'],
                '',
                $data['status'],
                (int) $id,
                $data['prodi_id'],
                $data['angkatan']
            );
        } catch (InvalidArgumentException $e) {
            $this->redirect('/mahasiswa/edit/' . (int) $id, null, $e->getMessage());
        }

        try {
            $this->repo->update((int) $id, $this->dariObject($mhs));
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

    // Data untuk repository diambil dari object Mahasiswa yang sudah lolos validasi setter.
    private function dariObject(Mahasiswa $mhs): array
    {
        return [
            'nim'      => $mhs->getNim(),
            'nama'     => $mhs->getNama(),
            'email'    => $mhs->getEmail(),
            'prodi_id' => $mhs->getProdiId(),
            'angkatan' => $mhs->getAngkatan(),
            'status'   => $mhs->getStatus(),
        ];
    }
}