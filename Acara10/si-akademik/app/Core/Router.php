<?php
// app/Core/Router.php
// Router sederhana: mencocokkan URI dengan route lalu memanggil controller@method.
// Router juga menjadi "container" kecil untuk dependency injection:
//   PDO   -> koneksi database dari Database
//   *Repository -> repository yang sudah menerima PDO
// Controller tidak perlu membuat koneksi database secara manual.
class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $uri, string $method = 'GET'): void
    {
        $method = $method === 'HEAD' ? 'GET' : $method;

        foreach ($this->routes as $route => $definition) {
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', trim($route, '/'));
            if (!preg_match('#^' . $pattern . '$#', $uri, $m)) {
                continue;
            }

            $handler = is_array($definition) ? $definition['handler'] : $definition;
            $allowed = is_array($definition) ? ($definition['method'] ?? null) : null;

            // Route yang mengubah data hanya boleh dipanggil dengan POST.
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

            $controller = $this->instantiateController($controllerName);
            $controller->$methodName(...array_values($params));
            return;
        }

        http_response_code(404);
        echo '404 - Halaman tidak ditemukan';
    }

    /** Membuat controller dan mengisi dependency-nya secara otomatis. */
    private function instantiateController(string $name): object
    {
        if (!class_exists($name)) {
            require_once __DIR__ . '/../Controllers/' . $name . '.php';
        }

        $reflection    = new ReflectionClass($name);
        $constructor   = $reflection->getConstructor();

        if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
            return new $name();
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->make($type->getName());
                continue;
            }

            $dependencies[] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    /** Dependency factory sederhana. */
    private function make(string $className): object
    {
        if ($className === 'PDO') {
            return $this->pdo();
        }

        if ($className === 'Database') {
            require_once __DIR__ . '/../Core/Database.php';
            return Database::getInstance();
        }

        // Repository: seluruh query SQL-nya ada di class repository,
        // dan repository mendapat objek PDO melalui constructor.
        if (str_ends_with($className, 'Repository')) {
            require_once __DIR__ . '/../Repositories/' . $className . '.php';
            return new $className($this->pdo());
        }

        throw new RuntimeException('Dependency ' . $className . ' tidak dikenali.');
    }

    private function pdo(): PDO
    {
        require_once __DIR__ . '/../Core/Database.php';
        return Database::getInstance()->getConnection();
    }
}