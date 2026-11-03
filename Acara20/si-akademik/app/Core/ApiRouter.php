<?php
// app/Core/ApiRouter.php
// Router API (RESTful): mencocokkan HTTP method + URI dengan route api (Acara 16).
// Format route: 'METHOD /api/path' => 'Controller@method'.
require_once __DIR__ . '/ApiResponse.php';
require_once __DIR__ . '/Database.php';

class ApiRouter
{
    // Dispatch request API. Memisahkan 404 (path tidak ada) dan 405 (method salah).
    public static function dispatch(string $uri, string $method): void
    {
        $routes = require __DIR__ . '/../../routes/api.php';
        $method = strtoupper($method);
        $pathMatched = false;

        foreach ($routes as $key => $handler) {
            [$routeMethod, $routePath] = preg_split('/\s+/', trim($key), 2);

            $pattern = preg_replace(
                '#\{([a-zA-Z0-9_]+)\}#',
                '(?P<$1>[0-9a-zA-Z_]+)',
                trim($routePath, '/')
            );

            if (preg_match('#^' . $pattern . '$#', $uri, $m)) {
                $pathMatched = true;

                if ($method !== strtoupper($routeMethod)) {
                    continue;
                }

                [$controllerName, $controllerMethod] = explode('@', $handler);
                $params = array_filter($m, function ($key) {
                    return !is_int($key);
                }, ARRAY_FILTER_USE_KEY);

                // Acara 19: endpoint pemodifikasi data wajib punya token Bearer,
                // kecuali endpoint login dan logout.
                if ($method !== 'GET' && $routePath !== '/api/auth/login' && $routePath !== '/api/auth/logout') {
                    require_once __DIR__ . '/../Core/Middleware/ApiAuthMiddleware.php';
                    ApiAuthMiddleware::validate();
                }

                $controllerFile = str_replace('\\', '/', $controllerName);
                $controllerClass = basename($controllerFile);
                require_once __DIR__ . '/../Controllers/' . $controllerFile . '.php';
                $controller = new $controllerClass();
                $controller->$controllerMethod(...array_values($params));
                return;
            }
        }

        if ($pathMatched) {
            ApiResponse::error('Metode HTTP tidak diizinkan untuk endpoint ini.', 405);
        }
        ApiResponse::error('Endpoint API tidak ditemukan.', 404);
    }

    // Membaca body request berisi JSON menjadi array.
    public static function input(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}