<?php
class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $uri, string $method = 'GET'): void
    {
        $method  = $method === 'HEAD' ? 'GET' : $method;

        foreach ($this->routes as $route => $definition) {
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', trim($route, '/'));
            if (!preg_match('#^' . $pattern . '$#', $uri, $m)) {
                continue;
            }

            $handler = is_array($definition) ? $definition['handler'] : $definition;
            $allowed = is_array($definition) ? ($definition['method'] ?? null) : null;

            if ($allowed !== null && strtoupper($allowed) !== $method) {
                http_response_code(405);
                header('Allow: ' . strtoupper($allowed));
                echo '405 - Metode HTTP tidak diizinkan';
                return;
            }

            [$controllerName, $methodName] = explode('@', $handler);
            $params = array_filter($m, function ($key) {
                return !is_int($key);
            }, ARRAY_FILTER_USE_KEY);

            $path = __DIR__ . '/../Controllers/' . $controllerName . '.php';
            require_once $path;
            $controller = new $controllerName();
            $controller->$methodName(...array_values($params));
            return;
        }

        http_response_code(404);
        echo '404 - Halaman tidak ditemukan';
    }
}