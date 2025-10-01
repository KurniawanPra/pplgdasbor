<?php

namespace App\Models;

use PDO;

class Roster
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM roster ORDER BY FIELD(hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"), id ASC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getByDay(string $day): array
    {
        $stmt = $this->db->prepare('SELECT * FROM roster WHERE hari = :hari ORDER BY id ASC');
        $stmt->execute([':hari' => $day]);
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO roster (hari, nama_mapel, created_at, updated_at) VALUES (:hari, :nama_mapel, NOW(), NOW())');
        return $stmt->execute([
            ':hari' => $data['hari'],
            ':nama_mapel' => $data['nama_mapel'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM roster WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
