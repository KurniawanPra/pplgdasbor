<?php
$errors = flash('errors') ?? [];
$canEdit = $canEdit ?? false;
$editItem = $editItem ?? null;
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1">Tugas Mingguan</h1>
        <p class="text-muted mb-0">Pengingat kegiatan dan tugas untuk seluruh anggota kelas.</p>
    </div>
</div>

<?php if ($canEdit): ?>
    <div class="card border-0 shadow-sm mb-4" id="form">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0"><?= $editItem ? 'Edit Tugas' : 'Buat Tugas Baru' ?></h2>
            <?php if ($editItem): ?>
                <a href="/dashboard/tugas" class="btn btn-sm btn-outline-secondary">Batal</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <form action="/dashboard/tugas/store" method="post" class="row g-3">
                <?= csrf_input() ?>
                <?php if ($editItem): ?>
                    <input type="hidden" name="id" value="<?= e($editItem['id']) ?>">
                <?php endif; ?>
                <div class="col-md-6">
                    <label class="form-label" for="judul">Judul Tugas</label>
                    <input type="text" name="judul" id="judul" class="form-control <?= isset($errors['judul']) ? 'is-invalid' : '' ?>" value="<?= e(old('judul', $editItem['judul'] ?? '')) ?>" required>
                    <?php if (isset($errors['judul'])): ?><div class="invalid-feedback"><?= e($errors['judul'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="deadline">Tenggat</label>
                    <input type="date" name="deadline" id="deadline" class="form-control <?= isset($errors['deadline']) ? 'is-invalid' : '' ?>" value="<?= e(old('deadline', $editItem['deadline'] ?? '')) ?>">
                    <?php if (isset($errors['deadline'])): ?><div class="invalid-feedback"><?= e($errors['deadline'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="is_completed" name="is_completed" <?= old('is_completed', $editItem['is_completed'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_completed">Tandai selesai</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control <?= isset($errors['deskripsi']) ? 'is-invalid' : '' ?>" placeholder="Detail tugas atau catatan tambahan"><?= e(old('deskripsi', $editItem['deskripsi'] ?? '')) ?></textarea>
                    <?php if (isset($errors['deskripsi'])): ?><div class="invalid-feedback"><?= e($errors['deskripsi'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success"><?= $editItem ? 'Perbarui Tugas' : 'Simpan Tugas' ?></button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <?php if (!empty($items)): ?>
            <div class="list-group list-group-flush">
                <?php foreach ($items as $task): ?>
                    <?php
                        $isCompleted = (int) ($task['is_completed'] ?? 0) === 1;
                        $deadline = $task['deadline'] ? date('d M Y', strtotime($task['deadline'])) : null;
                        $deadlineBadge = '';
                        if ($deadline) {
                            $deadlineBadge = '<span class="badge bg-info-subtle text-info ms-2"><i class="bi bi-calendar-event me-1"></i>' . e($deadline) . '</span>';
                        }
                    ?>
                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge <?= $isCompleted ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' ?>">
                                        <?= $isCompleted ? 'Selesai' : 'Belum Selesai' ?>
                                    </span>
                                    <?= $deadlineBadge ?>
                                </div>
                                <h2 class="h6 fw-semibold mb-1"><?= e($task['judul']) ?></h2>
                                <?php if (!empty($task['deskripsi'])): ?>
                                    <p class="text-muted small mb-0" style="white-space: pre-line;"><?= e($task['deskripsi']) ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ($canEdit): ?>
                                <div class="d-flex flex-column gap-2">
                                    <a href="/dashboard/tugas?edit=<?= e($task['id']) ?>#form" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="/dashboard/tugas/delete" method="post" onsubmit="return confirm('Hapus tugas ini?');">
                                        <?= csrf_input() ?>
                                        <input type="hidden" name="id" value="<?= e($task['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4">Belum ada tugas mingguan yang tercatat.</div>
        <?php endif; ?>
    </div>
    <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
        <div class="card-footer bg-white">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?= $pagination['has_prev'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?page=<?= $pagination['prev_page'] ?>#form">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>#form"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagination['has_next'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?page=<?= $pagination['next_page'] ?>#form">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
