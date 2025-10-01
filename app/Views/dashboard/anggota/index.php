<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-semibold mb-0">Data Anggota</h2>
        <p class="text-muted mb-0">Kelola data anggota kelas XI.</p>
    </div>
    <div class="d-flex gap-2">
        <form action="/dashboard/anggota" method="get" class="d-flex gap-2">
            <input type="search" name="q" class="form-control" placeholder="Cari nama atau NIS" value="<?= e($search) ?>">
            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="/dashboard/anggota/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Anggota</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nama Lengkap</th>
                    <th>Panggilan</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= e($item['id_absen']) ?></td>
                            <td>
                                <div class="table-avatar">
                                    <img src="<?= $item['foto'] ? '/uploads.php?path=' . urlencode($item['foto']) : '/assets/img/avatar-placeholder.png' ?>" alt="<?= e($item['nama_lengkap']) ?>" class="rounded-circle">
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold d-block"><?= e($item['nama_lengkap']) ?></span>
                                <small class="text-muted">NIS: <?= e($item['nis']) ?></small>
                            </td>
                            <td><?= e($item['panggilan']) ?></td>
                            <td><span class="badge bg-primary-subtle text-primary text-capitalize"><?= e($item['jabatan']) ?></span></td>
                            <td><span class="badge bg-secondary-subtle text-secondary text-capitalize"><?= e($item['status']) ?></span></td>
                            <td class="text-end">
                                <a href="/dashboard/anggota/edit?id=<?= e($item['id_absen']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="/dashboard/anggota/delete" method="post" class="d-inline" onsubmit="return confirm('Hapus data anggota ini?');">
                                    <?= csrf_input() ?>
                                    <input type="hidden" name="id" value="<?= e($item['id_absen']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data anggota.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
        <div class="card-footer bg-white">
            <nav>
                <ul class="pagination justify-content-end mb-0">
                    <li class="page-item <?= $pagination['has_prev'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $pagination['prev_page'] ?>">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagination['has_next'] ? '' : 'disabled' ?>">
                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $pagination['next_page'] ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
