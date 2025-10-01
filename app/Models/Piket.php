<?php

namespace App\Models;

use PDO;

class Piket
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function getAll(): array
    {
        $sql = 'SELECT p.id, p.hari, p.anggota_id, a.nama_lengkap, a.panggilan FROM piket p LEFT JOIN anggota a ON a.id_absen = p.anggota_id ORDER BY FIELD(p.hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"), a.nama_lengkap';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getGrouped(): array
    {
        $rows = $this->getAll();
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['hari']][] = $row;
        }
        return $grouped;
    }

    public function replaceForDay(string $day, array $anggotaIds): void
    {
        $this->db->beginTransaction();
        try {
            $delete = $this->db->prepare('DELETE FROM piket WHERE hari = :hari');
            $delete->execute([':hari' => $day]);

            $insert = $this->db->prepare('INSERT INTO piket (hari, anggota_id, created_at, updated_at) VALUES (:hari, :anggota_id, NOW(), NOW())');
            foreach ($anggotaIds as $anggotaId) {
                $insert->execute([
                    ':hari' => $day,
                    ':anggota_id' => $anggotaId,
                ]);
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM piket WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
