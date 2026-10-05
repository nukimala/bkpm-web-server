<?php
// app/Controllers/MatakuliahController.php

require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Repositories/MatakuliahRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';

class MatakuliahController extends BaseController
{
    public function __construct(
        private MatakuliahRepository $repo,
        private ProdiRepository $prodiRepo
    ) {
        AuthMiddleware::requireLogin($this->basePath, false);
    }

    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');
        $data    = $keyword === '' ? $this->repo->all() : $this->repo->search($keyword);

        $this->view('matakuliah/index', [
            'matakuliah' => $data,
            'keyword'    => $keyword,
            'total'      => count($data),
        ]);
    }

    public function create(): void
    {
        $this->view('matakuliah/create', [
            'prodi' => $this->prodiRepo->options(),
            'old'   => [],
        ]);
    }

    public function store(): void
    {
        $data = $this->dataDariForm();

        try {
            $this->repo->create($data);
            $this->redirect('/matakuliah', 'Data matakuliah berhasil ditambahkan.');
        } catch (InvalidArgumentException $e) {
            $this->redirect('/matakuliah/create', null, $e->getMessage());
        } catch (PDOException $e) {
            $this->redirect('/matakuliah/create', null, 'Gagal menyimpan: ' . $this->pesanError($e));
        }
    }

    public function edit(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/matakuliah', null, 'Id matakuliah tidak valid.');
        }

        $mk = $this->repo->find((int) $id);
        if ($mk === null) {
            $this->redirect('/matakuliah', null, 'Matakuliah tidak ditemukan.');
        }

        $this->view('matakuliah/edit', [
            'mk'    => $mk,
            'prodi' => $this->prodiRepo->options(),
        ]);
    }

    public function update(string $id): void
    {
        if (!ctype_digit($id)) {
            $this->redirect('/matakuliah', null, 'Id matakuliah tidak valid.');
        }

        $data = $this->dataDariForm();

        try {
            $this->repo->update((int) $id, $data);
            $this->redirect('/matakuliah', 'Data matakuliah berhasil diperbarui.');
        } catch (InvalidArgumentException $e) {
            $this->redirect('/matakuliah/edit/' . (int) $id, null, $e->getMessage());
        } catch (PDOException $e) {
            $this->redirect(
                '/matakuliah/edit/' . (int) $id,
                null,
                'Gagal memperbarui: ' . $this->pesanError($e)
            );
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
            'kode'     => strtoupper(trim($_POST['kode'] ?? '')),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];
    }
}