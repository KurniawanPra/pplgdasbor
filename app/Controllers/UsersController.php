<?php

namespace App\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function index(): string
    {
        $query = $this->query();
        $search = trim($query['q'] ?? '');
        $page = current_page();
        $perPage = 10;

        $result = $search ? $this->searchUsers($search, $page, $perPage) : $this->users->paginate($page, $perPage);
        $pagination = build_pagination($result['total'], $perPage, $page);

        return $this->render('dashboard/users/index', [
            'title' => 'Manajemen Pengguna',
            'users' => $result['data'],
            'pagination' => $pagination,
            'search' => $search,
        ], 'dashboard/layout');
    }

    private function searchUsers(string $search, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $db = db();
        $like = '%' . $search . '%';

        $stmt = $db->prepare('SELECT * FROM users WHERE nama_lengkap LIKE :name OR username LIKE :username OR email LIKE :email ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':name', $like);
        $stmt->bindValue(':username', $like);
        $stmt->bindValue(':email', $like);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $count = $db->prepare('SELECT COUNT(*) FROM users WHERE nama_lengkap LIKE :name OR username LIKE :username OR email LIKE :email');
        $count->bindValue(':name', $like);
        $count->bindValue(':username', $like);
        $count->bindValue(':email', $like);
        $count->execute();
        $total = (int) $count->fetchColumn();

        return ['data' => $data, 'total' => $total];
    }

    public function create(): string
    {
        return $this->render('dashboard/users/create', [
            'title' => 'Tambah Pengguna',
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        $data = $this->request();
        $old = $data;
        unset($old['password']);
        store_old_input($old);

        $rules = [
            'nama_lengkap' => 'required|max:100',
            'username' => 'required|alpha_dash|min:4|max:20|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|password',
            'role' => 'required|in:superadmin,pengurus,anggota',
        ];

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data pengguna.');
            redirect('/dashboard/users/create');
        }

        $payload = [
            'nama_lengkap' => $data['nama_lengkap'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'],
        ];

        if ($this->users->create($payload)) {
            clear_old_input();
            flash('success', 'Pengguna berhasil ditambahkan.');
            redirect('/dashboard/users');
        }

        flash('error', 'Terjadi kesalahan saat menambah pengguna.');
        redirect('/dashboard/users/create');
    }

    public function edit(): string
    {
        $id = (int) ($this->query()['id'] ?? 0);
        $user = $this->users->find($id);
        if (!$user) {
            http_response_code(404);
            return $this->render('pages/404', ['title' => 'Pengguna tidak ditemukan']);
        }

        return $this->render('dashboard/users/edit', [
            'title' => 'Edit Pengguna',
            'user' => $user,
        ], 'dashboard/layout');
    }

    public function update(): void
    {
        $data = $this->request();
        $id = (int) ($data['id'] ?? 0);
        $old = $data;
        unset($old['password']);
        store_old_input($old);

        $user = $this->users->find($id);
        if (!$user) {
            flash('error', 'Pengguna tidak ditemukan.');
            redirect('/dashboard/users');
        }

        $rules = [
            'nama_lengkap' => 'required|max:100',
            'username' => 'required|alpha_dash|min:4|max:20|unique:users,username,' . $id . ',id',
            'email' => 'required|email|max:100|unique:users,email,' . $id . ',id',
            'role' => 'required|in:superadmin,pengurus,anggota',
        ];

        if (!empty($data['password'])) {
            $rules['password'] = 'password';
        }

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data pengguna.');
            redirect('/dashboard/users/edit?id=' . $id);
        }

        $payload = [
            'nama_lengkap' => $data['nama_lengkap'],
            'username' => $data['username'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($this->users->update($id, $payload)) {
            clear_old_input();
            flash('success', 'Data pengguna diperbarui.');
            redirect('/dashboard/users');
        }

        flash('error', 'Terjadi kesalahan saat memperbarui pengguna.');
        redirect('/dashboard/users/edit?id=' . $id);
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id === auth_id()) {
            flash('error', 'Tidak dapat menghapus akun sendiri.');
            redirect('/dashboard/users');
        }

        $user = $this->users->find($id);
        if (!$user) {
            flash('error', 'Pengguna tidak ditemukan.');
            redirect('/dashboard/users');
        }

        if ($this->users->delete($id)) {
            flash('success', 'Pengguna berhasil dihapus.');
        } else {
            flash('error', 'Gagal menghapus pengguna.');
        }

        redirect('/dashboard/users');
    }

    public function updateProfile(): void
    {
        if (!auth_has_role(['administrator', 'superadmin'])) {
            http_response_code(403);
            return;
        }
        // Profile update logic
    }
}

