<?php

namespace App\Models;

use PDO;

class Struktur
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function getAll(): array
    {
        $sql = 'SELECT s.*, a.nama_lengkap, a.panggilan, a.foto, a.nis, a.jenis_kelamin, a.sosmed, a.nomor_hp, a.email, a.cita_cita, a.tujuan_hidup, a.hobi, a.status AS status_anggota, a.jabatan AS jabatan_anggota FROM struktur_kelas s LEFT JOIN anggota a ON a.id_absen = s.anggota_id ORDER BY FIELD(s.jabatan, "wali_kelas","ketua","wakil","sekretaris","bendahara")';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function assign(string $jabatan, int $anggotaId): bool
    {
        $exists = $this->findByJabatan($jabatan);
        if ($exists) {
            $stmt = $this->db->prepare('UPDATE struktur_kelas SET anggota_id = :anggota_id, updated_at = NOW() WHERE jabatan = :jabatan');
        } else {
            $stmt = $this->db->prepare('INSERT INTO struktur_kelas (jabatan, anggota_id, created_at, updated_at) VALUES (:jabatan, :anggota_id, NOW(), NOW())');
        }

        return $stmt->execute([
            ':jabatan' => $jabatan,
            ':anggota_id' => $anggotaId,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM struktur_kelas WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function findByJabatan(string $jabatan): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM struktur_kelas WHERE jabatan = :jabatan LIMIT 1');
        $stmt->execute([':jabatan' => $jabatan]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
