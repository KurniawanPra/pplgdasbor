<?php

namespace App\Models;

class Gallery
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function paginate(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare('SELECT g.*, u.nama_lengkap AS uploader FROM gallery g LEFT JOIN users u ON u.id = g.uploaded_by ORDER BY g.created_at DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $total = (int) $this->db->query('SELECT COUNT(*) FROM gallery')->fetchColumn();
        return ['data' => $data, 'total' => $total];
    }

    public function latest(int $limit = 9): array
    {
        $stmt = $this->db->prepare('SELECT * FROM gallery ORDER BY created_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO gallery (judul, deskripsi, file_path, uploaded_by, created_at) VALUES (:judul, :deskripsi, :file_path, :uploaded_by, NOW())');
        return $stmt->execute([
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'],
            ':file_path' => $data['file_path'],
            ':uploaded_by' => $data['uploaded_by'],
        ]);
    }

    public function delete(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM gallery WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $gallery = $stmt->fetch();
        if (!$gallery) {
            return null;
        }

        $delete = $this->db->prepare('DELETE FROM gallery WHERE id = :id');
        $delete->execute([':id' => $id]);

        return $gallery;
    }
}


