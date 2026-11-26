<?php
class Pegawai {
    private $db;
    private $table = 'pegawai';
    public function __construct() {
        $this->db = Database::getConnection();
    }
    public function getAll() {
        $query = "SELECT id, nama, nip, jabatan, email, no_hp, alamat, foto, created_at
        FROM {$this->table} order by id desc";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ? limit 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function getByNip($nip, $excludeId = null) {
        if ($excludeId) {
            $query = "SELECT * FROM {$this->table} WHERE nip = ? AND id != ? limit 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nip, $excludeId]);
        } else {
            $query = "SELECT * FROM {$this->table} WHERE nip = ? limit 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nip]);
        }
        return $stmt->fetch();
    }
    public function getByEmail($email, $excludeId = null) {
        if ($excludeId) {
            $query = "SELECT * FROM {$this->table} WHERE email = ? AND id != ? limit 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email, $excludeId]);
        } else {
            $query = "SELECT * FROM {$this->table} WHERE email = ? limit 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
        }
        return $stmt->fetch();
    }
    public function create($data) {
        $query = "INSERT INTO {$this->table} (nip, nama, jabatan, email, no_hp, alamat, foto) VALUES (:nip, :nama, :jabatan, :email, :no_hp, :alamat, :foto)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':nama' => $data['nama'],
            ':nip' => $data['nip'],
            ':jabatan' => $data['jabatan'],
            ':email' => $data['email'],
            ':no_hp' => $data['no_hp'],
            ':alamat' => $data['alamat'],
            ':foto' => $data['foto'],
        ]);
        return $result ? $this->db->lastInsertId() : false;
    }
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET nama = :nama, nip = :nip, jabatan = :jabatan, email = :email, no_hp = :no_hp, alamat = :alamat, foto = :foto WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':nama' => $data['nama'],
            ':nip' => $data['nip'],
            ':jabatan' => $data['jabatan'],
            ':email' => $data['email'],
            ':no_hp' => $data['no_hp'],
            ':alamat' => $data['alamat'],
            ':foto' => $data['foto'],
        ]);
    }
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}