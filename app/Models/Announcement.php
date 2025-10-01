<?php

namespace App\Models;

use PDO;

class Announcement
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function paginate(int $page, int $perPage, ?string $audience = null): array
    {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];

        if ($audience) {
            $conditions[] = '(audience = :audience OR audience = "semua")';
            $params[':audience'] = $audience;
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = 'SELECT * FROM pengumuman ' . $where . ' ORDER BY published_at DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $countSql = 'SELECT COUNT(*) FROM pengumuman ' . $where;
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        return ['data' => $data, 'total' => $total];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM pengumuman WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO pengumuman (judul, isi, audience, published_at, created_by, updated_at) VALUES (:judul, :isi, :audience, :published_at, :created_by, NOW())';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':judul' => $data['judul'],
            ':isi' => $data['isi'],
            ':audience' => $data['audience'],
            ':published_at' => $data['published_at'] ?? date('Y-m-d H:i:s'),
            ':created_by' => $data['created_by'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE pengumuman SET judul = :judul, isi = :isi, audience = :audience, published_at = :published_at, updated_at = NOW() WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':judul' => $data['judul'],
            ':isi' => $data['isi'],
            ':audience' => $data['audience'],
            ':published_at' => $data['published_at'] ?? date('Y-m-d H:i:s'),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM pengumuman WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}