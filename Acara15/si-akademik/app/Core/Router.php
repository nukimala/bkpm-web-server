<?php
// app/Core/Router.php
class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $uri): void
    {
        foreach ($this->routes as $route => $handler) {
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', trim($route, '/'));
            if (preg_match('#^' . $pattern . '$#', $uri, $m)) {
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
        }

        http_response_code(404);
        echo '404 - Halaman tidak ditemukan';
    }
}