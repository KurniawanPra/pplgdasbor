<?php

namespace App\Controllers;

use App\Models\Roster;

class RosterController extends Controller
{
    private Roster $roster;
    private const VALID_DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    public function __construct()
    {
        $this->roster = new Roster();
    }

    public function index(): string
    {
        return $this->render('dashboard/roster/index', [
            'title' => 'Jadwal Pelajaran',
            'roster' => $this->roster->getAll(),
        ], 'dashboard/layout');
    }

    public function store(): void
    {
        $data = $this->request();
        $rules = [
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'nama_mapel' => 'required|max:100',
        ];

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data roster.');
            redirect('/dashboard/roster');
        }

        if ($this->roster->create([
            'hari' => $data['hari'],
            'nama_mapel' => $data['nama_mapel'],
        ])) {
            flash('success', 'Data roster berhasil ditambahkan.');
        } else {
            flash('error', 'Terjadi kesalahan saat menyimpan roster.');
        }

        redirect('/dashboard/roster');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id && $this->roster->delete($id)) {
            flash('success', 'Data roster berhasil dihapus.');
        } else {
            flash('error', 'Gagal menghapus data roster.');
        }

        redirect('/dashboard/roster');
    }
}
