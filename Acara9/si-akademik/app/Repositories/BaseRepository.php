<?php
require_once __DIR__ . '/../Core/Database.php';

class BaseRepository
{
    protected Database $db;
    protected PDO $pdo;

    public function __construct(Database $db)
    {
        $this->db  = $db;
        $this->pdo = $db->getConnection();
    }

    protected function count(string $sql, array $params = []): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    protected function paginateQuery(string $sql, array $params, int $page, int $perPage = 10): array
    {
        $page    = max(1, $page);
        $perPage = max(1, $perPage);
        $offset  = ($page - 1) * $perPage;

        $stmt = $this->db->prepare($sql . " LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);

        return [
            'data'    => $stmt->fetchAll(),
            'page'    => $page,
            'perPage' => $perPage,
            'offset'  => $offset,
        ];
    }
}