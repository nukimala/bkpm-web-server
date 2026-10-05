<?php
// app/Controllers/ProdiController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Services/ProdiService.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class ProdiController extends Controller
{
    private ProdiService $service;

    public function __construct(?ProdiService $service = null)
    {
        AuthMiddleware::requireLogin($this->base_path, false);
        $this->service = $service ?? new ProdiService(Database::getInstance());
    }

    public function index(): void
    {
        $this->view('prodi/index.php', ['prodi' => $this->service->index()]);
    }

    public function create(): void
    {
        $this->view('prodi/create.php');
    }

    public function store(): void
    {
        $this->onlyPost('/prodi');
        $this->verifyCsrf();
        $result = $this->service->create($_POST);

        if ($result['ok']) {
            $this->redirect('/prodi', 'Data prodi berhasil ditambahkan.');
        }

        $_SESSION['errors'] = $result['errors'];
        Session::withOldInput($_POST);
        $this->redirect('/prodi/create', 'Periksa kembali isian form Anda.');
    }

    public function edit(int $id): void
    {
        $prodi = $this->service->find($id);
        if (!$prodi) {
            $this->redirect('/prodi', 'Prodi tidak ditemukan.');
        }

        $this->view('prodi/edit.php', ['prodi' => $prodi]);
    }

    public function update(int $id): void
    {
        $this->onlyPost('/prodi');
        $this->verifyCsrf();
        $result = $this->service->update($id, $_POST);

        if ($result['ok']) {
            $this->redirect('/prodi', 'Data prodi berhasil diperbarui.');
        }

        $_SESSION['errors'] = $result['errors'];
        Session::withOldInput($_POST);
        $this->redirect('/prodi/edit/' . $id, 'Periksa kembali isian form Anda.');
    }

    public function delete(int $id): void
    {
        $this->onlyPost('/prodi');
        $this->verifyCsrf();
        $result = $this->service->delete($id);

        if ($result['ok']) {
            $this->redirect('/prodi', 'Data prodi berhasil dihapus.');
        }

        $errors = reset($result['errors']);
        $message = $errors[0] ?? 'Prodi tidak dapat dihapus.';
        $this->redirect('/prodi', $message);
    }
}