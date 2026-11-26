<?php
class PegawaiController {
    private $model;
    public function __construct() {
        $this->model = new Pegawai();
    }
    private function getInputData() {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            return json_decode($raw, true);
        }
        return $_POST;
    }
    private function handleFileUpload($currentFoto = 'default.png') {
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            return $currentFoto;
        }
        $file = $_FILES['foto'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        if ($file['size'] > $maxFileSize) {
            Response::error('Ukuran file terlalu besar. Maksimal 2MB.', 400);
        }
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            Response::error('Format file tidak valid. Hanya diizinkan JPG, JPEG, PNG, dan WEBP.', 400);
        }
        $newFileName =  'foto_' . uniqid('', true) . '.' . $ext;
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
            Response::error('Gagal mengunggah foto.', 500);
        }
        if ($currentFoto && $currentFoto !== 'default.png') {
            $oldFilePath = $uploadDir . $currentFoto;
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        return $newFileName;
    }

    public function index() {
        $pegawaiList = $this->model->getAll();
        Response::success('Berhasil mengambil data pegawai', $pegawaiList, 200);
    }
    public function show($id) {
        $pegawai = $this->model->getById($id);
        if ($pegawai) {
            Response::success('Berhasil mengambil data pegawai', $pegawai, 200);
        } else {
            Response::error("pegawai dengan ID {$id} tidak ditemukan", 404);
        }
    }
        public function store() {
            $data = $this->getInputData();

            if (empty($data['nip']) || empty($data['nama']) || empty($data['email'])) {
                Response::error('Field NIP, Nama, jabatan, dan Email harus diisi', 400);
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                Response::error('Format email tidak valid', 400);
            }
            if ($this->model->getByNip($data['nip'])) {
                Response::error("NIP {$data['nip']} sudah digunakan", 400);
            }
            if ($this->model->getByEmail($data['email'])) {
                Response::error("Email {$data['email']} sudah digunakan", 400);
            }
            $data['foto'] = $this->handleFileUpload('default.png');
            $newId = $this->model->create($data);
            if (!$newId) {
                Response::error('gagal membuat data pegawai', 500);
            }
            $createdPegawai = $this->model->getById($newId);
            Response::success('Berhasil membuat data pegawai', $createdPegawai, 201);
        }
        public function update($id) {
            $pegawai = $this->model->getById($id);
            if (!$pegawai) {
                Response::error("pegawai dengan ID {$id} tidak ditemukan", 404);
            }
            $data = $this->getInputData();
            if (empty($data['nip']) || empty($data['nama']) || empty($data['email'])) {
                Response::error('Field NIP, Nama, jabatan, dan Email harus diisi', 400);
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                Response::error('Format email tidak valid', 400);
            }
            if ($this->model->getByNip($data['nip'], $id)) {
                Response::error("NIP {$data['nip']} sudah digunakan", 400);
            }
            if ($this->model->getByEmail($data['email'], $id)) {
                Response::error("Email {$data['email']} sudah digunakan", 400);
            }
            $data['foto'] = $this->handleFileUpload($pegawai['foto']);
            $data['foto'] = $data['foto'] ?? $pegawai['foto'];
            $success = $this->model->update($id, $data);
            if (!$success) {
                Response::error('gagal memperbarui data pegawai', 500);
            }
            $updatedPegawai = $this->model->getById($id);
            Response::success('Berhasil memperbarui data pegawai', $updatedPegawai, 200);
            }

            public function delete($id) {
                $pegawai = $this->model->getById($id);
                if (!$pegawai) {
                    Response::error("pegawai dengan ID {$id} tidak ditemukan", 404);
                }
                $success = $this->model->delete($id);
                if (!$success) {
                    Response::error('gagal menghapus data pegawai', 500);
                }
                Response::success('Berhasil menghapus data pegawai', null, 200);
            }
        }