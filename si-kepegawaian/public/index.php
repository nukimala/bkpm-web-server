<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-HTTP-Method-Override");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../app/core/autoload.php';

$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requesturi = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($basePath !== '/' && strpos($requesturi, $basePath) === 0) {
    $requesturi = substr($requesturi, strlen($basePath));
}
if (empty($requesturi)) {
    $requesturi = '/';
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
Router::get('/', 'TestController', 'welcome');
Router::get('/api/ping', 'TestController', 'ping');

class TestController {
    public function welcome() {
        Response::success('Selamat datang di API kami!');
    }

    public function ping() {
        Response::success('Pong! API Berjalan dengan baik.', [
            'app' => 'SI-Kepegawaian',
            'version' => '1.0.0',
            'time' => date('Y-m-d H:i:s'),
        ]);
    }
}

Router::dispatch($requesturi, $requestMethod);