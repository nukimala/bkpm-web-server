<?php
// app/Controllers/MatakuliahController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Services/MatakuliahService.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class MatakuliahController extends Controller
{
    private MatakuliahService $service;

    public function __construct(?MatakuliahService $service = null)
    {
        AuthMiddleware::requireLogin($this->base_path, false);
        $this->service = $service ?? new MatakuliahService(Database::getInstance());
    }

    public function index(): void
    {
        $this->view('matakuliah/index.php', ['matakuliah' => $this->service->index()]);
    }

    public function create(): void
    {
        $this->view('matakuliah/create.php');
    }

    public function store(): void
    {
        $this->onlyPost('/matakuliah');
        $this->verifyCsrf();
        $result = $this->service->create($_POST);

        if ($result['ok']) {
            $this->redirect('/matakuliah', 'Data matakuliah berhasil ditambahkan.');
        }

        $_SESSION['errors'] = $result['errors'];
        Session::withOldInput($_POST);
        $this->redirect('/matakuliah/create', 'Periksa kembali isian form Anda.');
    }

    public function edit(int $id): void
    {
        $matakuliah = $this->service->find($id);
        if (!$matakuliah) {
            $this->redirect('/matakuliah', 'Matakuliah tidak ditemukan.');
        }

        $this->view('matakuliah/edit.php', ['matakuliah' => $matakuliah]);
    }

    public function update(int $id): void
    {
        $this->onlyPost('/matakuliah');
        $this->verifyCsrf();
        $result = $this->service->update($id, $_POST);

        if ($result['ok']) {
            $this->redirect('/matakuliah', 'Data matakuliah berhasil diperbarui.');
        }

        $_SESSION['errors'] = $result['errors'];
        Session::withOldInput($_POST);
        $this->redirect('/matakuliah/edit/' . $id, 'Periksa kembali isian form Anda.');
    }

    public function delete(int $id): void
    {
        $this->onlyPost('/matakuliah');
        $this->verifyCsrf();
        $result = $this->service->delete($id);

        $message = $result['ok']
            ? 'Data matakuliah berhasil dihapus.'
            : 'Data tidak ditemukan.';
        $this->redirect('/matakuliah', $message);
    }
}