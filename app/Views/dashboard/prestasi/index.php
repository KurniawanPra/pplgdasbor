<?php $roleLabels = ['superadmin' => 'Admin', 'pengurus' => 'Perangkat Kelas', 'anggota' => 'Anggota']; ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-semibold mb-0">Daftar Prestasi</h2>
        <p class="text-muted mb-0">Catat capaian kelas agar tampil di halaman utama.</p>
    </div>
    <div class="d-flex gap-2">
        <form action="/dashboard/prestasi" method="get" class="d-flex gap-2">
            <input type="search" name="q" class="form-control" placeholder="Cari judul atau penyelenggara" value="<?= e($search) ?>">
            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="/dashboard/prestasi/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Prestasi</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Tingkat</th>
                    <th>Penyelenggara</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="fw-semibold text-wrap" style="max-width: 260px;">
                                <div><?= e($item['judul']) ?></div>
                                <?php if (!empty($item['deskripsi'])): ?>
                                    <small class="text-muted d-block text-truncate" style="max-width: 260px;"><?= e($item['deskripsi']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $item['tanggal'] ? e(date('d M Y', strtotime($item['tanggal']))) : '<span class="text-muted">-</span>' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $item['tingkat'] ? e($item['tingkat']) : '<span class="text-muted">-</span>' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $item['penyelenggara'] ? e($item['penyelenggara']) : '<span class="text-muted">-</span>' ?>
                            </td>
                            <td class="text-end">
                                <a href="/dashboard/prestasi/edit?id=<?= e($item['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="/dashboard/prestasi/delete" method="post" class="d-inline" onsubmit="return confirm('Hapus prestasi ini?');">
                                    <?= csrf_input() ?>
                                    <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data prestasi.</td>
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

