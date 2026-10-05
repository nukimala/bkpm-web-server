<?php
// app/Controllers/ProdiController.php

require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class ProdiController extends BaseController
{
    public function __construct(private ProdiRepository $repo)
    {
        AuthMiddleware::requireLogin($this->basePath, false);
    }

    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');
        $data    = $keyword === '' ? $this->repo->all() : $this->repo->search($keyword);

        $this->view('prodi/index', [
            'prodi'   => $data,
            'keyword' => $keyword,
            'total'   => count($data),
        ]);
    }

    public function create(): void
    {
        $this->view('prodi/create', ['old' => []]);
    }

    public function store(): void
    {
        $data = $this->dataDariForm();

        try {
            $this->repo->create($data);
            $this->redirect('/prodi', 'Data prodi berhasil ditambahkan.');
        } catch (InvalidArgumentException $e) {
            $this->redirect('/prodi/create', null, $e->getMessage());
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
        if ($prodi === null) {
            $this->redirect('/prodi', null, 'Prodi tidak ditemukan.');
        }

        $this->view('prodi/edit', ['prodi' => $prodi]);
    }

    public function update(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/prodi', null, 'Id prodi tidak valid.');
        }

        $data = $this->dataDariForm();

        try {
            $this->repo->update((int) $id, $data);
            $this->redirect('/prodi', 'Data prodi berhasil diperbarui.');
        } catch (InvalidArgumentException $e) {
            $this->redirect('/prodi/edit/' . (int) $id, null, $e->getMessage());
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
            $this->redirect('/prodi', null, 'Gagal menghapus: ' . $this->pesanError($e));
        }
    }

    private function dataDariForm(): array
    {
        return [
            'kode' => strtoupper(trim($_POST['kode'] ?? '')),
            'nama' => trim($_POST['nama'] ?? ''),
        ];
    }
}