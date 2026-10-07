<?php
// app/Controllers/MatakuliahController.php
// Controller tipis: memanggil Service Layer (Acara 13).
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Core/Logger.php';
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

        try {
            $result = $this->service->create($_POST);
        } catch (Throwable $e) {
            (new Logger())->error('CREATE matakuliah: ' . $e->getMessage());
            $this->redirect('/matakuliah', 'Data gagal disimpan.');
        }

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

        try {
            $result = $this->service->update($id, $_POST);
        } catch (Throwable $e) {
            (new Logger())->error('UPDATE matakuliah id=' . $id . ': ' . $e->getMessage());
            $this->redirect('/matakuliah', 'Data gagal disimpan.');
        }

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

        try {
            $result = $this->service->delete($id);
        } catch (Throwable $e) {
            (new Logger())->error('DELETE matakuliah id=' . $id . ': ' . $e->getMessage());
            $this->redirect('/matakuliah', 'Data gagal dihapus.');
        }

        $message = $result['ok']
            ? 'Data matakuliah berhasil dihapus.'
            : 'Data tidak ditemukan.';
        $this->redirect('/matakuliah', $message);
    }
}