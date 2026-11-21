<?php
class Database{
    private $host = 'localhost';
    private $db_name = 'db_kepegawaian';
    private $username = 'root';
    private $password = '';
    private $conn;

    private static $instance = null;
    //
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die(json_encode([
                'status' => 'error',
                'message' => 'koneksi database gagal: ' . $e->getMessage()
            ]));
        }
    }
    private function __clone()
    {
    }

    public static function getConnection() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }
}