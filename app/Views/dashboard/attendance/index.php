<?php
$filters = $filters ?? [];
$anggotaOptions = $anggotaOptions ?? [];
$canManage = $canManage ?? false;
$errors = flash('errors') ?? [];
$statusLabels = [
    'hadir' => ['label' => 'Hadir', 'variant' => 'success', 'icon' => 'bi-check-circle-fill', 'description' => 'Hadir tepat waktu'],
    'izin' => ['label' => 'Izin', 'variant' => 'info', 'icon' => 'bi-envelope-open', 'description' => 'Izin resmi dari wali'],
    'sakit' => ['label' => 'Sakit', 'variant' => 'warning', 'icon' => 'bi-thermometer-half', 'description' => 'Tidak hadir karena sakit'],
    'alpa' => ['label' => 'Alpa', 'variant' => 'danger', 'icon' => 'bi-exclamation-octagon-fill', 'description' => 'Tidak hadir tanpa keterangan'],
];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1">Rekap Absensi</h1>
        <p class="text-muted mb-0">Pantau dan kelola kehadiran anggota kelas.</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <?php foreach ($statusLabels as $key => $info): ?>
                <div class="status-legend-pill status-<?= e($key) ?>">
                    <span class="icon"><i class="bi <?= e($info['icon']) ?>"></i></span>
                    <div class="legend-text">
                        <strong><?= e($info['label']) ?></strong>
                        <small class="text-muted d-block"><?= e($info['description']) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= e($filters['tanggal'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label for="anggota_id" class="form-label">Anggota</label>
                <select name="anggota_id" id="anggota_id" class="form-select">
                    <option value="">Semua anggota</option>
                    <?php foreach ($anggotaOptions as $option): ?>
                        <option value="<?= e($option['id_absen']) ?>" <?= (int) ($filters['anggota_id'] ?? 0) === (int) $option['id_absen'] ? 'selected' : '' ?>>
                            <?= e($option['nama_lengkap']) ?> (<?= e(ucwords($option['jabatan'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="/dashboard/absensi" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<?php if ($canManage): ?>
    <div class="card border-0 shadow-sm mb-4" id="form">
        <div class="card-header bg-white border-0">
            <h2 class="h5 mb-0">Catat Kehadiran</h2>
        </div>
        <div class="card-body">
            <form action="/dashboard/absensi/store" method="post" class="row g-3">
                <?= csrf_input() ?>
                <div class="col-md-4">
                    <label class="form-label" for="form_anggota">Anggota</label>
                    <select name="anggota_id" id="form_anggota" class="form-select <?= isset($errors['anggota_id']) ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Pilih anggota --</option>
                        <?php foreach ($anggotaOptions as $option): ?>
                            <option value="<?= e($option['id_absen']) ?>" <?= (int) old('anggota_id') === (int) $option['id_absen'] ? 'selected' : '' ?>>
                                <?= e($option['nama_lengkap']) ?> (<?= e(ucwords($option['jabatan'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['anggota_id'])): ?><div class="invalid-feedback"><?= e($errors['anggota_id'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-3">
                    <label for="form_tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="form_tanggal" class="form-control <?= isset($errors['tanggal']) ? 'is-invalid' : '' ?>" value="<?= e(old('tanggal', date('Y-m-d'))) ?>" required>
                    <?php if (isset($errors['tanggal'])): ?><div class="invalid-feedback"><?= e($errors['tanggal'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-3">
                    <label for="form_status" class="form-label">Status</label>
                    <?php $status = old('status', 'hadir'); ?>
                    <select name="status" id="form_status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>" required>
                        <?php foreach ($statusLabels as $value => $info): ?>
                            <option value="<?= e($value) ?>" <?= $status === $value ? 'selected' : '' ?>><?= e($info['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['status'])): ?><div class="invalid-feedback"><?= e($errors['status'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-10">
                    <label for="form_keterangan" class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" id="form_keterangan" class="form-control <?= isset($errors['keterangan']) ? 'is-invalid' : '' ?>" value="<?= e(old('keterangan')) ?>" maxlength="255" placeholder="Tambahkan catatan jika diperlukan">
                    <?php if (isset($errors['keterangan'])): ?><div class="invalid-feedback"><?= e($errors['keterangan'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Anggota</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <?php if ($canManage): ?><th class="text-end">Aksi</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $row): ?>
                            <?php $statusInfo = $statusLabels[$row['status']] ?? ['label' => ucfirst($row['status']), 'variant' => 'secondary', 'icon' => 'bi-circle']; ?>
                            <tr class="attendance-row status-<?= e($row['status']) ?>">
                                <td><?= e(date('d M Y', strtotime($row['tanggal']))) ?></td>
                                <td>
                                    <div class="fw-semibold mb-0"><?= e($row['nama_lengkap'] ?? '-') ?></div>
                                    <small class="text-muted">NIS: <?= e($row['nis'] ?? '-') ?></small>
                                </td>
                                <td>
                                    <span class="attendance-status-badge bg-<?= e($statusInfo['variant']) ?>-subtle text-<?= e($statusInfo['variant']) ?>">
                                        <i class="bi <?= e($statusInfo['icon']) ?>"></i>
                                        <?= e($statusInfo['label']) ?>
                                    </span>
                                </td>
                                <td><?= e($row['keterangan'] ?: '-') ?></td>
                                <?php if ($canManage): ?>
                                    <td class="text-end">
                                        <form action="/dashboard/absensi/delete" method="post" onsubmit="return confirm('Hapus catatan absensi ini?');">
                                            <?= csrf_input() ?>
                                            <input type="hidden" name="id" value="<?= e($row['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= $canManage ? '5' : '4' ?>" class="text-center text-muted py-4">Belum ada data absensi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
        <div class="card-footer bg-white">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?= $pagination['has_prev'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['prev_page']])) ?>#form">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>#form"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagination['has_next'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['next_page']])) ?>#form">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
