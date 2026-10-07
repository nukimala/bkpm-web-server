<?php
// app/Core/Router.php
// Router: mencocokkan URI + (opsional) HTTP method, menjalankan middleware, lalu controller.
class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $uri): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $pathFound = false;

        foreach ($this->routes as $route => $config) {
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', trim($route, '/'));
            if (!preg_match('#^' . $pattern . '$#', $uri, $m)) {
                continue;
            }

            $pathFound = true;

            $handler = is_array($config) ? ($config['handler'] ?? '') : $config;
            $middlewares = is_array($config) ? ($config['middleware'] ?? []) : [];
            // Default semua route GET; route tulis (POST) harus dideklarasikan eksplisit.
            $requiredMethod = is_array($config) ? ($config['method'] ?? 'GET') : 'GET';

            if ($requiredMethod !== $method) {
                continue;
            }

            $this->runMiddleware($middlewares);

            [$controllerName, $action] = explode('@', $handler);
            $params = array_filter($m, function ($key) {
                return !is_int($key);
            }, ARRAY_FILTER_USE_KEY);

            $path = __DIR__ . '/../Controllers/' . $controllerName . '.php';
            require_once $path;
            $controller = new $controllerName();
            $controller->$action(...array_values($params));
            return;
        }

        // Path cocok tapi method tidak diizinkan (Acara 5-6: GET/POST dipisah).
        if ($pathFound) {
            http_response_code(405);
            echo '405 - Metode HTTP tidak diizinkan';
            return;
        }

        http_response_code(404);
        echo '404 - Halaman tidak ditemukan';
    }

    private function runMiddleware(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            $file = __DIR__ . '/Middleware/' . $middleware . '.php';
            require_once $file;
            $instance = new $middleware();
            $instance->handle();
        }
    }
}
