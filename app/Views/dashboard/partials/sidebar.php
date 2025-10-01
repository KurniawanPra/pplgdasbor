<?php
$current = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$userRole = auth_role() ?? 'guest';

$menuMap = [
    'administrator' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi'],
        ['icon' => 'bi-people', 'label' => 'Anggota', 'href' => '/dashboard/anggota'],
        ['icon' => 'bi-diagram-3', 'label' => 'Struktur Kelas', 'href' => '/dashboard/struktur'],
        ['icon' => 'bi-calendar-week', 'label' => 'Jadwal Piket', 'href' => '/dashboard/piket'],
        ['icon' => 'bi-calendar-event', 'label' => 'Roster Pelajaran', 'href' => '/dashboard/roster'],
        ['icon' => 'bi-image', 'label' => 'Galeri', 'href' => '/dashboard/gallery'],
        ['icon' => 'bi-trophy', 'label' => 'Prestasi', 'href' => '/dashboard/prestasi'],
        ['icon' => 'bi-envelope', 'label' => 'Pesan Masuk', 'href' => '/dashboard/pesan'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
        ['icon' => 'bi-people-fill', 'label' => 'Pengguna', 'href' => '/dashboard/users'],
        ['icon' => 'bi-gear', 'label' => 'Pengaturan', 'href' => '/dashboard/settings'],
    ],
    'superadmin' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi'],
        ['icon' => 'bi-people', 'label' => 'Anggota', 'href' => '/dashboard/anggota'],
        ['icon' => 'bi-diagram-3', 'label' => 'Struktur Kelas', 'href' => '/dashboard/struktur'],
        ['icon' => 'bi-calendar-week', 'label' => 'Jadwal Piket', 'href' => '/dashboard/piket'],
        ['icon' => 'bi-calendar-event', 'label' => 'Roster Pelajaran', 'href' => '/dashboard/roster'],
        ['icon' => 'bi-image', 'label' => 'Galeri', 'href' => '/dashboard/gallery'],
        ['icon' => 'bi-trophy', 'label' => 'Prestasi', 'href' => '/dashboard/prestasi'],
        ['icon' => 'bi-envelope', 'label' => 'Pesan Masuk', 'href' => '/dashboard/pesan'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
        ['icon' => 'bi-people-fill', 'label' => 'Pengguna', 'href' => '/dashboard/users'],
        ['icon' => 'bi-gear', 'label' => 'Pengaturan', 'href' => '/dashboard/settings'],
    ],
    'wali_kelas' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi'],
        ['icon' => 'bi-people', 'label' => 'Anggota', 'href' => '/dashboard/anggota'],
        ['icon' => 'bi-diagram-3', 'label' => 'Struktur Kelas', 'href' => '/dashboard/struktur'],
        ['icon' => 'bi-calendar-week', 'label' => 'Jadwal Piket', 'href' => '/dashboard/piket'],
        ['icon' => 'bi-calendar-event', 'label' => 'Roster Pelajaran', 'href' => '/dashboard/roster'],
        ['icon' => 'bi-image', 'label' => 'Galeri', 'href' => '/dashboard/gallery'],
        ['icon' => 'bi-trophy', 'label' => 'Prestasi', 'href' => '/dashboard/prestasi'],
        ['icon' => 'bi-envelope', 'label' => 'Pesan Masuk', 'href' => '/dashboard/pesan'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
    ],
    'ketua' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi'],
        ['icon' => 'bi-calendar-event', 'label' => 'Roster Pelajaran', 'href' => '/dashboard/roster'],
        ['icon' => 'bi-calendar-week', 'label' => 'Jadwal Piket', 'href' => '/dashboard/piket'],
        ['icon' => 'bi-image', 'label' => 'Galeri', 'href' => '/dashboard/gallery'],
        ['icon' => 'bi-trophy', 'label' => 'Prestasi', 'href' => '/dashboard/prestasi'],
        ['icon' => 'bi-envelope', 'label' => 'Pesan Masuk', 'href' => '/dashboard/pesan'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
    ],
    'wakil_ketua' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi'],
        ['icon' => 'bi-calendar-event', 'label' => 'Roster Pelajaran', 'href' => '/dashboard/roster'],
        ['icon' => 'bi-calendar-week', 'label' => 'Jadwal Piket', 'href' => '/dashboard/piket'],
        ['icon' => 'bi-image', 'label' => 'Galeri', 'href' => '/dashboard/gallery'],
        ['icon' => 'bi-trophy', 'label' => 'Prestasi', 'href' => '/dashboard/prestasi'],
        ['icon' => 'bi-envelope', 'label' => 'Pesan Masuk', 'href' => '/dashboard/pesan'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
    ],
    'bendahara' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi'],
        ['icon' => 'bi-calendar-event', 'label' => 'Roster Pelajaran', 'href' => '/dashboard/roster'],
        ['icon' => 'bi-calendar-week', 'label' => 'Jadwal Piket', 'href' => '/dashboard/piket'],
        ['icon' => 'bi-image', 'label' => 'Galeri', 'href' => '/dashboard/gallery'],
        ['icon' => 'bi-trophy', 'label' => 'Prestasi', 'href' => '/dashboard/prestasi'],
        ['icon' => 'bi-envelope', 'label' => 'Pesan Masuk', 'href' => '/dashboard/pesan'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
    ],
    'sekretaris' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi'],
        ['icon' => 'bi-calendar-event', 'label' => 'Roster Pelajaran', 'href' => '/dashboard/roster'],
        ['icon' => 'bi-calendar-week', 'label' => 'Jadwal Piket', 'href' => '/dashboard/piket'],
        ['icon' => 'bi-image', 'label' => 'Galeri', 'href' => '/dashboard/gallery'],
        ['icon' => 'bi-trophy', 'label' => 'Prestasi', 'href' => '/dashboard/prestasi'],
        ['icon' => 'bi-envelope', 'label' => 'Pesan Masuk', 'href' => '/dashboard/pesan'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
    ],
    'pengurus' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi'],
        ['icon' => 'bi-calendar-event', 'label' => 'Roster Pelajaran', 'href' => '/dashboard/roster'],
        ['icon' => 'bi-calendar-week', 'label' => 'Jadwal Piket', 'href' => '/dashboard/piket'],
        ['icon' => 'bi-image', 'label' => 'Galeri', 'href' => '/dashboard/gallery'],
        ['icon' => 'bi-trophy', 'label' => 'Prestasi', 'href' => '/dashboard/prestasi'],
        ['icon' => 'bi-envelope', 'label' => 'Pesan Masuk', 'href' => '/dashboard/pesan'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
    ],
    'anggota' => [
        ['icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'href' => '/dashboard'],
        ['icon' => 'bi-megaphone', 'label' => 'Pengumuman', 'href' => '/dashboard/pengumuman/feed'],
        ['icon' => 'bi-receipt', 'label' => 'Daftar SPP', 'href' => '/dashboard/spp/me'],
        ['icon' => 'bi-clipboard-check', 'label' => 'Absensi', 'href' => '/dashboard/absensi/me'],
        ['icon' => 'bi-bell', 'label' => 'Tugas Mingguan', 'href' => '/dashboard/tugas'],
    ],
];

$links = $menuMap[$userRole] ?? $menuMap['anggota'];
?>
<aside class="sidebar bg-primary text-white">
    <div class="sidebar-header px-4 py-3 border-bottom border-primary-subtle">
        <div class="fw-bold text-uppercase small">XI PPLG</div>
        <div class="small text-white-50">Dashboard <?= e(ucwords(str_replace('_', ' ', $userRole))) ?></div>
    </div>
    <nav class="sidebar-nav flex-grow-1 overflow-auto">
        <ul class="nav flex-column py-2">
            <?php foreach ($links as $link): ?>
                <?php
                    $href = $link['href'];
                    $active = $current === $href || ($href !== '/dashboard' && str_starts_with($current, $href));
                ?>
                <li class="nav-item">
                    <a href="<?= e($href) ?>" class="nav-link d-flex align-items-center gap-2 <?= $active ? 'active' : '' ?>">
                        <i class="bi <?= e($link['icon']) ?>"></i>
                        <span><?= e($link['label']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</aside>