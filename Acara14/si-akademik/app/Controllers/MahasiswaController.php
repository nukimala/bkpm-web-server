<?php
// app/Controllers/MahasiswaController.php
// Controller tipis: memanggil Service Layer, lalu memutuskan arah halaman (PRG) (Acara 13).
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Core/Logger.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class MahasiswaController extends Controller
{
    private MahasiswaService $service;

    public function __construct(?MahasiswaService $service = null)
    {
        AuthMiddleware::requireLogin($this->base_path, false);
        $this->service = $service ?? new MahasiswaService(Database::getInstance());
    }

    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');

        $this->view('mahasiswa/index.php', [
            'mahasiswa' => $this->service->index($search),
            'keyword'   => $search,
        ]);
    }

    public function create(): void
    {
        $this->view('mahasiswa/create.php', [
            'prodi' => $this->service->prodiList(),
        ]);
    }

    public function store(): void
    {
        $this->onlyPost('/mahasiswa');
        $this->verifyCsrf();

        try {
            $result = $this->service->create($_POST);
        } catch (Throwable $e) {
            (new Logger())->error('CREATE mahasiswa: ' . $e->getMessage());
            $this->redirect('/mahasiswa', 'Data gagal disimpan.');
        }

        if ($result['ok']) {
            $this->redirect('/mahasiswa', 'Data mahasiswa berhasil ditambahkan.');
        }

        $_SESSION['errors'] = $result['errors'];
        Session::withOldInput($_POST);
        $this->redirect('/mahasiswa/create', 'Periksa kembali isian form Anda.');
    }

    public function show(string $nim): void
    {
        $this->view('mahasiswa/show.php', [
            'mhs' => $this->service->findByNim($nim),
        ]);
    }

    public function edit(int $id): void
    {
        $mhs = $this->service->find($id);
        if (!$mhs) {
            $this->redirect('/mahasiswa', 'Mahasiswa tidak ditemukan.');
        }

        $this->view('mahasiswa/edit.php', [
            'mhs'   => $mhs,
            'prodi' => $this->service->prodiList(),
        ]);
    }

    public function update(int $id): void
    {
        $this->onlyPost('/mahasiswa');
        $this->verifyCsrf();

        try {
            $result = $this->service->update($id, $_POST);
        } catch (Throwable $e) {
            (new Logger())->error('UPDATE mahasiswa id=' . $id . ': ' . $e->getMessage());
            $this->redirect('/mahasiswa', 'Data gagal disimpan.');
        }

        if ($result['ok']) {
            $this->redirect('/mahasiswa', 'Data mahasiswa berhasil diperbarui.');
        }

        $_SESSION['errors'] = $result['errors'];
        Session::withOldInput($_POST);
        $this->redirect('/mahasiswa/edit/' . $id, 'Periksa kembali isian form Anda.');
    }

    public function delete(int $id): void
    {
        $this->onlyPost('/mahasiswa');
        $this->verifyCsrf();

        try {
            $result = $this->service->delete($id);
        } catch (Throwable $e) {
            (new Logger())->error('DELETE mahasiswa id=' . $id . ': ' . $e->getMessage());
            $this->redirect('/mahasiswa', 'Data gagal dihapus.');
        }

        $message = $result['ok']
            ? 'Data mahasiswa berhasil dihapus.'
            : 'Data tidak ditemukan.';
        $this->redirect('/mahasiswa', $message);
    }
}