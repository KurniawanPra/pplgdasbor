<?php

namespace App\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller
{
    private Announcement $announcements;

    private const MANAGE_ROLES = ['administrator', 'superadmin', 'wali_kelas', 'ketua', 'wakil_ketua', 'bendahara', 'sekretaris'];

    public function __construct()
    {
        $this->announcements = new Announcement();
    }

    public function index(): string
    {
        if (!$this->canManage()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki hak mengelola pengumuman.');
            redirect('/dashboard');
        }

        $query = $this->query();
        $page = current_page();
        $perPage = 10;
        $audience = null;

        $result = $this->announcements->paginate($page, $perPage, null);
        $pagination = build_pagination($result['total'], $perPage, $page);
        $editId = isset($query['edit']) ? (int) $query['edit'] : null;
        $editItem = null;
        if ($editId && $this->canManage()) {
            $editItem = $this->announcements->find($editId);
        }


        return $this->render('dashboard/announcements/index', [
            'title' => 'Pengumuman',
            'items' => $result['data'],
            'editItem' => $editItem,
            'pagination' => $pagination,
        ], 'dashboard/layout');
    }

    public function feed(): string
    {
        $role = auth_role();
        $audience = 'semua';
        if ($role === 'anggota') {
            $audience = 'anggota';
        } elseif ($role && $role !== 'administrator' && $role !== 'superadmin') {
            $audience = 'pengurus';
        }

        $query = $this->query();
        $page = current_page();
        $perPage = 10;
        $result = $this->announcements->paginate($page, $perPage, $audience);
        $pagination = build_pagination($result['total'], $perPage, $page);
        $editId = isset($query['edit']) ? (int) $query['edit'] : null;
        $editItem = null;
        if ($editId && $this->canManage()) {
            $editItem = $this->announcements->find($editId);
        }


        return $this->render('dashboard/announcements/feed', [
            'title' => 'Pengumuman',
            'items' => $result['data'],
            'editItem' => $editItem,
            'pagination' => $pagination,
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        if (!$this->canManage()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki hak mengelola pengumuman.');
            redirect('/dashboard/pengumuman');
        }

        $data = $this->request();
        $rules = [
            'judul' => 'required|max:150',
            'isi' => 'required',
            'audience' => 'required|in:semua,anggota,pengurus',
            'published_at' => 'nullable|date',
        ];
        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data pengumuman.');
            redirect('/dashboard/pengumuman');
        }

        $payload = [
            'judul' => $data['judul'],
            'isi' => $data['isi'],
            'audience' => $data['audience'],
            'published_at' => $data['published_at'] ?? null,
            'created_by' => auth_id(),
        ];

        if (!empty($data['id'])) {
            $this->announcements->update((int) $data['id'], $payload);
        } else {
            $this->announcements->create($payload);
        }

        flash('success', 'Pengumuman berhasil disimpan.');
        redirect('/dashboard/pengumuman');
    }

    public function delete(): void
    {
        if (!$this->canManage()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki hak menghapus pengumuman.');
            redirect('/dashboard/pengumuman');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'Pengumuman tidak ditemukan.');
            redirect('/dashboard/pengumuman');
        }

        $this->announcements->delete($id);
        flash('success', 'Pengumuman dihapus.');
        redirect('/dashboard/pengumuman');
    }

    private function canManage(): bool
    {
        return auth_has_role(self::MANAGE_ROLES);
    }
}