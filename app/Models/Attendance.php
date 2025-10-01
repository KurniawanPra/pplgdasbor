<?php

namespace App\Models;

use PDO;

class Attendance
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function paginate(int $page, int $perPage, ?string $date = null, ?int $anggotaId = null): array
    {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];

        if ($date) {
            $conditions[] = 'ar.tanggal = :tanggal';
            $params[':tanggal'] = $date;
        }
        if ($anggotaId) {
            $conditions[] = 'ar.anggota_id = :anggota_id';
            $params[':anggota_id'] = $anggotaId;
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = 'SELECT ar.*, a.nama_lengkap, a.panggilan, a.nis FROM attendance_records ar '
            . 'LEFT JOIN anggota a ON a.id_absen = ar.anggota_id '
            . $where
            . ' ORDER BY ar.tanggal DESC, a.nama_lengkap ASC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $records = $stmt->fetchAll();

        $countSql = 'SELECT COUNT(*) FROM attendance_records ar ' . $where;
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        return ['data' => $records, 'total' => $total];
    }

    public function upsert(array $data): bool
    {
        $existing = $this->findByDate($data['anggota_id'], $data['tanggal']);
        if ($existing) {
            return $this->update((int) $existing['id'], $data);
        }
        return $this->create($data);
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO attendance_records (anggota_id, tanggal, status, keterangan, recorded_by, created_at, updated_at) VALUES (:anggota_id, :tanggal, :status, :keterangan, :recorded_by, NOW(), NOW())';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':anggota_id' => $data['anggota_id'],
            ':tanggal' => $data['tanggal'],
            ':status' => $data['status'],
            ':keterangan' => $data['keterangan'] ?: null,
            ':recorded_by' => $data['recorded_by'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE attendance_records SET status = :status, keterangan = :keterangan, recorded_by = :recorded_by, updated_at = NOW() WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $data['status'],
            ':keterangan' => $data['keterangan'] ?: null,
            ':recorded_by' => $data['recorded_by'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM attendance_records WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM attendance_records WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByDate(int $anggotaId, string $tanggal): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM attendance_records WHERE anggota_id = :anggota_id AND tanggal = :tanggal LIMIT 1');
        $stmt->execute([
            ':anggota_id' => $anggotaId,
            ':tanggal' => $tanggal,
        ]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}