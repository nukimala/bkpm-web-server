<?php
// app/Core/AppException.php
// Exception khusus aplikasi dengan kode status HTTP (Acara 14).
class AppException extends Exception
{
    private int $statusCode;
    private array $errors;

    public function __construct(
        string $message,
        int $statusCode = 500,
        array $errors = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}