<?php

namespace App\Models;

use PDO;

class Anggota
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function paginate(int $page, int $perPage, ?string $search = null): array
    {
        $offset = ($page - 1) * $perPage;
        $conditions = [];
        $params = [];

        if ($search) {
            $conditions[] = '(nama_lengkap LIKE :nama_search OR nis LIKE :nis_search)';
            $params[':nama_search'] = '%' . $search . '%';
            $params[':nis_search'] = '%' . $search . '%';
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = 'SELECT * FROM anggota ' . $where . ' ORDER BY id_absen ASC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $countSql = 'SELECT COUNT(*) FROM anggota ' . $where;
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        return ['data' => $data, 'total' => $total];
    }

    public function getLanding(int $perPage, int $page, ?string $search = null): array
    {
        $offset = ($page - 1) * $perPage;
        $conditions = ['status = :status'];
        $params = [':status' => 'aktif'];

        if ($search) {
            $conditions[] = '(nama_lengkap LIKE :search OR panggilan LIKE :search OR nis LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);

        $sql = 'SELECT id_absen, nis, nama_lengkap, panggilan, jenis_kelamin, jabatan, sosmed, nomor_hp, email, status, cita_cita, tujuan_hidup, hobi, foto '
            . 'FROM anggota ' . $where . ' ORDER BY id_absen ASC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $countSql = 'SELECT COUNT(*) FROM anggota ' . $where;
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        return ['data' => $data, 'total' => $total];
    }

    public function find(int $idAbsen): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM anggota WHERE id_absen = :id LIMIT 1');
        $stmt->execute([':id' => $idAbsen]);
        $anggota = $stmt->fetch();
        return $anggota ?: null;
    }

    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM anggota WHERE user_id = :user_id LIMIT 1');
        $stmt->execute([':user_id' => $userId]);
        $anggota = $stmt->fetch();
        return $anggota ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO anggota (id_absen, nis, nama_lengkap, panggilan, jenis_kelamin, jabatan, sosmed, nomor_hp, email, status, cita_cita, tujuan_hidup, hobi, foto, user_id, created_at, updated_at) VALUES (:id_absen, :nis, :nama_lengkap, :panggilan, :jenis_kelamin, :jabatan, :sosmed, :nomor_hp, :email, :status, :cita_cita, :tujuan_hidup, :hobi, :foto, :user_id, NOW(), NOW())');

        return $stmt->execute([
            ':id_absen' => $data['id_absen'],
            ':nis' => $data['nis'],
            ':nama_lengkap' => $data['nama_lengkap'],
            ':panggilan' => $data['panggilan'],
            ':jenis_kelamin' => $data['jenis_kelamin'],
            ':jabatan' => $data['jabatan'],
            ':sosmed' => $data['sosmed'],
            ':nomor_hp' => $data['nomor_hp'],
            ':email' => $data['email'],
            ':status' => $data['status'],
            ':cita_cita' => $data['cita_cita'],
            ':tujuan_hidup' => $data['tujuan_hidup'],
            ':hobi' => $data['hobi'],
            ':foto' => $data['foto'],
            ':user_id' => $data['user_id'],
        ]);
    }

    public function update(int $idAbsen, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE anggota SET nis = :nis, nama_lengkap = :nama_lengkap, panggilan = :panggilan, jenis_kelamin = :jenis_kelamin, jabatan = :jabatan, sosmed = :sosmed, nomor_hp = :nomor_hp, email = :email, status = :status, cita_cita = :cita_cita, tujuan_hidup = :tujuan_hidup, hobi = :hobi, foto = :foto, user_id = :user_id, updated_at = NOW() WHERE id_absen = :id');

        return $stmt->execute([
            ':id' => $idAbsen,
            ':nis' => $data['nis'],
            ':nama_lengkap' => $data['nama_lengkap'],
            ':panggilan' => $data['panggilan'],
            ':jenis_kelamin' => $data['jenis_kelamin'],
            ':jabatan' => $data['jabatan'],
            ':sosmed' => $data['sosmed'],
            ':nomor_hp' => $data['nomor_hp'],
            ':email' => $data['email'],
            ':status' => $data['status'],
            ':cita_cita' => $data['cita_cita'],
            ':tujuan_hidup' => $data['tujuan_hidup'],
            ':hobi' => $data['hobi'],
            ':foto' => $data['foto'],
            ':user_id' => $data['user_id'],
        ]);
    }

    public function delete(int $idAbsen): bool
    {
        $stmt = $this->db->prepare('DELETE FROM anggota WHERE id_absen = :id');
        return $stmt->execute([':id' => $idAbsen]);
    }

    public function forDropdown(): array
    {
        $stmt = $this->db->query('SELECT id_absen, nama_lengkap, jabatan FROM anggota ORDER BY nama_lengkap ASC');
        return $stmt->fetchAll();
    }

    public function getByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT * FROM anggota WHERE id_absen IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    public function count(?string $status = null): int
    {
        if ($status) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM anggota WHERE status = :status');
            $stmt->execute([':status' => $status]);
            return (int) $stmt->fetchColumn();
        }

        $stmt = $this->db->query('SELECT COUNT(*) FROM anggota');
        return (int) $stmt->fetchColumn();
    }
}