<?php

namespace App\Controllers;

use App\Models\Anggota;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Piket;
use App\Models\Prestasi;
use App\Models\Roster;
use App\Models\Spp;
use App\Models\Struktur;
use App\Models\User;
use App\Models\WeeklyTask;

class AdminController extends Controller
{
    public function landing(): string
    {
        $anggotaModel = new Anggota();
        $strukturModel = new Struktur();
        $piketModel = new Piket();
        $rosterModel = new Roster();
        $galleryModel = new Gallery();
        $prestasiModel = new Prestasi();

        $perPage = 6;
        $page = current_page();
        $search = trim($_GET['anggota_search'] ?? '') ?: null;
        $anggotaData = $anggotaModel->getLanding($perPage, $page, $search);
        $pagination = build_pagination($anggotaData['total'], $perPage, $page);

        $galleryPerPage = 6;
        $galleryPage = filter_input(INPUT_GET, 'gallery_page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
        $galleryResult = $galleryModel->paginate($galleryPage, $galleryPerPage);
        $galleryPagination = build_pagination($galleryResult['total'], $galleryPerPage, $galleryPage);

        $prestasi = $prestasiModel->latest(6);
        if (empty($prestasi)) {
            $prestasi = $this->prestasiSeed();
        }

        return $this->render('landing/index', [
            'title' => config('app.name'),
            'struktur' => $strukturModel->getAll(),
            'anggota' => $anggotaData['data'],
            'pagination' => $pagination,
            'piket' => $piketModel->getGrouped(),
            'roster' => $rosterModel->getAll(),
            'gallery' => $galleryResult['data'],
            'galleryPagination' => $galleryPagination,
            'prestasi' => $prestasi,
            'settings' => $this->loadSettings(),
            'anggotaSearch' => $search,
        ], 'partials/landing-layout');
    }

    public function dashboard(): string
    {
        $role = auth_role();
        $userId = auth_id();

        if ($role === 'anggota') {
            $anggotaModel = new Anggota();
            $sppModel = new Spp();
            $attendanceModel = new Attendance();
            $announcementModel = new Announcement();
            $taskModel = new WeeklyTask();

            $anggota = $userId ? $anggotaModel->findByUserId($userId) : null;
            $spp = $anggota ? $sppModel->paginate(1, 6, (int) $anggota['id_absen'], (int) date('Y'), null, null)['data'] : [];
            $attendance = $anggota ? $attendanceModel->paginate(1, 10, null, (int) $anggota['id_absen'])['data'] : [];
            $announcements = $announcementModel->paginate(1, 5, 'anggota')['data'];
            $tasks = $taskModel->all(true);

            return $this->render('dashboard/member/home', [
                'title' => 'Beranda Anggota',
                'anggota' => $anggota,
                'spp' => $spp,
                'attendance' => $attendance,
                'announcements' => $announcements,
                'tasks' => $tasks,
            ], 'dashboard/layout');
        }

        $anggotaModel = new Anggota();
        $galleryModel = new Gallery();
        $prestasiModel = new Prestasi();
        $userModel = new User();
        $contactModel = new Contact();
        $sppModel = new Spp();
        $attendanceModel = new Attendance();
        $taskModel = new WeeklyTask();
        $announcementModel = new Announcement();

        $stats = [
            'total_anggota' => $anggotaModel->count(),
            'anggota_aktif' => $anggotaModel->count('aktif'),
            'total_pengurus' => $userModel->countByRoles(['wali_kelas', 'ketua', 'wakil_ketua', 'bendahara', 'sekretaris']),
            'total_gallery' => $galleryModel->paginate(1, 1)['total'],
            'pesan_masuk' => $contactModel->paginate(1, 1)['total'],
            'total_prestasi' => $prestasiModel->count(),
            'spp_bulan_ini' => $sppModel->paginate(1, 1, null, (int) date('Y'), (int) date('n'))['total'],
            'absensi_hari_ini' => $attendanceModel->paginate(1, 1, date('Y-m-d'), null)['total'],
        ];

        $recentAnnouncements = $announcementModel->paginate(1, 5, 'pengurus')['data'];
        $tasks = $taskModel->all(true);

        return $this->render('dashboard/index', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'announcements' => $recentAnnouncements,
            'tasks' => $tasks,
        ], 'dashboard/layout');
    }

    private function prestasiSeed(): array
    {
        return [
            [
                'judul' => 'Juara 2 Lomba Desain UI/UX',
                'deskripsi' => 'Tim XI PPLG meraih juara dua pada ajang desain antarmuka tingkat kabupaten.',
                'penyelenggara' => 'Disdik Simalungun',
                'tingkat' => 'Kabupaten',
                'tanggal' => '2024-08-12',
                'lokasi' => 'Balai Budaya Simalungun',
            ],
            [
                'judul' => 'Top 5 Hackathon Pelajar',
                'deskripsi' => 'Kolaborasi perangkat kelas menghasilkan prototipe aplikasi layanan sekolah dan lolos lima besar.',
                'penyelenggara' => 'Dinas Pendidikan Sumut',
                'tingkat' => 'Provinsi',
                'tanggal' => '2024-05-05',
                'lokasi' => 'Medan',
            ],
            [
                'judul' => 'Kelas Terbersih Ramadhan',
                'deskripsi' => 'Seluruh anggota mendapatkan penghargaan karena menjaga kebersihan kelas selama bulan Ramadhan.',
                'penyelenggara' => 'OSIS SMK Swasta Al Washliyah 2',
                'tingkat' => 'Sekolah',
                'tanggal' => '2024-03-22',
                'lokasi' => 'SMK Swasta Al Washliyah 2',
            ],
        ];
    }

    private function loadSettings(): array
    {
        $file = BASE_PATH . '/storage/settings.json';
        if (!file_exists($file)) {
            return [
                'motto' => 'Belajar. Berkarya. Berprestasi Bersama.',
                'about' => 'Kami adalah kelas XI PPLG yang siap melahirkan talenta digital unggulan.',
                'contact_email' => 'xi1pplg@sekolah.sch.id',
                'contact_phone' => '081234567890',
                'instagram' => '@xi1pplg.aw2',
            ];
        }

        $content = file_get_contents($file);
        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }
}