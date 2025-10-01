<?php

namespace App\Controllers;

use App\Models\Anggota;
use App\Models\User;

class AnggotaController extends Controller
{
    private Anggota $anggota;
    private User $users;

    public function __construct()
    {
        $this->anggota = new Anggota();
        $this->users = new User();
    }

    public function index(): string
    {
        $query = $this->query();
        $search = trim($query['q'] ?? '');
        $page = current_page();
        $perPage = 10;
        $result = $this->anggota->paginate($page, $perPage, $search !== '' ? $search : null);
        $pagination = build_pagination($result['total'], $perPage, $page);

        return $this->render('dashboard/anggota/index', [
            'title' => 'Data Anggota',
            'items' => $result['data'],
            'pagination' => $pagination,
            'search' => $search,
        ], 'dashboard/layout');
    }

    public function create(): string
    {
        return $this->render('dashboard/anggota/create', [
            'title' => 'Tambah Anggota',
            'userOptions' => $this->users->getByRole('anggota'),
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        $data = $this->request();
        store_old_input($data);

        $rules = [
            'id_absen' => 'required|numeric|unique:anggota,id_absen',
            'nis' => 'required|max:30|unique:anggota,nis',
            'nama_lengkap' => 'required|max:100',
            'panggilan' => 'required|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan' => 'required|in:anggota,ketua,wakil,sekretaris,bendahara',
            'sosmed' => 'nullable|max:100',
            'nomor_hp' => 'nullable|phone',
            'email' => 'nullable|email|max:100',
            'status' => 'required|in:aktif,alumni,nonaktif',
            'cita_cita' => 'nullable|max:150',
            'tujuan_hidup' => 'nullable|max:150',
            'hobi' => 'nullable|max:150',
        ];

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data anggota.');
            redirect('/dashboard/anggota/create');
        }

        $fotoResult = handle_upload($_FILES['foto'] ?? [], 'siswa', ['resize' => true]);
        if ($fotoResult['error']) {
            flash('error', $fotoResult['error']);
            redirect('/dashboard/anggota/create');
        }

        $payload = [
            'id_absen' => (int) $data['id_absen'],
            'nis' => $data['nis'],
            'nama_lengkap' => $data['nama_lengkap'],
            'panggilan' => $data['panggilan'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'jabatan' => $data['jabatan'],
            'sosmed' => $data['sosmed'] ?: null,
            'nomor_hp' => $data['nomor_hp'] ?: null,
            'email' => $data['email'] ?: null,
            'status' => $data['status'],
            'cita_cita' => $data['cita_cita'] ?: null,
            'tujuan_hidup' => $data['tujuan_hidup'] ?: null,
            'hobi' => $data['hobi'] ?: null,
            'foto' => $fotoResult['path'],
            'user_id' => !empty($data['user_id']) ? (int) $data['user_id'] : null,
        ];

        if ($this->anggota->create($payload)) {
            clear_old_input();
            flash('success', 'Data anggota berhasil ditambahkan.');
            redirect('/dashboard/anggota');
        }

        flash('error', 'Terjadi kesalahan saat menyimpan data.');
        redirect('/dashboard/anggota/create');
    }

    public function edit(): string
    {
        $id = (int) ($this->query()['id'] ?? 0);
        $anggota = $this->anggota->find($id);

        if (!$anggota) {
            http_response_code(404);
            return $this->render('pages/404', ['title' => 'Anggota tidak ditemukan']);
        }

        return $this->render('dashboard/anggota/edit', [
            'title' => 'Edit Anggota',
            'anggota' => $anggota,
            'userOptions' => $this->users->getByRole('anggota'),
        ], 'dashboard/layout');
    }

    public function update(): void
    {
        $data = $this->request();
        $id = (int) ($data['id_absen'] ?? 0);
        store_old_input($data);

        $anggota = $this->anggota->find($id);
        if (!$anggota) {
            flash('error', 'Data anggota tidak ditemukan.');
            redirect('/dashboard/anggota');
        }

        $rules = [
            'nis' => 'required|max:30|unique:anggota,nis,' . $id . ',id_absen',
            'nama_lengkap' => 'required|max:100',
            'panggilan' => 'required|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan' => 'required|in:anggota,ketua,wakil,sekretaris,bendahara',
            'sosmed' => 'nullable|max:100',
            'nomor_hp' => 'nullable|phone',
            'email' => 'nullable|email|max:100',
            'status' => 'required|in:aktif,alumni,nonaktif',
            'cita_cita' => 'nullable|max:150',
            'tujuan_hidup' => 'nullable|max:150',
            'hobi' => 'nullable|max:150',
        ];

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data anggota.');
            redirect('/dashboard/anggota/edit?id=' . $id);
        }

        $fotoPath = $anggota['foto'] ?? null;
        $fotoResult = handle_upload($_FILES['foto'] ?? [], 'siswa', ['resize' => true]);
        if ($fotoResult['error']) {
            flash('error', $fotoResult['error']);
            redirect('/dashboard/anggota/edit?id=' . $id);
        }

        if ($fotoResult['path']) {
            delete_uploaded_file($fotoPath);
            $fotoPath = $fotoResult['path'];
        }

        $payload = [
            'nis' => $data['nis'],
            'nama_lengkap' => $data['nama_lengkap'],
            'panggilan' => $data['panggilan'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'jabatan' => $data['jabatan'],
            'sosmed' => $data['sosmed'] ?: null,
            'nomor_hp' => $data['nomor_hp'] ?: null,
            'email' => $data['email'] ?: null,
            'status' => $data['status'],
            'cita_cita' => $data['cita_cita'] ?: null,
            'tujuan_hidup' => $data['tujuan_hidup'] ?: null,
            'hobi' => $data['hobi'] ?: null,
            'foto' => $fotoPath,
            'user_id' => !empty($data['user_id']) ? (int) $data['user_id'] : null,
        ];

        if ($this->anggota->update($id, $payload)) {
            clear_old_input();
            flash('success', 'Data anggota berhasil diperbarui.');
            redirect('/dashboard/anggota');
        }

        flash('error', 'Terjadi kesalahan saat memperbarui data.');
        redirect('/dashboard/anggota/edit?id=' . $id);
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $anggota = $this->anggota->find($id);

        if (!$anggota) {
            flash('error', 'Data anggota tidak ditemukan.');
            redirect('/dashboard/anggota');
        }

        if ($this->anggota->delete($id)) {
            delete_uploaded_file($anggota['foto'] ?? null);
            flash('success', 'Data anggota berhasil dihapus.');
        } else {
            flash('error', 'Gagal menghapus data anggota.');
        }

        redirect('/dashboard/anggota');
    }

    public function apiIndex(): void
    {
        $perPage = (int) ($_GET['limit'] ?? 6);
        if ($perPage <= 0) {
            $perPage = 6;
        }
        $page = current_page();
        $search = trim($_GET['q'] ?? '');
        $result = $this->anggota->getLanding($perPage, $page, $search !== '' ? $search : null);
        $pagination = build_pagination($result['total'], $perPage, $page);

        $data = array_map(function ($member) {
            $image = $member['foto'] ? '/uploads.php?path=' . urlencode($member['foto']) : '/assets/img/avatar-placeholder.png';
            $jabatan = $member['jabatan'] ? ucwords(str_replace('_', ' ', $member['jabatan'])) : null;
            $status = $member['status'] ? ucfirst($member['status']) : null;

            return [
                'id_absen' => (int) $member['id_absen'],
                'nis' => $member['nis'],
                'nama_lengkap' => $member['nama_lengkap'],
                'panggilan' => $member['panggilan'],
                'jenis_kelamin' => $member['jenis_kelamin'],
                'jabatan' => $jabatan,
                'sosmed' => $member['sosmed'],
                'nomor_hp' => $member['nomor_hp'],
                'email' => $member['email'],
                'status' => $status,
                'cita_cita' => $member['cita_cita'],
                'tujuan_hidup' => $member['tujuan_hidup'],
                'hobi' => $member['hobi'],
                'foto' => $member['foto'],
                'image_url' => $image,
            ];
        }, $result['data']);

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => $pagination,
        ]);
        exit;
    }
}