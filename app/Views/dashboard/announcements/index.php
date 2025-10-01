<?php
$errors = flash('errors') ?? [];
$editItem = $editItem ?? null;
$audienceOptions = [
    'semua' => 'Semua Pengguna',
    'anggota' => 'Anggota',
    'pengurus' => 'Perangkat Kelas',
];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1">Kelola Pengumuman</h1>
        <p class="text-muted mb-0">Bagikan informasi penting untuk anggota dan perangkat kelas.</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" id="form">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0"><?= $editItem ? 'Edit Pengumuman' : 'Buat Pengumuman' ?></h2>
        <?php if ($editItem): ?>
            <a href="/dashboard/pengumuman" class="btn btn-sm btn-outline-secondary">Batal</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <form action="/dashboard/pengumuman/store" method="post" class="row g-3">
            <?= csrf_input() ?>
            <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= e($editItem['id']) ?>">
            <?php endif; ?>
            <div class="col-md-6">
                <label for="judul" class="form-label">Judul</label>
                <input type="text" name="judul" id="judul" class="form-control <?= isset($errors['judul']) ? 'is-invalid' : '' ?>" value="<?= e(old('judul', $editItem['judul'] ?? '')) ?>" required>
                <?php if (isset($errors['judul'])): ?><div class="invalid-feedback"><?= e($errors['judul'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-3">
                <label for="audience" class="form-label">Audiens</label>
                <?php $audience = old('audience', $editItem['audience'] ?? 'semua'); ?>
                <select name="audience" id="audience" class="form-select <?= isset($errors['audience']) ? 'is-invalid' : '' ?>" required>
                    <?php foreach ($audienceOptions as $value => $label): ?>
                        <option value="<?= e($value) ?>" <?= $audience === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['audience'])): ?><div class="invalid-feedback"><?= e($errors['audience'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-3">
                <label for="published_at" class="form-label">Waktu Tayang</label>
                <input type="datetime-local" name="published_at" id="published_at" class="form-control <?= isset($errors['published_at']) ? 'is-invalid' : '' ?>" value="<?= e(old('published_at', isset($editItem['published_at']) ? date('Y-m-d\TH:i', strtotime($editItem['published_at'])) : '')) ?>">
                <?php if (isset($errors['published_at'])): ?><div class="invalid-feedback"><?= e($errors['published_at'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-12">
                <label for="isi" class="form-label">Isi Pengumuman</label>
                <textarea name="isi" id="isi" rows="5" class="form-control <?= isset($errors['isi']) ? 'is-invalid' : '' ?>" required><?= e(old('isi', $editItem['isi'] ?? '')) ?></textarea>
                <?php if (isset($errors['isi'])): ?><div class="invalid-feedback"><?= e($errors['isi'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-success"><?= $editItem ? 'Perbarui Pengumuman' : 'Publikasikan' ?></button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Audiens</th>
                        <th>Waktu Terbit</th>
                        <th>Isi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="fw-semibold"><?= e($item['judul']) ?></td>
                                <td>
                                    <?php $badgeMap = ['semua' => 'secondary', 'anggota' => 'primary', 'pengurus' => 'info']; ?>
                                    <span class="badge bg-<?= e($badgeMap[$item['audience']] ?? 'secondary') ?>-subtle text-<?= e($badgeMap[$item['audience']] ?? 'secondary') ?> text-capitalize">
                                        <?= e($audienceOptions[$item['audience']] ?? ucfirst($item['audience'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($item['published_at'])): ?>
                                        <?= e(date('d M Y H:i', strtotime($item['published_at']))) ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="text-muted small mb-0 lh-sm">
                                        <?= nl2br(e(mb_strimwidth($item['isi'], 0, 120, '...'))) ?>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="/dashboard/pengumuman?edit=<?= e($item['id']) ?>#form" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="/dashboard/pengumuman/delete" method="post" onsubmit="return confirm('Hapus pengumuman ini?');">
                                            <?= csrf_input() ?>
                                            <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada pengumuman.</td>
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
