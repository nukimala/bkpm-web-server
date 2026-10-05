<?php
// app/Core/BaseModel.php
abstract class BaseModel
{
    protected PDO $pdo;

    protected string $table = '';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function db(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->db($sql, $params)->fetchAll();
    }

    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $row = $this->db($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    protected function execute(string $sql, array $params = []): int
    {
        return $this->db($sql, $params)->rowCount();
    }

    protected function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    protected function transaction(callable $callback): mixed
    {
        $this->pdo->beginTransaction();
        try {
            $result = $callback($this->pdo);
            $this->pdo->commit();
            return $result;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function table(): string
    {
        return $this->table;
    }

    public function validate(array $data): array
    {
        return [];
    }
}