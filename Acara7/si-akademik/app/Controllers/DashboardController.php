<?php
// app/Controllers/DashboardController.php
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class DashboardController
{
    public function index(): void
    {
        AuthMiddleware::requireLogin(BASE_PATH);

        $user = $_SESSION['user'];
        $content = __DIR__ . '/../Views/auth/dashboard.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}