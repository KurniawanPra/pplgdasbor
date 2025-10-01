<?php
$spp = $spp ?? [];
$attendance = $attendance ?? [];
$announcements = $announcements ?? [];
$tasks = $tasks ?? [];
$months = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
];
$statusBadges = [
    'lunas' => ['label' => 'Lunas', 'variant' => 'success'],
    'cicil' => ['label' => 'Cicil', 'variant' => 'warning'],
    'belum' => ['label' => 'Belum', 'variant' => 'secondary'],
];
$attendanceStatus = [
    'hadir' => ['label' => 'Hadir', 'variant' => 'success'],
    'izin' => ['label' => 'Izin', 'variant' => 'info'],
    'sakit' => ['label' => 'Sakit', 'variant' => 'warning'],
    'alpa' => ['label' => 'Alpa', 'variant' => 'danger'],
];
$canManageTasks = auth_has_role(['administrator','superadmin','wali_kelas','ketua','wakil_ketua','bendahara','sekretaris','pengurus']);
?>
<div class="mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center gap-4">
            <div class="flex-grow-1">
                <h1 class="h4 mb-2">Selamat datang kembali, <?= e($anggota['panggilan'] ?? auth_name() ?? 'Anggota') ?>!</h1>
                <p class="text-muted mb-0">Pantau informasi terbaru kelas, status SPP, serta catatan absensi Anda di halaman ini.</p>
            </div>
            <div class="bg-primary-subtle text-primary rounded-4 px-4 py-3 text-center">
                <div class="fw-semibold text-uppercase small">NIS</div>
                <div class="fs-5 fw-bold mb-0"><?= e($anggota['nis'] ?? '-') ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Tugas Mingguan</h2>
                <span class="badge bg-primary-subtle text-primary">Pengingat</span>
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
                                            <p class="text-muted small mb-0" style="white-space: pre-line;"><?= e($task['deskripsi']) ?></p>
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
                    <div class="text-center text-muted py-4">Belum ada tugas aktif. Tetap semangat!</div>
                <?php endif; ?>
            </div>
            <?php if ($canManageTasks): ?>
                <div class="card-footer bg-white text-end">
                    <a href="/dashboard/tugas" class="btn btn-sm btn-outline-primary">Kelola Tugas</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h2 class="h5 mb-0">Status SPP Terbaru</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Status</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($spp)): ?>
                                <?php foreach ($spp as $row): ?>
                                    <?php $badge = $statusBadges[$row['status']] ?? $statusBadges['belum']; ?>
                                    <tr>
                                        <td><?= e($months[(int) $row['bulan']] ?? $row['bulan']) ?></td>
                                        <td><?= e($row['tahun']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= e($badge['variant']) ?>-subtle text-<?= e($badge['variant']) ?>"><?= e($badge['label']) ?></span>
                                        </td>
                                        <td>Rp<?= number_format((float) $row['jumlah'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada catatan SPP.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <a href="/dashboard/spp/me" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h2 class="h5 mb-0">Riwayat Absensi</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($attendance)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($attendance as $row): ?>
                            <?php $info = $attendanceStatus[$row['status']] ?? ['label' => ucfirst($row['status']), 'variant' => 'secondary']; ?>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold"><?= e(date('d M Y', strtotime($row['tanggal']))) ?></div>
                                        <?php if (!empty($row['keterangan'])): ?>
                                            <small class="text-muted">Catatan: <?= e($row['keterangan']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge bg-<?= e($info['variant']) ?>-subtle text-<?= e($info['variant']) ?>"><?= e($info['label']) ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-center text-muted py-4">Belum ada catatan absensi.</div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white text-end">
                <a href="/dashboard/absensi/me" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Pengumuman Terbaru</h2>
                <a href="/dashboard/pengumuman/feed" class="btn btn-sm btn-outline-primary">Semua</a>
            </div>
            <div class="card-body">
                <?php if (!empty($announcements)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h3 class="h6 fw-semibold mb-1"><?= e($announcement['judul']) ?></h3>
                                        <p class="text-muted small mb-0" style="white-space: pre-line;"><?= e(mb_strimwidth($announcement['isi'], 0, 160, '...')) ?></p>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        <?php if (!empty($announcement['published_at'])): ?>
                                            <?= e(date('d M H:i', strtotime($announcement['published_at']))) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">Belum ada pengumuman terbaru.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
