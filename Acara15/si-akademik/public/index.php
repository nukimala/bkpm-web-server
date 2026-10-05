<?php
require_once __DIR__ . '/../config/app.php';

session_start();

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

if ($uri === 'api' || str_starts_with($uri, 'api/')) {
    require_once __DIR__ . '/../app/Core/ApiResponse.php';
    $apiRoutes = require __DIR__ . '/../routes/api.php';

    foreach ($apiRoutes as $route => $handler) {
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', trim($route, '/'));
        if (preg_match('#^' . $pattern . '$#', $uri, $m)) {
            [$controllerName, $method] = explode('@', $handler);
            $params = array_filter($m, function ($key) {
                return !is_int($key);
            }, ARRAY_FILTER_USE_KEY);

            require_once __DIR__ . '/../app/Controllers/' . $controllerName . '.php';
            $controller = new $controllerName();
            $controller->$method(...array_values($params));
        }
    }

    ApiResponse::error('Endpoint API tidak ditemukan.', 404);
}

function flash(string $key = 'flash'): string
{
    if (isset($_SESSION[$key])) {
        $message = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $message;
    }
    return '';
}

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