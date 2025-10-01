<?php $errors = flash('errors') ?? []; ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Upload Foto</h2>
                <form action="/dashboard/gallery/upload" method="post" enctype="multipart/form-data" class="d-grid gap-3">
                    <?= csrf_input() ?>
                    <div>
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control <?= isset($errors['judul']) ? 'is-invalid' : '' ?>" value="<?= e(old('judul')) ?>" required>
                        <?php if (isset($errors['judul'])): ?><div class="invalid-feedback"><?= e($errors['judul'][0]) ?></div><?php endif; ?>
                    </div>
                    <div>
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control <?= isset($errors['deskripsi']) ? 'is-invalid' : '' ?>" placeholder="Tambahkan deskripsi singkat (opsional)"><?= e(old('deskripsi')) ?></textarea>
                        <?php if (isset($errors['deskripsi'])): ?><div class="invalid-feedback"><?= e($errors['deskripsi'][0]) ?></div><?php endif; ?>
                    </div>
                    <div>
                        <label for="file" class="form-label">File Gambar</label>
                        <input type="file" name="file" id="file" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                        <small class="text-muted">Ukuran maks 1 MB.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Unggah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Daftar Galeri</h2>
                <div class="row g-3">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <div class="col-sm-6">
                                <div class="gallery-item card border-0 shadow-sm h-100">
                                    <img src="<?= '/uploads.php?path=' . urlencode($item['file_path']) ?>" class="card-img-top" alt="<?= e($item['judul']) ?>">
                                    <div class="card-body">
                                        <h6 class="fw-semibold mb-1"><?= e($item['judul']) ?></h6>
                                        <?php if (!empty($item['deskripsi'])): ?>
                                            <p class="text-muted small mb-2"><?= e($item['deskripsi']) ?></p>
                                        <?php endif; ?>
                                        <small class="text-muted">Diunggah: <?= e(date('d M Y', strtotime($item['created_at']))) ?></small>
                                    </div>
                                    <div class="card-footer bg-white text-end">
                                        <form action="/dashboard/gallery/delete" method="post" onsubmit="return confirm('Hapus foto ini?');" class="d-inline">
                                            <?= csrf_input() ?>
                                            <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">Belum ada foto galeri.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
                <div class="card-footer bg-white">
                    <nav>
                        <ul class="pagination justify-content-end mb-0">
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
    </div>
</div>
