<?php

namespace App\Controllers;

use App\Models\WeeklyTask;

class WeeklyTaskController extends Controller
{
    private WeeklyTask $tasks;

    private const EDIT_ROLES = ['administrator', 'superadmin', 'wali_kelas', 'ketua', 'wakil_ketua', 'bendahara', 'sekretaris'];

    public function __construct()
    {
        $this->tasks = new WeeklyTask();
    }

    public function index(): string
    {
        $page = current_page();
        $perPage = 12;
        $result = $this->tasks->paginate($page, $perPage);
        $pagination = build_pagination($result['total'], $perPage, $page);

        return $this->render('dashboard/tasks/index', [
            'title' => 'Tugas Mingguan',
            'items' => $result['data'],
            'pagination' => $pagination,
            'canEdit' => $this->canEdit(),
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        if (!$this->canEdit()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki hak mengubah tugas mingguan.');
            redirect('/dashboard/tugas');
        }

        $data = $this->request();
        $rules = [
            'judul' => 'required|max:150',
            'deskripsi' => 'nullable',
            'deadline' => 'nullable|date',
            'is_completed' => 'nullable|boolean',
        ];
        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data tugas.');
            redirect('/dashboard/tugas');
        }

        $payload = [
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'deadline' => $data['deadline'] ?? null,
            'is_completed' => !empty($data['is_completed']),
            'created_by' => auth_id(),
            'updated_by' => auth_id(),
        ];

        if (!empty($data['id'])) {
            $this->tasks->update((int) $data['id'], $payload);
        } else {
            $this->tasks->create($payload);
        }

        flash('success', 'Tugas mingguan tersimpan.');
        redirect('/dashboard/tugas');
    }

    public function delete(): void
    {
        if (!$this->canEdit()) {
            http_response_code(403);
            flash('error', 'Anda tidak memiliki hak menghapus tugas mingguan.');
            redirect('/dashboard/tugas');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'Tugas tidak ditemukan.');
            redirect('/dashboard/tugas');
        }

        $this->tasks->delete($id);
        flash('success', 'Tugas dihapus.');
        redirect('/dashboard/tugas');
    }

    private function canEdit(): bool
    {
        return auth_has_role(self::EDIT_ROLES);
    }
}