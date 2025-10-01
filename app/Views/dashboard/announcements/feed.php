<?php
$canManage = auth_has_role(['administrator', 'superadmin', 'wali_kelas', 'ketua', 'wakil_ketua', 'bendahara', 'sekretaris']);
$audienceLabels = [
    'semua' => 'Semua Pengguna',
    'anggota' => 'Anggota',
    'pengurus' => 'Perangkat Kelas',
];
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h4 mb-1">Pengumuman</h1>
        <p class="text-muted mb-0">Informasi terbaru untuk <?= e(ucwords(str_replace('_', ' ', auth_role() ?? 'anggota'))) ?>.</p>
    </div>
    <?php if ($canManage): ?>
        <div class="d-flex gap-2">
            <a href="/dashboard/pengumuman" class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i>Kelola Pengumuman</a>
        </div>
    <?php endif; ?>
</div>

<div class="row g-4">
    <?php if (!empty($items)): ?>
        <?php foreach ($items as $announcement): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column gap-2">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary-subtle text-primary text-uppercase small">Pengumuman</span>
                                    <span class="badge bg-secondary-subtle text-secondary text-capitalize">
                                        <?= e($audienceLabels[$announcement['audience']] ?? ucfirst($announcement['audience'])) ?>
                                    </span>
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
                        <h2 class="h5 fw-semibold mb-1"><?= e($announcement['judul']) ?></h2>
                        <p class="text-muted mb-0" style="white-space: pre-line;">
                            <?= e($announcement['isi']) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info text-center">Belum ada pengumuman yang ditayangkan.</div>
        </div>
    <?php endif; ?>
</div>

<?php if (($pagination['total_pages'] ?? 1) > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
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
<?php endif; ?>
