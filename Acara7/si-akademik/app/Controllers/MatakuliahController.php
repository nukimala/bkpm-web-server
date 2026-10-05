<?php

require_once __DIR__ . '/../Models/MatakuliahModel.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class MatakuliahController
{
    private string $base_path = BASE_PATH;

    public function __construct()
    {
        AuthMiddleware::requireLogin($this->base_path, false);
    }

    public function index(): void
    {
        $matakuliah = MatakuliahModel::all();
        $content = __DIR__ . '/../Views/matakuliah/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}