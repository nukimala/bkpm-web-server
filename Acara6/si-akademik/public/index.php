<?php
require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_path = BASE_PATH;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (str_starts_with($uri, $base_path)) {
    $uri = substr($uri, strlen($base_path));
}
$uri = trim($uri, '/');

if ($uri === 'index.php') {
    $uri = '';
}

require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Router.php';

$routes = require __DIR__ . '/../routes/web.php';

$router = new Router($routes);
$router->dispatch($uri);