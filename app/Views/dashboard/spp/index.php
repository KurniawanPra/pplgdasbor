<?php
$filters = $filters ?? [];
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
$currentYear = (int) date('Y');
$yearRange = range($currentYear + 1, $currentYear - 5);
$editItem = $editItem ?? null;
$canManage = $canManage ?? false;
$anggotaOptions = $anggotaOptions ?? [];
$matrix = $matrix ?? [];
$matrixYear = $matrixYear ?? (int) date('Y');
$statusBadge = static function (?array $entry) {
    if (!$entry) {
        return '<span class="badge rounded-pill text-bg-light">-</span>';
    }

    $map = [
        'lunas' => ['label' => 'Lunas', 'class' => 'text-bg-success'],
        'cicil' => ['label' => 'Cicil', 'class' => 'text-bg-warning'],
        'belum' => ['label' => 'Belum', 'class' => 'text-bg-secondary'],
    ];

    $status = strtolower($entry['status']);
    $meta = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'text-bg-secondary'];
    $tooltip = 'Status: ' . $meta['label'];
    if (!empty($entry['jumlah'])) {
        $tooltip .= "\nNominal: Rp" . number_format((float) $entry['jumlah'], 0, ',', '.');
    }
    if (!empty($entry['tanggal_bayar'])) {
        $tooltip .= "\nTanggal bayar: " . date('d M Y', strtotime($entry['tanggal_bayar']));
    }
    if (!empty($entry['catatan'])) {
        $tooltip .= "\nCatatan: " . $entry['catatan'];
    }

    return '<span class="badge rounded-pill ' . e($meta['class']) . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="' . e($tooltip) . '">' . e(substr($meta['label'], 0, 1)) . '</span>';
};
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1">Daftar SPP</h1>
        <p class="text-muted mb-0">Pantau status pembayaran SPP seluruh anggota kelas.</p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Anggota</label>
                <select name="anggota_id" class="form-select">
                    <option value="">Semua anggota</option>
                    <?php foreach ($anggotaOptions as $anggotaOption): ?>
                        <option value="<?= e($anggotaOption['id_absen']) ?>" <?= (int) ($filters['anggota_id'] ?? 0) === (int) $anggotaOption['id_absen'] ? 'selected' : '' ?>>
                            <?= e($anggotaOption['nama_lengkap']) ?> (<?= e(ucwords($anggotaOption['jabatan'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach ($yearRange as $year): ?>
                        <option value="<?= $year ?>" <?= (int) ($filters['tahun'] ?? 0) === (int) $year ? 'selected' : '' ?>><?= $year ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach ($months as $value => $label): ?>
                        <option value="<?= $value ?>" <?= (int) ($filters['bulan'] ?? 0) === (int) $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kata kunci</label>
                <input type="text" name="q" class="form-control" placeholder="Cari nama atau NIS" value="<?= e($filters['q'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<?php if ($canManage): ?>
    <div class="card shadow-sm border-0 mb-4" id="form">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0"><?= $editItem ? 'Edit Data SPP' : 'Input SPP Baru' ?></h2>
            <?php if ($editItem): ?>
                <a href="/dashboard/spp" class="btn btn-sm btn-outline-secondary">Batal</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <form action="/dashboard/spp/store" method="post" class="row g-3">
                <?= csrf_input() ?>
                <?php if ($editItem): ?>
                    <input type="hidden" name="id" value="<?= e($editItem['id']) ?>">
                <?php endif; ?>
                <div class="col-md-4">
                    <label class="form-label">Anggota</label>
                    <select name="anggota_id" class="form-select" required>
                        <option value="">-- Pilih anggota --</option>
                        <?php foreach ($anggotaOptions as $anggotaOption): ?>
                            <option value="<?= e($anggotaOption['id_absen']) ?>" <?= (int) ($editItem['anggota_id'] ?? 0) === (int) $anggotaOption['id_absen'] ? 'selected' : '' ?>>
                                <?= e($anggotaOption['nama_lengkap']) ?> (<?= e(ucwords($anggotaOption['jabatan'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select" required>
                        <?php foreach ($months as $value => $label): ?>
                            <option value="<?= $value ?>" <?= (int) ($editItem['bulan'] ?? date('n')) === (int) $value ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="<?= e($editItem['tahun'] ?? date('Y')) ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nominal (Rp)</label>
                    <input type="number" name="jumlah" class="form-control" value="<?= e($editItem['jumlah'] ?? 0) ?>" min="0" step="5000" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <?php $statusVal = $editItem['status'] ?? 'belum'; ?>
                    <select name="status" class="form-select" required>
                        <option value="belum" <?= $statusVal === 'belum' ? 'selected' : '' ?>>Belum</option>
                        <option value="lunas" <?= $statusVal === 'lunas' ? 'selected' : '' ?>>Lunas</option>
                        <option value="cicil" <?= $statusVal === 'cicil' ? 'selected' : '' ?>>Cicil</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control" value="<?= e($editItem['tanggal_bayar'] ?? '') ?>">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="catatan" class="form-control" maxlength="255" value="<?= e($editItem['catatan'] ?? '') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <?= $editItem ? 'Perbarui' : 'Simpan' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <h2 class="h5 mb-0">Rekap SPP Bulanan <?= e($matrixYear) ?></h2>
            <p class="text-muted small mb-0">Pantau kelengkapan pembayaran setiap bulan untuk anggota yang sesuai filter.</p>
        </div>
        <div class="d-flex align-items-center gap-2 small text-muted">
            <span class="badge rounded-pill text-bg-success">L</span> <span>Lunas</span>
            <span class="badge rounded-pill text-bg-warning">C</span> <span>Cicil</span>
            <span class="badge rounded-pill text-bg-secondary">B</span> <span>Belum</span>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($matrix)): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Anggota</th>
                            <?php foreach ($months as $value => $label): ?>
                                <th class="small fw-semibold"><?= e(substr($label, 0, 3)) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matrix as $row): ?>
                            <tr>
                                <td class="text-start">
                                    <div class="fw-semibold mb-0"><?= e($row['info']['nama_lengkap']) ?></div>
                                    <small class="text-muted">NIS: <?= e($row['info']['nis'] ?? '-') ?></small>
                                </td>
                                <?php foreach ($row['months'] as $monthValue => $entry): ?>
                                    <td><?= $statusBadge($entry) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="p-4 text-center text-muted">Belum ada data anggota atau catatan SPP untuk rekap tahun ini.</div>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th class="text-end">Nominal</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Catatan</th>
                        <?php if ($canManage): ?><th class="text-end">Aksi</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $row): ?>
                            <?php $bulanLabel = $months[(int) $row['bulan']] ?? $row['bulan']; ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold mb-0"><?= e($row['nama_lengkap'] ?? '-') ?></div>
                                    <small class="text-muted">NIS: <?= e($row['nis'] ?? '-') ?></small>
                                </td>
                                <td><?= e($bulanLabel) ?></td>
                                <td><?= e($row['tahun']) ?></td>
                                <td class="text-end">Rp<?= number_format((float) $row['jumlah'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($row['status'] === 'lunas'): ?>
                                        <span class="badge bg-success-subtle text-success">Lunas</span>
                                    <?php elseif ($row['status'] === 'cicil'): ?>
                                        <span class="badge bg-warning-subtle text-warning">Cicil</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary">Belum</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row['tanggal_bayar'] ? e(date('d M Y', strtotime($row['tanggal_bayar']))) : '<span class="text-muted">-</span>' ?></td>
                                <td><?= e($row['catatan'] ?: '-') ?></td>
                                <?php if ($canManage): ?>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="/dashboard/spp?<?= http_build_query(array_merge($filters, ['edit' => $row['id']])) ?>#form" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="/dashboard/spp/delete" method="post" onsubmit="return confirm('Hapus data SPP ini?');">
                                                <?= csrf_input() ?>
                                                <input type="hidden" name="id" value="<?= e($row['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= $canManage ? '8' : '7' ?>" class="text-center py-4 text-muted">Belum ada data SPP sesuai filter.</td>
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
                        <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['prev_page']])) ?>">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagination['has_next'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $pagination['next_page']])) ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = Array.prototype.slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>