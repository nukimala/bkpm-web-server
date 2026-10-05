<?php
require_once __DIR__ . '/../Core/Controller.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->render('auth/dashboard.php', ['user' => $_SESSION['user'] ?? '']);
    }
}