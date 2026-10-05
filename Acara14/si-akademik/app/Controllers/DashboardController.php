<?php
// app/Controllers/DashboardController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::requireLogin(BASE_PATH);

        $this->view('auth/dashboard.php', [
            'user' => $_SESSION['user'],
        ]);
    }
}