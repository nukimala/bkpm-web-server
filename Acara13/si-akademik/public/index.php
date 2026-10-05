<?php
require_once __DIR__ . '/../config/app.php';

// public/index.php - Front Controller (Acara 8)
// Mengatur session, pemeriksaan autentikasi, lalu dispatch ke Router.
session_start();

$base_path = BASE_PATH;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (str_starts_with($uri, $base_path)) {
    $uri = substr($uri, strlen($base_path));
}
$uri = trim($uri, '/');

// Helper: mengambil pesan flash sekali lalu menghapusnya (Tugas Mandiri Acara 6).
function flash(string $key = 'flash'): string
{
    if (isset($_SESSION[$key])) {
        $message = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $message;
    }
    return '';
}

// Group route yang wajib login.
$protected = ['dashboard', 'mahasiswa', 'prodi', 'matakuliah'];
foreach ($protected as $prefix) {
    if ($uri === $prefix || str_starts_with($uri, $prefix . '/')) {
        require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';
        AuthMiddleware::requireLogin($base_path);
    }
}

$routes = require __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../app/Core/Router.php';

$router = new Router($routes);
$router->dispatch($uri);