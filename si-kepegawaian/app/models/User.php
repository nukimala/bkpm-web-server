<?php 
class User {
    private $db;
    private $table = 'users';
    public function __construct() {
        $this->db = Database::getConnection();
    }
    public function getByUsername($username) {
        $query = "SELECT * FROM {$this->table} WHERE username = ? limit 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    public function getProfile($userId) {
        $query = "SELECT u.id, u.username, u.role, u.pegawai_id, p.nip, p.nama, p.jabatan, p.email, p.no_hp, p.alamat, p.foto
                  FROM {$this->table} u
                  LEFT JOIN pegawai p ON u.pegawai_id = p.id
                  WHERE u.id = ? limit 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    public function create($data) {
        $query = "INSERT INTO {$this->table} (pegawai_id, username, password, role) VALUES (:pegawai_id, :username, :password, :role)"; 
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':pegawai_id' => $data['pegawai_id'],
            ':username' => $data['username'],
            ':password' => $hashedPassword,
            ':role' => $data['role'],
        ]);
        return $result ? $this->db->lastInsertId() : false;
    }

}