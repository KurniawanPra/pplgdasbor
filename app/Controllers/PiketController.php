<?php

namespace App\Controllers;

use App\Models\Anggota;
use App\Models\Piket;

class PiketController extends Controller
{
    private Piket $piket;
    private Anggota $anggota;

    public function __construct()
    {
        $this->piket = new Piket();
        $this->anggota = new Anggota();
    }

    public function index(): string
    {
        return $this->render('dashboard/piket/index', [
            'title' => 'Jadwal Piket',
            'piket' => $this->piket->getGrouped(),
            'anggotaOptions' => $this->anggota->forDropdown(),
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        $data = $this->request();
        $hari = $data['hari'] ?? '';
        $anggotaIds = $data['anggota_ids'] ?? [];
        if (!is_array($anggotaIds)) {
            $anggotaIds = [$anggotaIds];
        }
        $anggotaIds = array_filter(array_map('intval', $anggotaIds));

        $allowedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        if (!in_array($hari, $allowedDays, true)) {
            flash('error', 'Hari piket tidak valid.');
            redirect('/dashboard/piket');
        }

        if (empty($anggotaIds)) {
            flash('error', 'Pilih minimal satu anggota untuk piket.');
            redirect('/dashboard/piket');
        }

        try {
            $this->piket->replaceForDay($hari, $anggotaIds);
            flash('success', 'Jadwal piket berhasil diperbarui.');
        } catch (\Throwable $e) {
            app_log('error', 'Gagal memperbarui piket', ['error' => $e->getMessage()]);
            flash('error', 'Terjadi kesalahan saat menyimpan jadwal piket.');
        }

        redirect('/dashboard/piket');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id && $this->piket->delete($id)) {
            flash('success', 'Data piket berhasil dihapus.');
        } else {
            flash('error', 'Gagal menghapus data piket.');
        }
        redirect('/dashboard/piket');
    }
}
