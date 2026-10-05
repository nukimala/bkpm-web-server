<?php
class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $uri): void
    {
        foreach ($this->routes as $route => $config) {
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', trim($route, '/'));
            if (!preg_match('#^' . $pattern . '$#', $uri, $m)) {
                continue;
            }

            $handler = is_array($config) ? ($config['handler'] ?? '') : $config;
            $middlewares = is_array($config) ? ($config['middleware'] ?? []) : [];

            $this->runMiddleware($middlewares);

            [$controllerName, $method] = explode('@', $handler);
            $params = array_filter($m, function ($key) {
                return !is_int($key);
            }, ARRAY_FILTER_USE_KEY);

            $path = __DIR__ . '/../Controllers/' . $controllerName . '.php';
            require_once $path;
            $controller = new $controllerName();
            $controller->$method(...array_values($params));
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