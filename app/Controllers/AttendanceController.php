<?php

namespace App\Controllers;

use App\Models\Anggota;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    private Attendance $attendance;
    private Anggota $anggota;

    private const MANAGE_ROLES = ['administrator', 'superadmin', 'wali_kelas', 'sekretaris'];
    private const VIEW_ALL_ROLES = ['administrator', 'superadmin', 'wali_kelas', 'ketua', 'wakil_ketua', 'bendahara', 'sekretaris', 'pengurus'];

    public function __construct()
    {
        $this->attendance = new Attendance();
        $this->anggota = new Anggota();
    }

    public function index(): string
    {
        if (!$this->canViewAll()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki hak melihat rekap absensi.');
            redirect('/dashboard');
        }

        $query = $this->query();
        $page = current_page();
        $perPage = 25;
        $tanggal = $query['tanggal'] ?? null;
        $anggotaId = isset($query['anggota_id']) ? (int) $query['anggota_id'] : null;

        $result = $this->attendance->paginate($page, $perPage, $tanggal ?: null, $anggotaId ?: null);
        $pagination = build_pagination($result['total'], $perPage, $page);

        return $this->render('dashboard/attendance/index', [
            'title' => 'Absensi',
            'items' => $result['data'],
            'pagination' => $pagination,
            'filters' => [
                'tanggal' => $tanggal,
                'anggota_id' => $anggotaId,
            ],
            'anggotaOptions' => $this->anggota->forDropdown(),
            'canManage' => $this->canManage(),
        ], 'dashboard/layout');
    }

    public function member(): string
    {
        $userId = auth_id();
        $anggota = $userId ? $this->anggota->findByUserId($userId) : null;
        if (!$anggota) {
            flash('error', 'Profil anggota tidak ditemukan.');
            redirect('/dashboard');
        }

        $page = current_page();
        $perPage = 25;
        $result = $this->attendance->paginate($page, $perPage, null, (int) $anggota['id_absen']);
        $pagination = build_pagination($result['total'], $perPage, $page);

        return $this->render('dashboard/attendance/member', [
            'title' => 'Absensi Saya',
            'items' => $result['data'],
            'pagination' => $pagination,
            'anggota' => $anggota,
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        if (!$this->canManage()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki hak mengubah data absensi.');
            redirect('/dashboard/absensi');
        }

        $data = $this->request();
        store_old_input($data);
        $rules = [
            'anggota_id' => 'required|exists:anggota,id_absen',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'keterangan' => 'nullable|max:255',
        ];
        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data absensi.');
            redirect('/dashboard/absensi');
        }

        $payload = [
            'anggota_id' => (int) $data['anggota_id'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'keterangan' => !empty($data['keterangan']) ? $data['keterangan'] : null,
            'recorded_by' => auth_id(),
        ];

        if (!empty($data['id'])) {
            $this->attendance->update((int) $data['id'], $payload);
        } else {
            $this->attendance->upsert($payload);
        }

        clear_old_input();
        flash('success', 'Data absensi berhasil disimpan.');
        redirect('/dashboard/absensi');
    }

    public function delete(): void
    {
        if (!$this->canManage()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki hak menghapus data absensi.');
            redirect('/dashboard/absensi');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'Data absensi tidak ditemukan.');
            redirect('/dashboard/absensi');
        }

        $this->attendance->delete($id);
        flash('success', 'Data absensi berhasil dihapus.');
        redirect('/dashboard/absensi');
    }

    private function canManage(): bool
    {
        return auth_has_role(self::MANAGE_ROLES);
    }

    private function canViewAll(): bool
    {
        return $this->canManage() || auth_has_role(array_diff(self::VIEW_ALL_ROLES, self::MANAGE_ROLES));
    }
}