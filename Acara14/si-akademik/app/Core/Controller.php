<?php
// app/Core/Controller.php
// BaseController: menyediakan helper view(), redirect(), onlyPost(), verifyCsrf() (Acara 14).
require_once __DIR__ . '/Csrf.php';

class Controller
{
    protected string $base_path = BASE_PATH;

    // Merender view dengan layout utama. $data akan diekstrak menjadi variabel view.
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . '/../Views/' . $view;
        require __DIR__ . '/../Views/layouts/main.php';
    }

    // Redirect dengan pesan flash (opsional).
    protected function redirect(string $to, ?string $flash = null): void
    {
        if ($flash !== null) {
            $_SESSION['flash'] = $flash;
        }
        header('Location: ' . $this->base_path . $to);
        exit;
    }

    // Pola PRG (Post-Redirect-Get): tolak akses GET ke route yang mengubah data.
    protected function onlyPost(string $fallback = '/'): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            $this->redirect($fallback, 'Metode HTTP tidak diizinkan.');
        }
    }

    // Wajib dipanggil di awal tiap operasi POST untuk verifikasi token CSRF.
    protected function verifyCsrf(): void
    {
        Csrf::verify();
    }
}