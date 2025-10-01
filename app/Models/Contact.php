<?php

namespace App\Models;

use PDO;

class Contact
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO kontak (nama, email, pesan, created_at) VALUES (:nama, :email, :pesan, NOW())');
        return $stmt->execute([
            ':nama' => $data['nama'],
            ':email' => $data['email'],
            ':pesan' => $data['pesan'],
        ]);
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare('SELECT * FROM kontak ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $total = (int) $this->db->query('SELECT COUNT(*) FROM kontak')->fetchColumn();
        return ['data' => $data, 'total' => $total];
    }
}
