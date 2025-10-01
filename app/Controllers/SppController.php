<?php

namespace App\Controllers;

use App\Models\Anggota;
use App\Models\Spp;

class SppController extends Controller
{
    private Spp $spp;
    private Anggota $anggota;

    private const MANAGE_ROLES = ['administrator', 'superadmin', 'wali_kelas', 'bendahara'];
    private const VIEW_ALL_ROLES = ['administrator', 'superadmin', 'wali_kelas', 'ketua', 'wakil_ketua', 'bendahara', 'sekretaris'];

    public function __construct()
    {
        $this->spp = new Spp();
        $this->anggota = new Anggota();
    }

    public function index(): string
    {
        $query = $this->query();
        $page = current_page();
        $perPage = 12;

        $anggotaId = isset($query['anggota_id']) ? (int) $query['anggota_id'] : null;
        $year = isset($query['tahun']) ? (int) $query['tahun'] : null;
        $month = isset($query['bulan']) ? (int) $query['bulan'] : null;
        $keyword = trim($query['q'] ?? '');

        $result = $this->spp->paginate($page, $perPage, $anggotaId ?: null, $year ?: null, $month ?: null, $keyword ?: null);
        $pagination = build_pagination($result['total'], $perPage, $page);

        $editId = isset($query['edit']) ? (int) $query['edit'] : null;
        $editItem = null;
        if ($editId && $this->canManage()) {
            $editItem = $this->spp->find($editId);
        }

        $canManage = $this->canManage();
        $canViewAll = $this->canViewAll();

        if (!$canViewAll && !$canManage) {
            // fallback: anggota should not hit this route
            redirect('/dashboard/spp/me');
        }

        return $this->render('dashboard/spp/index', [
            'title' => 'Data SPP',
            'items' => $result['data'],
            'pagination' => $pagination,
            'filters' => [
                'anggota_id' => $anggotaId,
                'tahun' => $year,
                'bulan' => $month,
                'q' => $keyword,
            ],
            'anggotaOptions' => $this->anggota->forDropdown(),
            'canManage' => $canManage,
            'editItem' => $editItem,
            'currentRole' => auth_role(),
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

        $query = $this->query();
        $page = current_page();
        $perPage = 12;
        $year = isset($query['tahun']) ? (int) $query['tahun'] : null;

        $result = $this->spp->paginate($page, $perPage, (int) $anggota['id_absen'], $year ?: null, null, null);
        $pagination = build_pagination($result['total'], $perPage, $page);

        $editId = isset($query['edit']) ? (int) $query['edit'] : null;
        $editItem = null;
        if ($editId && $this->canManage()) {
            $editItem = $this->spp->find($editId);
        }

        return $this->render('dashboard/spp/member', [
            'title' => 'SPP Saya',
            'items' => $result['data'],
            'pagination' => $pagination,
            'filters' => [
                'tahun' => $year,
            ],
            'anggota' => $anggota,
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        if (!$this->canManage()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki izin mengubah data SPP.');
            redirect('/dashboard/spp');
        }

        $data = $this->request();
        $rules = [
            'anggota_id' => 'required|exists:anggota,id_absen',
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000|max:2100',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:belum,lunas,cicil',
            'tanggal_bayar' => 'nullable|date',
            'catatan' => 'nullable|max:255',
        ];
        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data SPP.');
            redirect('/dashboard/spp');
        }

        $payload = [
            'anggota_id' => (int) $data['anggota_id'],
            'bulan' => (int) $data['bulan'],
            'tahun' => (int) $data['tahun'],
            'jumlah' => (float) $data['jumlah'],
            'status' => $data['status'],
            'tanggal_bayar' => $data['tanggal_bayar'] ?: null,
            'catatan' => $data['catatan'] ?? null,
            'created_by' => auth_id(),
            'updated_by' => auth_id(),
        ];

        if (!empty($data['id'])) {
            $this->spp->update((int) $data['id'], $payload);
        } else {
            $this->spp->upsert($payload);
        }

        flash('success', 'Data SPP berhasil disimpan.');
        redirect('/dashboard/spp');
    }

    public function delete(): void
    {
        if (!$this->canManage()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki izin menghapus data SPP.');
            redirect('/dashboard/spp');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'Data tidak ditemukan.');
            redirect('/dashboard/spp');
        }

        $this->spp->delete($id);
        flash('success', 'Data SPP berhasil dihapus.');
        redirect('/dashboard/spp');
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