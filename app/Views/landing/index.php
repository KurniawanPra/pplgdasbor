<?php
$errors = flash('errors') ?? [];
$settings = $settings ?? [];
$galleryPagination = $galleryPagination ?? null;
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#home"> AW2PDN XI PPLG</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="#struktur">Struktur</a></li>
                <li class="nav-item"><a class="nav-link" href="#anggota">Anggota</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Informasi
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item nav-link ps-3" href="#gallery">Galeri</a>
                        <a class="dropdown-item nav-link ps-3" href="#prestasi">Prestasi</a>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
            </ul>
            <div class="ms-lg-3 mt-3 mt-lg-0">
                <?php if (auth_check()): ?>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                            <?= e(strtoupper(substr(auth_name() ?? 'A', 0, 1))) ?>
                        </div>
                        <div>
                            <div class="small fw-semibold mb-0"><?= e(auth_name() ?? '') ?></div>
                            <div class="small text-muted text-capitalize"><?= e(str_replace('_', ' ', auth_role() ?? '')) ?></div>
                        </div>
                        <a href="/dashboard" class="btn btn-outline-primary btn-sm">Dashboard</a>
                        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<header id="home" class="hero-section py-5 py-md-7 bg-gradient text-white">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="badge bg-light text-primary mb-3 fw-semibold">XI PPLG SMK Swasta Al Washliyah 2</span>
                <h1 class="display-5 fw-bold mb-3"><?= e($settings['motto'] ?? 'Belajar. Berkarya. Berprestasi Bersama.') ?></h1>
                <p class="lead mb-4"><?= e($settings['about'] ?? 'Kami adalah kelas XI PPLG yang berkomitmen tumbuh melalui disiplin, solidaritas, dan inovasi teknologi.') ?></p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    <a href="#informasi" class="btn btn-light btn-lg text-primary fw-semibold">Informasi</a>
                    <a href="#contact" class="btn btn-light btn-lg text-primary fw-semibold">Hubungi Kami</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="/assets/img/hero-illustration.svg" alt="Ilustrasi Kelas" class="img-fluid hero-illustration">
            </div>
        </div>
    </div>
</header>

<section id="about" class="py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <img src="/assets/img/about-class.svg" alt="Tentang Kelas" class="img-fluid rounded-4 shadow-sm">
            </div>
            <div class="col-lg-6">
                <span class="section-label">Tentang Kami</span>
                <h2 class="fw-bold mb-3">Kelas XI PPLG, Solid dan Kreatif</h2>
                <p class="text-muted"><?= e($settings['about'] ?? 'Kami terus berkolaborasi, belajar, dan berkarya untuk melahirkan solusi digital yang bermanfaat.') ?></p>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Tim perangkat kelas yang solid dan bertanggung jawab</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Anggota aktif dengan berbagai bakat dan minat</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Program kerja yang kreatif dan tepat sasaran</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Menjunjung tinggi nilai keagamaan dan kebersamaan</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="struktur" class="py-5 bg-light">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">Struktur Kelas</span>
            <h2 class="fw-bold">Susunan Perangkat Kelas</h2>
            <p class="text-muted">Struktur perangkat kelas disusun sesuai hierarki kepengurusan.</p>
        </div>
        <?php
        $strukturMap = [];
        foreach ($struktur as $item) {
            if (!isset($item['jabatan'])) {
                continue;
            }
            $strukturMap[$item['jabatan']] = $item;
        }

        $waliEntry = $strukturMap['wali_kelas'] ?? null;
        $ketuaEntry = $strukturMap['ketua'] ?? null;
        $wakilEntry = $strukturMap['wakil'] ?? $strukturMap['wakil_ketua'] ?? null;
        $bendaharaEntry = $strukturMap['bendahara'] ?? null;
        $sekretarisEntry = $strukturMap['sekretaris'] ?? null;

        $renderStrukturCard = static function (?array $entry, string $label, array $classes = []): string {
            $baseClasses = ['struktur-node', 'text-center'];
            if ($entry) {
                $baseClasses[] = 'profile-card';
            } else {
                $baseClasses[] = 'kosong';
            }
            $baseClasses = array_merge($baseClasses, $classes);
            $classAttr = implode(' ', array_map('trim', array_filter(array_unique($baseClasses))));

            if (!$entry) {
                return '<div class="' . e($classAttr) . '"><span class="struktur-label">' . e($label) . '</span><small class="text-muted">Belum ditetapkan</small></div>';
            }

            $imageUrl = $entry['foto'] ? '/uploads.php?path=' . urlencode($entry['foto']) : '/assets/img/avatar-placeholder.png';
            $profile = [
                'nama_lengkap' => $entry['nama_lengkap'] ?? '-',
                'panggilan' => $entry['panggilan'] ?? '-',
                'jabatan' => ucwords(str_replace('_', ' ', $entry['jabatan'] ?? $label)),
                'nis' => $entry['nis'] ?? '-',
                'jenis_kelamin' => $entry['jenis_kelamin'] ?? '-',
                'sosmed' => $entry['sosmed'] ?? '',
                'nomor_hp' => $entry['nomor_hp'] ?? '',
                'email' => $entry['email'] ?? '',
                'status' => $entry['status_anggota'] ? ucfirst($entry['status_anggota']) : '',
                'cita_cita' => $entry['cita_cita'] ?? '',
                'tujuan_hidup' => $entry['tujuan_hidup'] ?? '',
                'hobi' => $entry['hobi'] ?? '',
                'foto' => $entry['foto'] ?? null,
                'image_url' => $imageUrl,
            ];
            $profileJson = htmlspecialchars(json_encode($profile), ENT_QUOTES, 'UTF-8');

            ob_start();
            ?>
            <div class="<?= e($classAttr) ?>" role="button" tabindex="0" data-profile="<?= $profileJson ?>">
                <div class="struktur-avatar">
                    <img src="<?= e($imageUrl) ?>" alt="<?= e($profile['nama_lengkap']) ?>" loading="lazy">
                </div>
                <span class="struktur-role"><?= e($label) ?></span>
                <div class="struktur-name"><?= e($profile['nama_lengkap']) ?></div>
                <div class="struktur-alias"><?= e($profile['panggilan']) ?></div>
            </div>
            <?php
            return (string) ob_get_clean();
        };
        ?>
        <?php if (!empty($struktur)): ?>
            <div class="struktur-tree">
                <div class="struktur-level level-1">
                    <?= $renderStrukturCard($waliEntry, 'Wali Kelas', ['has-children']) ?>
                </div>
                <div class="struktur-level level-2">
                    <?= $renderStrukturCard($ketuaEntry, 'Ketua Kelas', ['has-children']) ?>
                </div>
                <div class="struktur-level level-3">
                    <?= $renderStrukturCard($wakilEntry, 'Wakil Ketua', ['has-children']) ?>
                </div>
                <div class="struktur-level level-branches">
                    <?= $renderStrukturCard($bendaharaEntry, 'Bendahara', ['branch']) ?>
                    <?= $renderStrukturCard($sekretarisEntry, 'Sekretaris', ['branch']) ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">Belum ada data struktur kelas.</div>
        <?php endif; ?>
    </div>
</section>

<section id="anggota" class="py-5">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">Anggota Kelas</span>
            <h2 class="fw-bold">Kenalan dengan Keluarga XI PPLG</h2>
            <p class="text-muted" data-anggota-count>Sebanyak <?= e($pagination['total'] ?? 0) ?> anggota aktif yang siap berkarya.</p>
        </div>
                <form id="anggotaSearchForm" class="anggota-search row g-2 justify-content-center">
            <div class="col-lg-6">
                <div class="input-group input-group-lg bg-white">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Cari nama atau NIS anggota" value="<?= e($anggotaSearch ?? '') ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
        <div id="anggotaList" class="row g-4 mt-4">
            <?php if (!empty($anggota)): ?>
                <?php foreach ($anggota as $member): ?>
                    <?php
                    $imageUrl = $member['foto'] ? '/uploads.php?path=' . urlencode($member['foto']) : '/assets/img/avatar-placeholder.png';
                    $profile = [
                        'id_absen' => (int) $member['id_absen'],
                        'nama_lengkap' => $member['nama_lengkap'],
                        'panggilan' => $member['panggilan'],
                        'jabatan' => ucwords(str_replace('_', ' ', $member['jabatan'] ?? '')),
                        'nis' => $member['nis'],
                        'jenis_kelamin' => $member['jenis_kelamin'],
                        'sosmed' => $member['sosmed'],
                        'nomor_hp' => $member['nomor_hp'],
                        'email' => $member['email'],
                        'status' => $member['status'] ? ucfirst($member['status']) : '',
                        'cita_cita' => $member['cita_cita'],
                        'tujuan_hidup' => $member['tujuan_hidup'],
                        'hobi' => $member['hobi'],
                        'image_url' => $imageUrl,
                    ];
                    $profileJson = htmlspecialchars(json_encode($profile), ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class="col-md-4 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 profile-card text-center" role="button" tabindex="0" data-profile="<?= $profileJson ?>">
                            <div class="text-center pt-4">
                                <div class="profile-avatar mx-auto">
                                    <img src="<?= e($imageUrl) ?>" alt="<?= e($profile['nama_lengkap']) ?>" class="img-fluid">
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <span class="badge bg-primary-subtle text-primary mb-2 text-capitalize"><?= e($profile['jabatan']) ?></span>
                                <h5 class="fw-semibold mb-1"><?= e($profile['nama_lengkap']) ?></h5>
                                <p class="text-muted mb-0"><?= e($profile['panggilan']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">Data anggota belum tersedia.</div>
                </div>
            <?php endif; ?>
        </div>
        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
            <nav class="mt-4 ajax-pagination" data-type="anggota">
                <ul class="pagination justify-content-center" id="anggotaPagination">
                    <li class="page-item <?= $pagination['has_prev'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?page=<?= $pagination['prev_page'] ?>#anggota" data-page="<?= $pagination['prev_page'] ?>" aria-label="Previous">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>#anggota" data-page="<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagination['has_next'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?page=<?= $pagination['next_page'] ?>#anggota" data-page="<?= $pagination['next_page'] ?>" aria-label="Next">&raquo;</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<section id="informasi" class="py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-4">
            <span class="section-label">Informasi Kelas</span>
            <h2 class="fw-bold">Jadwal Piket &amp; Roster Pelajaran</h2>
        </div>
        <ul class="nav nav-pills justify-content-center gap-2" id="infoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="piket-tab" data-bs-toggle="pill" data-bs-target="#piket" type="button" role="tab">Jadwal Piket</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="roster-tab" data-bs-toggle="pill" data-bs-target="#roster" type="button" role="tab">Roster Pelajaran</button>
            </li>
        </ul>
        <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="piket" role="tabpanel">
                <div class="table-responsive bg-white shadow-sm rounded-4">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hari</th>
                                <th>Anggota Piket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $piketDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; ?>
                            <?php foreach ($piketDays as $day): ?>
                                <?php $list = $piket[$day] ?? []; ?>
                                <tr>
                                    <td class="fw-semibold"><?= e($day) ?></td>
                                    <td>
                                        <?php if ($list): ?>
                                            <?php $names = array_map(fn($item) => $item['nama_lengkap'] ?? '-', $list); ?>
                                            <?= e(implode(', ', $names)) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Belum dijadwalkan</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="roster" role="tabpanel">
                <div class="table-responsive bg-white shadow-sm rounded-4">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $rosterDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; ?>
                            <?php if (!empty($roster)): ?>
                                <?php foreach ($rosterDays as $day): ?>
                                    <?php $mapel = array_filter($roster, fn($item) => $item['hari'] === $day); ?>
                                    <tr>
                                        <td class="fw-semibold"><?= e($day) ?></td>
                                        <td>
                                            <?php if ($mapel): ?>
                                                <ul class="list-unstyled mb-0">
                                                    <?php foreach ($mapel as $entry): ?>
                                                        <li><?= e($entry['nama_mapel']) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="text-muted">Belum dijadwalkan</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Belum ada data roster.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>



<section id="prestasi" class="py-5 bg-white">

    <div class="container">

        <div class="section-header text-center mb-4">

            <span class="section-label">Prestasi</span>

            <h2 class="fw-bold">Capaian Kelas Kami</h2>

            <p class="text-muted mb-0">Rekam jejak penghargaan terbaru perangkat kelas dan anggota.</p>

        </div>

        <?php if (!empty($prestasi)): ?>

            <div class="row g-4">

                <?php foreach ($prestasi as $achievement): ?>

                    <div class="col-md-6 col-lg-4">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body d-flex flex-column gap-2">

                                <div class="d-flex align-items-center justify-content-between">

                                    <span class="badge bg-primary-subtle text-primary text-uppercase small"><?= e($achievement['tingkat'] ?? 'Prestasi') ?></span>

                                    <?php if (!empty($achievement['tanggal'])): ?>

                                        <span class="text-muted small"><?= e(date('d M Y', strtotime($achievement['tanggal']))) ?></span>

                                    <?php endif; ?>

                                </div>

                                <h3 class="h6 fw-semibold mb-0"><?= e($achievement['judul'] ?? 'Prestasi') ?></h3>

                                <?php if (!empty($achievement['penyelenggara'])): ?>

                                    <div class="small text-muted"><i class="bi bi-award me-1"></i><?= e($achievement['penyelenggara']) ?></div>

                                <?php endif; ?>

                                <?php if (!empty($achievement['lokasi'])): ?>

                                    <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?= e($achievement['lokasi']) ?></div>

                                <?php endif; ?>

                                <p class="text-muted small mb-0 flex-grow-1"><?= e($achievement['deskripsi'] ?? '') ?></p>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="alert alert-info text-center">Belum ada data prestasi.</div>

        <?php endif; ?>

    </div>

</section>



<section id="gallery" class="py-5">
    <div class="container">
        <div class="section-header text-center mb-4">
            <span class="section-label">Galeri</span>
            <h2 class="fw-bold">Momen Terbaik Kami</h2>
        </div>
        <div id="galleryList" class="row g-4">
            <?php if (!empty($gallery)): ?>
                <?php foreach ($gallery as $index => $item): ?>
                    <?php $imageUrl = '/uploads.php?path=' . urlencode($item['file_path']); ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a href="#" class="gallery-card rounded-4 overflow-hidden shadow-sm position-relative d-block gallery-lightbox-trigger" data-index="<?= $index ?>" data-image="<?= e($imageUrl) ?>" data-title="<?= e($item['judul']) ?>" data-description="<?= e($item['deskripsi'] ?? '') ?>">
                            <img src="<?= e($imageUrl) ?>" alt="<?= e($item['judul']) ?>" class="img-fluid">
                            <div class="gallery-overlay">
                                <h6 class="mb-1"><?= e($item['judul']) ?></h6>
                                <?php if (!empty($item['deskripsi'])): ?>
                                    <small><?= e($item['deskripsi']) ?></small>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">Belum ada foto galeri.</div>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($galleryPagination) && ($galleryPagination['total_pages'] ?? 1) > 1): ?>
            <?php
            $currentQuery = $_GET ?? [];
            unset($currentQuery['gallery_page']);
            $buildGalleryLink = function ($page) use ($currentQuery) {
                $params = $currentQuery;
                $params['gallery_page'] = $page;
                $query = http_build_query($params);
                return ($query ? '?' . $query : '') . '#gallery';
            };
            ?>
            <nav class="mt-4 ajax-pagination" data-type="gallery">
                <ul class="pagination justify-content-center" id="galleryPagination">
                    <li class="page-item <?= $galleryPagination['has_prev'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="<?= $buildGalleryLink($galleryPagination['prev_page']) ?>" data-page="<?= $galleryPagination['prev_page'] ?>" aria-label="Previous">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $galleryPagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $galleryPagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $buildGalleryLink($i) ?>" data-page="<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $galleryPagination['has_next'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="<?= $buildGalleryLink($galleryPagination['next_page']) ?>" data-page="<?= $galleryPagination['next_page'] ?>" aria-label="Next">&raquo;</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content gallery-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" alt="" class="img-fluid mb-3" id="galleryModalImage">
                <p class="text-muted mb-0 d-none" id="galleryModalDescription"></p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="galleryPrev"><i class="bi bi-arrow-left"></i> Sebelumnya</button>
                <button type="button" class="btn btn-outline-secondary" id="galleryNext">Berikutnya <i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true" aria-labelledby="profileModalTitle">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
    <div class="modal-content profile-modal border-0 shadow-lg">
      <button type="button" class="btn-close profile-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="profile-modal-header">
        <div class="profile-modal-avatar">
          <img src="" alt="" id="profileModalImage">
        </div>
        <div class="profile-modal-summary">
          <h2 class="visually-hidden" id="profileModalTitle"></h2>
          <span class="profile-modal-subtitle">Profile</span>
          <h3 class="profile-modal-name" id="profileModalNama">-</h3>
          <p class="profile-modal-nickname" id="profileModalPanggilan">-</p>
          <div class="profile-chip-group">
            <span class="profile-chip" id="profileModalJabatan">-</span>
            <span class="profile-chip subtle text-light bg" id="profileModalStatus">-</span>
          </div>
        </div>
      </div>
      <div class="modal-body profile-modal-body">
        <div class="row g-4">
          <div class="col-lg-4">
            <div class="profile-info-card h-100">
              <span class="profile-info-heading">Data Utama</span>
              <ul class="profile-info-list">
                <li>
                  <div class="info-icon"><i class="bi bi-card-text"></i></div>
                  <div class="info-content">
                    <span class="label">NIS</span>
                    <span class="value" id="profileModalNis">-</span>
                  </div>
                </li>
                <li>
                  <div class="info-icon"><i class="bi bi-gender-ambiguous"></i></div>
                  <div class="info-content">
                    <span class="label">Jenis Kelamin</span>
                    <span class="value" id="profileModalGender">-</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="profile-info-grid">
              <div class="profile-info-card">
                <span class="profile-info-heading">Kontak</span>
                <ul class="profile-info-list">
                  <li>
                    <div class="info-icon"><i class="bi bi-telephone"></i></div>
                    <div class="info-content">
                      <span class="label">Nomor HP</span>
                      <span class="value" id="profileModalPhone">-</span>
                    </div>
                  </li>
                  <li>
                    <div class="info-icon"><i class="bi bi-envelope"></i></div>
                    <div class="info-content">
                      <span class="label">Email</span>
                      <span class="value" id="profileModalEmail">-</span>
                    </div>
                  </li>
                  <li>
                    <div class="info-icon"><i class="bi bi-share"></i></div>
                    <div class="info-content">
                      <span class="label">Media Sosial</span>
                      <span class="value" id="profileModalSosmed">-</span>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="profile-info-card">
                <span class="profile-info-heading">Personal</span>
                <ul class="profile-info-list">
                  <li>
                    <div class="info-icon"><i class="bi bi-lightbulb"></i></div>
                    <div class="info-content">
                      <span class="label">Cita-cita</span>
                      <span class="value" id="profileModalCita">-</span>
                    </div>
                  </li>
                  <li>
                    <div class="info-icon"><i class="bi bi-compass"></i></div>
                    <div class="info-content">
                      <span class="label">Tujuan Hidup</span>
                      <span class="value" id="profileModalTujuan">-</span>
                    </div>
                  </li>
                  <li>
                    <div class="info-icon"><i class="bi bi-heart"></i></div>
                    <div class="info-content">
                      <span class="label">Hobi</span>
                      <span class="value" id="profileModalHobi">-</span>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<section id="contact" class="py-5">
    <div class="container">
        <div class="section-header text-center mb-4">
            <span class="section-label">Kontak</span>
            <h2 class="fw-bold">Hubungi Kami</h2>
            <p class="text-muted">Ada pertanyaan atau ingin berkolaborasi? Kirim pesan sekarang.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="contact-card h-100 p-4 rounded-4 shadow-sm bg-primary text-white">
                    <h5 class="fw-semibold mb-3">Informasi Kontak</h5>
                    <p class="mb-3">SMK Swasta Al Washliyah 2 Perdagangan<br>Jl. Merdeka No. 45, Perdagangan</p>
                    <p class="mb-2"><i class="bi bi-envelope me-2"></i> <?= e($settings['contact_email'] ?? 'xi1pplg@sekolah.sch.id') ?></p>
                    <p class="mb-2"><i class="bi bi-phone me-2"></i> <?= e($settings['contact_phone'] ?? '081234567890') ?></p>
                    <p class="mb-4"><i class="bi bi-instagram me-2"></i> <?= e($settings['instagram'] ?? '@xi1pplg.aw2') ?></p>
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31876.696937781458!2d99.303!3d3.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3031d1508da89313%3A0xf53a0b9afd1cdccb!2sPerdagangan%2C%20Simalungun!5e0!3m2!1sid!2sid!4v1700000000000" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <form action="/contact/submit" method="post" class="row g-3">
                            <?= csrf_input() ?>
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" value="<?= e(old('nama')) ?>" required>
                                <?php if (isset($errors['nama'])): ?><div class="invalid-feedback"><?= e($errors['nama'][0]) ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= e(old('email')) ?>" required>
                                <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= e($errors['email'][0]) ?></div><?php endif; ?>
                            </div>
                            <div class="col-12">
                                <label for="pesan" class="form-label">Pesan</label>
                                <textarea name="pesan" id="pesan" rows="5" class="form-control <?= isset($errors['pesan']) ? 'is-invalid' : '' ?>" required><?= e(old('pesan')) ?></textarea>
                                <?php if (isset($errors['pesan'])): ?><div class="invalid-feedback"><?= e($errors['pesan'][0]) ?></div><?php endif; ?>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4">Kirim Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-4 bg-dark text-white">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div>? <?= date('Y') ?> XI PPLG SMK Swasta Al Washliyah 2. All rights reserved.</div>
        <div class="d-flex gap-3">
            <a href="#about" class="text-white text-decoration-none">Tentang</a>
            <a href="#informasi" class="text-white text-decoration-none">Informasi</a>
            <a href="#contact" class="text-white text-decoration-none">Kontak</a>
        </div>
    </div>
</footer>

