<?php
// app/Core/Router.php
// Router sederhana: mencocokkan URI + HTTP method dengan route, lalu memanggil controller@method.
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

        if (isset($this->routes[$method]) && $this->match($this->routes[$method], $uri)) {
            return;
        }

        // URI ada, tapi method-nya salah (contoh: GET ke route POST) -> 405.
        foreach ($this->routes as $otherMethod => $group) {
            if ($otherMethod !== $method && $this->match($group, $uri, false)) {
                http_response_code(405);
                echo '405 - Metode HTTP tidak diizinkan';
                return;
            }
        }

        http_response_code(404);
        echo '404 - Halaman tidak ditemukan';
    }

    // $execute = false hanya untuk mencocokkan path (dipakai pengecekan 405).
    private function match(array $group, string $uri, bool $execute = true): bool
    {
        foreach ($group as $route => $handler) {
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', trim($route, '/'));
            if (!preg_match('#^' . $pattern . '$#', $uri, $m)) {
                continue;
            }

            if (!$execute) {
                return true;
            }

            [$controllerName, $method] = explode('@', $handler);
            $params = array_filter($m, function ($key) {
                return !is_int($key);
            }, ARRAY_FILTER_USE_KEY);

            $path = __DIR__ . '/../Controllers/' . $controllerName . '.php';
            require_once $path;
            $controller = new $controllerName();
            $controller->$method(...array_values($params));
            return true;
        }

        return false;
    }
}
