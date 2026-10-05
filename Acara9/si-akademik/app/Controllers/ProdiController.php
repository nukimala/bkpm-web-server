<?php
// app/Controllers/ProdiController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class ProdiController extends Controller
{
    private ProdiRepository $repo;

    public function __construct(Database $db)
    {
        AuthMiddleware::requireLogin($this->basePath, false);
        $this->repo = new ProdiRepository($db);
    }

    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');
        $page    = max(1, (int) ($_GET['page'] ?? 1));

        $result = $this->repo->paginate($page, $keyword);

        $this->view('prodi/index', [
            'prodi'   => $result['data'],
            'total'   => $result['total'],
            'pages'   => $result['pages'],
            'page'    => $result['page'],
            'offset'  => $result['offset'],
            'keyword' => $keyword,
        ]);
    }

    public function create(): void
    {
        $this->view('prodi/create');
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

        $this->view('prodi/edit', ['prodi' => $prodi]);
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
}