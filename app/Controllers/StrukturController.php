<?php

namespace App\Controllers;

use App\Models\Anggota;
use App\Models\Struktur;

class StrukturController extends Controller
{
    private Struktur $struktur;
    private Anggota $anggota;

    public function __construct()
    {
        $this->struktur = new Struktur();
        $this->anggota = new Anggota();
    }

    public function index(): string
    {
        return $this->render('dashboard/struktur/index', [
            'title' => 'Struktur Kelas',
            'struktur' => $this->struktur->getAll(),
            'anggotaOptions' => $this->anggota->forDropdown(),
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        $data = $this->request();
        $rules = [
            'jabatan' => 'required|in:wali_kelas,ketua,wakil,sekretaris,bendahara',
            'anggota_id' => 'required|numeric',
        ];

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data struktur.');
            redirect('/dashboard/struktur');
        }

        try {
            $this->struktur->assign($data['jabatan'], (int) $data['anggota_id']);
            flash('success', 'Struktur kelas berhasil diperbarui.');
        } catch (\Throwable $e) {
            app_log('error', 'Gagal memperbarui struktur', ['error' => $e->getMessage()]);
            flash('error', 'Terjadi kesalahan saat memperbarui struktur.');
        }

        redirect('/dashboard/struktur');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id && $this->struktur->delete($id)) {
            flash('success', 'Data struktur berhasil dihapus.');
        } else {
            flash('error', 'Gagal menghapus data struktur.');
        }
        redirect('/dashboard/struktur');
    }
}
