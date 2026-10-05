<?php
// app/Core/BaseController.php

abstract class BaseController
{
    protected string $basePath = BASE_PATH;

    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $content = __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }


    protected function redirect(string $to, ?string $success = null, ?string $error = null): void
    {
        if ($success !== null) {
            $_SESSION['flash_success'] = $success;
        }
        if ($error !== null) {
            $_SESSION['flash_error'] = $error;
        }

        header('Location: ' . $this->basePath . $to);
        exit;
    }

        protected function pesanError(PDOException $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Duplicate entry')) {
            return 'Data sudah ada (kode/NIM sudah dipakai).';
        }
        if (str_contains($message, 'foreign key constraint fails')) {
            return 'Data masih dipakai tabel lain (mis. prodi), hapus data terkait lebih dulu.';
        }
        if (str_contains($message, 'Data too long')) {
            return 'Data terlalu panjang untuk kolom database.';
        }

        return 'Kesalahan database: ' . $message;
    }
}