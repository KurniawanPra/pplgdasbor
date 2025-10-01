<div class="row g-4">
    <?php $statIcons = [
        'total_anggota' => ['label' => 'Total Anggota', 'icon' => 'bi-people-fill', 'variant' => 'primary'],
        'anggota_aktif' => ['label' => 'Anggota Aktif', 'icon' => 'bi-person-check', 'variant' => 'success'],
        'total_pengurus' => ['label' => 'Akun Perangkat Kelas', 'icon' => 'bi-person-gear', 'variant' => 'info'],
        'total_gallery' => ['label' => 'Foto Galeri', 'icon' => 'bi-images', 'variant' => 'warning'],
        'total_prestasi' => ['label' => 'Prestasi', 'icon' => 'bi-trophy', 'variant' => 'secondary'],
        'pesan_masuk' => ['label' => 'Pesan Masuk', 'icon' => 'bi-envelope', 'variant' => 'danger'],
    ]; ?>
    <?php foreach ($statIcons as $key => $config): ?>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon text-<?= e($config['variant']) ?> bg-<?= e($config['variant']) ?>-subtle">
                        <i class="bi <?= e($config['icon']) ?>"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-1"><?= e($config['label']) ?></div>
                        <div class="h4 fw-bold mb-0"><?= e($stats[$key] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5 class="fw-semibold">Selamat datang, <?= e(auth_name() ?? 'Admin') ?>!</h5>
        <p class="text-muted mb-0">Kelola data kelas XI PPLG melalui menu di sidebar. Pastikan selalu memperbarui data agar informasi tetap akurat dan up to date.</p>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0 text-uppercase">Pengumuman Terbaru</h2>
                <a href="/dashboard/pengumuman" class="btn btn-sm btn-outline-primary">Kelola</a>
            </div>
            <div class="card-body">
                <?php if (!empty($announcements)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h3 class="h6 fw-semibold mb-1"><?= e($announcement['judul']) ?></h3>
                                        <p class="text-muted small mb-0" style="white-space: pre-line;">
                                            <?= e(mb_strimwidth($announcement['isi'], 0, 140, '...')) ?>
                                        </p>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php if (!empty($announcement['published_at'])): ?>
                                            <?= e(date('d M Y H:i', strtotime($announcement['published_at']))) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">Belum ada pengumuman terkini.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0 text-uppercase">Tugas Mingguan Aktif</h2>
                <a href="/dashboard/tugas" class="btn btn-sm btn-outline-primary">Kelola</a>
            </div>
            <div class="card-body">
                <?php if (!empty($tasks)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($tasks as $task): ?>
                            <?php $deadline = $task['deadline'] ? date('d M Y', strtotime($task['deadline'])) : null; ?>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h3 class="h6 fw-semibold mb-1"><?= e($task['judul']) ?></h3>
                                        <?php if (!empty($task['deskripsi'])): ?>
                                            <p class="text-muted small mb-0" style="white-space: pre-line;">
                                                <?= e(mb_strimwidth($task['deskripsi'], 0, 140, '...')) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-end small">
                                        <?php if ($deadline): ?>
                                            <div class="badge bg-info-subtle text-info"><i class="bi bi-calendar-event me-1"></i><?= e($deadline) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($task['is_completed'])): ?>
                                            <div class="badge bg-success-subtle text-success mt-1">Selesai</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-center text-muted py-4">Belum ada tugas aktif.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


