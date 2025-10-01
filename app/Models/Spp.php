<?php

namespace App\Models;

use PDO;

class Spp
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function paginate(int $page, int $perPage, ?int $anggotaId = null, ?int $year = null, ?int $month = null, ?string $keyword = null): array
    {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];

        if ($anggotaId) {
            $conditions[] = 'spp.anggota_id = :anggota_id';
            $params[':anggota_id'] = $anggotaId;
        }
        if ($year) {
            $conditions[] = 'spp.tahun = :tahun';
            $params[':tahun'] = $year;
        }
        if ($month) {
            $conditions[] = 'spp.bulan = :bulan';
            $params[':bulan'] = $month;
        }
        if ($keyword) {
            $conditions[] = '(a.nama_lengkap LIKE :keyword OR a.panggilan LIKE :keyword OR a.nis LIKE :keyword)';
            $params[':keyword'] = '%' . $keyword . '%';
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = 'SELECT spp.*, a.nama_lengkap, a.panggilan, a.nis FROM spp_records spp '
            . 'LEFT JOIN anggota a ON a.id_absen = spp.anggota_id '
            . $where
            . ' ORDER BY spp.tahun DESC, spp.bulan DESC, a.nama_lengkap ASC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $countSql = 'SELECT COUNT(*) FROM spp_records spp '
            . 'LEFT JOIN anggota a ON a.id_absen = spp.anggota_id '
            . ($where ? ' ' . $where : '');
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        return ['data' => $data, 'total' => $total];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM spp_records WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function upsert(array $data): bool
    {
        $existing = $this->findByPeriod($data['anggota_id'], $data['bulan'], $data['tahun']);
        if ($existing) {
            return $this->update((int) $existing['id'], $data);
        }
        return $this->create($data);
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO spp_records (anggota_id, bulan, tahun, jumlah, status, tanggal_bayar, catatan, created_by, updated_by, created_at, updated_at) '
            . 'VALUES (:anggota_id, :bulan, :tahun, :jumlah, :status, :tanggal_bayar, :catatan, :created_by, :updated_by, NOW(), NOW())';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':anggota_id' => $data['anggota_id'],
            ':bulan' => $data['bulan'],
            ':tahun' => $data['tahun'],
            ':jumlah' => $data['jumlah'],
            ':status' => $data['status'],
            ':tanggal_bayar' => $data['tanggal_bayar'] ?: null,
            ':catatan' => $data['catatan'] ?: null,
            ':created_by' => $data['created_by'] ?? null,
            ':updated_by' => $data['updated_by'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE spp_records SET anggota_id = :anggota_id, bulan = :bulan, tahun = :tahun, jumlah = :jumlah, status = :status, '
            . 'tanggal_bayar = :tanggal_bayar, catatan = :catatan, updated_by = :updated_by, updated_at = NOW() WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':anggota_id' => $data['anggota_id'],
            ':bulan' => $data['bulan'],
            ':tahun' => $data['tahun'],
            ':jumlah' => $data['jumlah'],
            ':status' => $data['status'],
            ':tanggal_bayar' => $data['tanggal_bayar'] ?: null,
            ':catatan' => $data['catatan'] ?: null,
            ':updated_by' => $data['updated_by'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM spp_records WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function findByPeriod(int $anggotaId, int $bulan, int $tahun): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM spp_records WHERE anggota_id = :anggota_id AND bulan = :bulan AND tahun = :tahun LIMIT 1');
        $stmt->execute([
            ':anggota_id' => $anggotaId,
            ':bulan' => $bulan,
            ':tahun' => $tahun,
        ]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getSummaryByYear(int $tahun): array
    {
        $stmt = $this->db->prepare('SELECT bulan, COUNT(*) AS total_record, SUM(CASE WHEN status = "lunas" THEN 1 ELSE 0 END) AS total_lunas FROM spp_records WHERE tahun = :tahun GROUP BY bulan ORDER BY bulan');
        $stmt->execute([':tahun' => $tahun]);
        return $stmt->fetchAll();
    }
}