<?php
// app/Controllers/MahasiswaController.php
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class MahasiswaController extends BaseController
{
    public function __construct(
        private MahasiswaRepository $repo,
        private ProdiRepository $prodiRepo
    ) {
        AuthMiddleware::requireLogin($this->basePath, false);
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
        $this->view('mahasiswa/create', [
            'prodi'     => $this->prodiRepo->options(),
            'statuses'  => ['aktif', 'cuti', 'lulus'],
            'old'       => [],
        ]);
    }

    public function store(): void
    {
        $data = $this->dataDariForm();

        try {
            $this->repo->create($data);
            $this->redirect('/mahasiswa', 'Data mahasiswa berhasil ditambahkan.');
        } catch (InvalidArgumentException $e) {
            $this->redirect('/mahasiswa/create', null, $e->getMessage());
        } catch (PDOException $e) {
            $this->redirect('/mahasiswa/create', null, 'Gagal menyimpan: ' . $this->pesanError($e));
        }
    }

    public function show(string $nim): void
    {
        $this->view('mahasiswa/show', [
            'mhs' => $this->repo->findByNim($nim),
            'nim' => $nim,
        ]);
    }

    public function edit(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/mahasiswa', null, 'Id mahasiswa tidak valid.');
        }

        $mhs = $this->repo->find((int) $id);
        if ($mhs === null) {
            $this->redirect('/mahasiswa', null, 'Mahasiswa tidak ditemukan.');
        }

        $this->view('mahasiswa/edit', [
            'mhs'      => $mhs,
            'prodi'    => $this->prodiRepo->options(),
            'statuses' => ['aktif', 'cuti', 'lulus'],
        ]);
    }

    public function update(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/mahasiswa', null, 'Id mahasiswa tidak valid.');
        }

        $data = $this->dataDariForm();

        try {
            $this->repo->update((int) $id, $data);
            $this->redirect('/mahasiswa', 'Data mahasiswa berhasil diperbarui.');
        } catch (InvalidArgumentException $e) {
            $this->redirect('/mahasiswa/edit/' . (int) $id, null, $e->getMessage());
        } catch (PDOException $e) {
            $this->redirect(
                '/mahasiswa/edit/' . (int) $id,
                null,
                'Gagal memperbarui: ' . $this->pesanError($e)
            );
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