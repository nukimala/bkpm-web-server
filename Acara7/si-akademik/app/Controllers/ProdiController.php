<?php

require_once __DIR__ . '/../Models/ProdiModel.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class ProdiController
{
    private string $base_path = BASE_PATH;

    public function __construct()
    {
        AuthMiddleware::requireLogin($this->base_path, false);
    }

    public function index(): void
    {
        $prodi = ProdiModel::all();
        $content = __DIR__ . '/../Views/prodi/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}