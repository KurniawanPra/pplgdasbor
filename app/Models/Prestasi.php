<?php

namespace App\Models;

use PDO;

class Prestasi
{
    private PDO $db;
    private static bool $tableEnsured = false;

    public function __construct()
    {
        $this->db = db();
        if (!self::$tableEnsured) {
            $this->ensureTable();
            self::$tableEnsured = true;
        }
    }

    private function ensureTable(): void
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'prestasi'"
        );
        $exists = (int) $stmt->fetchColumn() > 0;

        if ($exists) {
            return;
        }

        $sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS prestasi (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT NULL,
    penyelenggara VARCHAR(255) NULL,
    tingkat VARCHAR(100) NULL,
    tanggal DATE NULL,
    lokasi VARCHAR(255) NULL,
    created_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_prestasi_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $this->db->exec($sql);
    }

    public function paginate(int $page, int $perPage, ?string $search = null): array
    {
        $offset = ($page - 1) * $perPage;
        $where = '';
        $params = [];

        if ($search) {
            $like = '%' . $search . '%';
            $where = 'WHERE judul LIKE :judul_search OR penyelenggara LIKE :penyelenggara_search OR tingkat LIKE :tingkat_search';
            $params = [
                ':judul_search' => $like,
                ':penyelenggara_search' => $like,
                ':tingkat_search' => $like,
            ];
        }

        $sql = 'SELECT * FROM prestasi ' . $where . ' ORDER BY COALESCE(tanggal, created_at) DESC, created_at DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $countSql = 'SELECT COUNT(*) FROM prestasi ' . $where;
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        return ['data' => $data, 'total' => $total];
    }

    public function latest(int $limit = 6): array
    {
        $stmt = $this->db->prepare('SELECT * FROM prestasi ORDER BY COALESCE(tanggal, created_at) DESC, created_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM prestasi')->fetchColumn();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM prestasi WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO prestasi (judul, deskripsi, penyelenggara, tingkat, tanggal, lokasi, created_by, created_at, updated_at) VALUES (:judul, :deskripsi, :penyelenggara, :tingkat, :tanggal, :lokasi, :created_by, NOW(), NOW())');
        $stmt->execute([
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'] ?: null,
            ':penyelenggara' => $data['penyelenggara'] ?: null,
            ':tingkat' => $data['tingkat'] ?: null,
            ':tanggal' => !empty($data['tanggal']) ? $data['tanggal'] : null,
            ':lokasi' => $data['lokasi'] ?: null,
            ':created_by' => $data['created_by'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE prestasi SET judul = :judul, deskripsi = :deskripsi, penyelenggara = :penyelenggara, tingkat = :tingkat, tanggal = :tanggal, lokasi = :lokasi, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            ':id' => $id,
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'] ?: null,
            ':penyelenggara' => $data['penyelenggara'] ?: null,
            ':tingkat' => $data['tingkat'] ?: null,
            ':tanggal' => !empty($data['tanggal']) ? $data['tanggal'] : null,
            ':lokasi' => $data['lokasi'] ?: null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM prestasi WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}

