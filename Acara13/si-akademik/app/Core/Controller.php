<?php
// app/Core/Controller.php
// BaseController: menyediakan helper view() dan redirect() untuk semua controller (Acara 10).
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
}