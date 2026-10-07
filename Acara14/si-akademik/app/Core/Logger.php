<?php
// app/Core/Logger.php
// Logging sederhana ke file storage/logs/*.log (Acara 14).
class Logger
{
    private string $dir;
    private string $file;

    public function __construct(string $name = 'app')
    {
        $this->dir = __DIR__ . '/../../storage/logs';
        $this->file = $this->dir . '/' . $name . '.log';
    }

    private function write(string $level, string $message): void
    {
        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0777, true);
        }
        $line = sprintf("[%s] %-5s %s%s", date('Y-m-d H:i:s'), $level, $message, PHP_EOL);
        file_put_contents($this->file, $line, FILE_APPEND | LOCK_EX);
    }

    public function info(string $message): void
    {
        $this->write('INFO', $message);
    }

    public function warning(string $message): void
    {
        $this->write('WARN', $message);
    }

    public function error(string $message): void
    {
        $this->write('ERROR', $message);
    }
}