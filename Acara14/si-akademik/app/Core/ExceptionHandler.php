<?php
// app/Core/ExceptionHandler.php
// Pusat penanganan error/exception: mencatat ke log lalu menampilkan respon (Acara 14).
require_once __DIR__ . '/AppException.php';
require_once __DIR__ . '/Logger.php';

class ExceptionHandler
{
    public static function register(): void
    {
        // Di produksi pesan detail jangan ditampilkan ke pengguna.
        ini_set('display_errors', '0');
        error_reporting(E_ALL);
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
    }

    public static function handleError(int $severity, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        // Detail teknis hanya dicatat ke log, tidak ditampilkan ke pengguna.
        (new Logger())->error(sprintf('PHP ERROR: %s in %s:%d', $message, $file, $line));
        throw new AppException('Terjadi kesalahan pada server. Silakan coba lagi.');
    }

    public static function handleException(Throwable $e): void
    {
        $logger = new Logger();
        $logger->error(sprintf(
            'EXCEPTION %s: %s in %s:%d',
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ));

        $status = ($e instanceof AppException) ? $e->getStatusCode() : 500;
        http_response_code($status);

        // Pesan aman untuk pengguna: AppException sengaja dibuat dengan pesan ramah,
        // selain itu (mis. PDOException) hanya pesan generik — detail ada di storage/logs/app.log.
        $safeMessage = ($e instanceof AppException)
            ? $e->getMessage()
            : 'Terjadi kesalahan pada server. Silakan coba lagi.';

        header('Content-Type: text/html; charset=UTF-8');
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Error</title>'
            . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">'
            . '</head><body class="bg-light">'
            . '<div class="container py-5" style="max-width:560px;">'
            . '<div class="card shadow-sm"><div class="card-body text-center p-5">'
            . '<h1 class="display-6 text-danger">Terjadi Kesalahan</h1>'
            . '<p class="mt-3 mb-0">' . htmlspecialchars($safeMessage) . '</p>'
            . '<a href="' . BASE_PATH . '/" class="btn btn-primary mt-4">Kembali ke Beranda</a>'
            . '</div></div></div></body></html>';
    }
}