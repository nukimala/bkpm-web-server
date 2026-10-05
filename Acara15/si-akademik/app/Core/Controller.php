<?php
// app/Core/Controller.php
require_once __DIR__ . '/Csrf.php';

class Controller
{
    protected string $base_path = BASE_PATH;

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . '/../Views/' . $view;
        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect(string $to, ?string $flash = null): void
    {
        if ($flash !== null) {
            $_SESSION['flash'] = $flash;
        }
        header('Location: ' . $this->base_path . $to);
        exit;
    }

    protected function onlyPost(string $fallback = '/'): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            $this->redirect($fallback, 'Metode HTTP tidak diizinkan.');
        }
    }

    protected function verifyCsrf(): void
    {
        Csrf::verify();
    }
}