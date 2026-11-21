<?php
require_once __DIR__ . '/../config/app.php';
// public/index.php - Front Controller (Acara 8, diperkuat Acara 14)
// Mengatur session, pemeriksaan autentikasi, lalu dispatch ke Router.
session_start();

// Acara 14: daftarkan pusat penanganan error/exception + proteksi CSRF.
require_once __DIR__ . '/../app/Core/AppException.php';
require_once __DIR__ . '/../app/Core/Logger.php';
require_once __DIR__ . '/../app/Core/ExceptionHandler.php';
require_once __DIR__ . '/../app/Core/Csrf.php';
ExceptionHandler::register();

$base_path = BASE_PATH;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (str_starts_with($uri, $base_path)) {
    $uri = substr($uri, strlen($base_path));
}
$uri = trim($uri, '/');

// Acara 16: dispatch request API (prefix /api) via ApiRouter (RESTful, aware method).
// Acara 18: preflight CORS (OPTIONS), try/catch terpusat, status code HTTP konsisten.
if ($uri === 'api' || str_starts_with($uri, 'api/')) {
    require_once __DIR__ . '/../app/Core/ApiResponse.php';

    // Preflight request CORS dari browser (konsumen lintas origin).
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
        ApiResponse::noContent(204);
    }

    try {
        require_once __DIR__ . '/../app/Core/ApiRouter.php';
        ApiRouter::dispatch($uri, $_SERVER['REQUEST_METHOD'] ?? 'GET');
    } catch (AppException $e) {
        $code = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;
        ApiResponse::error($e->getMessage(), $code);
    } catch (Throwable $e) {
        (new Logger())->error(sprintf('API %s: %s in %s:%d', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));
        ApiResponse::error('Terjadi kesalahan pada server.', 500);
    }
}

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