<?php
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
$filters = $filters ?? [];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1">SPP Saya</h1>
        <p class="text-muted mb-0">Riwayat pembayaran SPP atas nama <?= e($anggota['nama_lengkap'] ?? '-') ?>.</p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="<?= e($filters['tahun'] ?? date('Y')) ?>" min="2020" max="2100">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th class="text-end">Nominal</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $row): ?>
                            <?php $bulanLabel = $months[(int) $row['bulan']] ?? $row['bulan']; ?>
                            <tr>
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
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data SPP untuk periode ini.</td>
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