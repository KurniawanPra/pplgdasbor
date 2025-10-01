<?php

namespace App\Controllers;

use App\Models\Prestasi;

class PrestasiController extends Controller
{
    private Prestasi $prestasi;

    public function __construct()
    {
        $this->prestasi = new Prestasi();
    }

    public function index(): string
    {
        $query = $this->query();
        $search = trim($query['q'] ?? '');
        $page = current_page();
        $perPage = 10;

        $result = $this->prestasi->paginate($page, $perPage, $search ?: null);
        $pagination = build_pagination($result['total'], $perPage, $page);

        return $this->render('dashboard/prestasi/index', [
            'title' => 'Daftar Prestasi',
            'items' => $result['data'],
            'pagination' => $pagination,
            'search' => $search,
        ], 'dashboard/layout');
    }

    public function create(): string
    {
        return $this->render('dashboard/prestasi/create', [
            'title' => 'Tambah Prestasi',
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        $data = $this->request();
        store_old_input($data);

        $errors = $this->validateForm($data);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data prestasi.');
            redirect('/dashboard/prestasi/create');
        }

        $payload = $this->mapData($data);
        $payload['created_by'] = auth_id();

        $this->prestasi->create($payload);
        clear_old_input();
        flash('success', 'Prestasi berhasil ditambahkan.');
        redirect('/dashboard/prestasi');
    }

    public function edit(): string
    {
        $id = (int) ($this->query()['id'] ?? 0);
        $item = $this->prestasi->find($id);
        if (!$item) {
            http_response_code(404);
            return $this->render('pages/404', ['title' => 'Prestasi tidak ditemukan']);
        }

        return $this->render('dashboard/prestasi/edit', [
            'title' => 'Edit Prestasi',
            'item' => $item,
        ], 'dashboard/layout');
    }

    public function update(): void
    {
        $data = $this->request();
        $id = (int) ($data['id'] ?? 0);
        store_old_input($data);

        $item = $this->prestasi->find($id);
        if (!$item) {
            flash('error', 'Prestasi tidak ditemukan.');
            redirect('/dashboard/prestasi');
        }

        $errors = $this->validateForm($data);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data prestasi.');
            redirect('/dashboard/prestasi/edit?id=' . $id);
        }

        $payload = $this->mapData($data);
        if ($this->prestasi->update($id, $payload)) {
            clear_old_input();
            flash('success', 'Prestasi berhasil diperbarui.');
        } else {
            flash('error', 'Gagal memperbarui prestasi.');
        }

        redirect('/dashboard/prestasi');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $item = $this->prestasi->find($id);
        if (!$item) {
            flash('error', 'Data prestasi tidak ditemukan.');
            redirect('/dashboard/prestasi');
        }

        if ($this->prestasi->delete($id)) {
            flash('success', 'Prestasi berhasil dihapus.');
        } else {
            flash('error', 'Gagal menghapus prestasi.');
        }

        redirect('/dashboard/prestasi');
    }

    private function validateForm(array $data): array
    {
        $rules = [
            'judul' => 'required|max:150',
            'deskripsi' => 'nullable|max:2000',
            'penyelenggara' => 'nullable|max:120',
            'tingkat' => 'nullable|max:50',
            'tanggal' => 'nullable',
            'lokasi' => 'nullable|max:120',
        ];

        $errors = validate($data, $rules);

        if (!empty($data['tanggal'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $data['tanggal']);
            $dateErrors = \DateTime::getLastErrors();
            if (!$date || $dateErrors['warning_count'] || $dateErrors['error_count']) {
                $errors['tanggal'][] = 'Format tanggal harus YYYY-MM-DD.';
            }
        }

        return $errors;
    }

    private function mapData(array $data): array
    {
        $normalize = static function ($value) {
            return ($value ?? '') === '' ? null : $value;
        };

        return [
            'judul' => $data['judul'] ?? '',
            'deskripsi' => $normalize($data['deskripsi'] ?? null),
            'penyelenggara' => $normalize($data['penyelenggara'] ?? null),
            'tingkat' => $normalize($data['tingkat'] ?? null),
            'tanggal' => $normalize($data['tanggal'] ?? null),
            'lokasi' => $normalize($data['lokasi'] ?? null),
        ];
    }
}
