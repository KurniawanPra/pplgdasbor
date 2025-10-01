<?php
$items = $items ?? [];
$pagination = $pagination ?? [];
$statusLabels = [
    'hadir' => ['label' => 'Hadir', 'variant' => 'success'],
    'izin' => ['label' => 'Izin', 'variant' => 'info'],
    'sakit' => ['label' => 'Sakit', 'variant' => 'warning'],
    'alpa' => ['label' => 'Alpa', 'variant' => 'danger'],
];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1">Absensi Saya</h1>
        <p class="text-muted mb-0">Rekap kehadiran atas nama <?= e($anggota['nama_lengkap'] ?? '-') ?>.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $row): ?>
                            <?php $statusInfo = $statusLabels[$row['status']] ?? ['label' => ucfirst($row['status']), 'variant' => 'secondary']; ?>
                            <tr>
                                <td><?= e(date('d M Y', strtotime($row['tanggal']))) ?></td>
                                <td>
                                    <span class="badge bg-<?= e($statusInfo['variant']) ?>-subtle text-<?= e($statusInfo['variant']) ?>">
                                        <?= e($statusInfo['label']) ?>
                                    </span>
                                </td>
                                <td><?= e($row['keterangan'] ?: '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada data absensi.</td>
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
                        <a class="page-link" href="?page=<?= $pagination['prev_page'] ?>">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagination['has_next'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?page=<?= $pagination['next_page'] ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
