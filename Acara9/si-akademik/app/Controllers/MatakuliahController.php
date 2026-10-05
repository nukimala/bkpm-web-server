<?php
// app/Controllers/MatakuliahController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Repositories/MatakuliahRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class MatakuliahController extends Controller
{
    private MatakuliahRepository $repo;
    private ProdiRepository $prodiRepo;

    public function __construct(Database $db)
    {
        AuthMiddleware::requireLogin($this->basePath, false);

        $this->repo      = new MatakuliahRepository($db);
        $this->prodiRepo = new ProdiRepository($db);
    }

    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');
        $page    = max(1, (int) ($_GET['page'] ?? 1));

        $result = $this->repo->paginate($page, $keyword);

        $this->view('matakuliah/index', [
            'matakuliah' => $result['data'],
            'total'      => $result['total'],
            'pages'      => $result['pages'],
            'page'       => $result['page'],
            'offset'     => $result['offset'],
            'keyword'    => $keyword,
        ]);
    }

    public function create(): void
    {
        $this->view('matakuliah/create', ['prodi' => $this->prodiRepo->options()]);
    }

    public function store(): void
    {
        $data = $this->dataDariForm();

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

        $this->view('matakuliah/edit', [
            'matakuliah' => $matakuliah,
            'prodi'      => $this->prodiRepo->options(),
        ]);
    }

    public function update(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/matakuliah', null, 'Id matakuliah tidak valid.');
        }

        $data = $this->dataDariForm();
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

    private function dataDariForm(): array
    {
        return [
            'kode'     => trim($_POST['kode'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];
    }
}