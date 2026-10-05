<?php
// app/Controllers/DashboardController.php
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Repositories/ProdiRepository.php';
require_once __DIR__ . '/../Repositories/MatakuliahRepository.php';

class DashboardController extends BaseController
{
    public function __construct(
        private MahasiswaRepository $mhsRepo,
        private ProdiRepository $prodiRepo,
        private MatakuliahRepository $mkRepo
    ) {
        AuthMiddleware::requireLogin($this->basePath, false);
    }

    public function index(): void
    {
        $this->view('auth/dashboard', [
            'user'      => $_SESSION['user'] ?? '',
            'statistik' => [
                'mahasiswa'  => count($this->mhsRepo->all()),
                'prodi'      => count($this->prodiRepo->all()),
                'matakuliah' => count($this->mkRepo->all()),
            ],
        ]);
    }
}