<?php

namespace App\Models;

use PDO;

class WeeklyTask
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function all(?bool $onlyActive = null): array
    {
        $conditions = [];
        if ($onlyActive === true) {
            $conditions[] = 'is_completed = 0';
        } elseif ($onlyActive === false) {
            $conditions[] = 'is_completed = 1';
        }
        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $sql = 'SELECT * FROM weekly_tasks ' . $where . ' ORDER BY deadline IS NULL ASC, deadline ASC, created_at DESC';
        return $this->db->query($sql)->fetchAll();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare('SELECT * FROM weekly_tasks ORDER BY deadline IS NULL ASC, deadline ASC, created_at DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $total = (int) $this->db->query('SELECT COUNT(*) FROM weekly_tasks')->fetchColumn();
        return ['data' => $data, 'total' => $total];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM weekly_tasks WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO weekly_tasks (judul, deskripsi, deadline, is_completed, created_by, updated_by, created_at, updated_at) VALUES '
            . '(:judul, :deskripsi, :deadline, :is_completed, :created_by, :updated_by, NOW(), NOW())';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'] ?: null,
            ':deadline' => $data['deadline'] ?: null,
            ':is_completed' => (int) !empty($data['is_completed']),
            ':created_by' => $data['created_by'] ?? null,
            ':updated_by' => $data['updated_by'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE weekly_tasks SET judul = :judul, deskripsi = :deskripsi, deadline = :deadline, is_completed = :is_completed, updated_by = :updated_by, updated_at = NOW() WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'] ?: null,
            ':deadline' => $data['deadline'] ?: null,
            ':is_completed' => (int) !empty($data['is_completed']),
            ':updated_by' => $data['updated_by'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM weekly_tasks WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}