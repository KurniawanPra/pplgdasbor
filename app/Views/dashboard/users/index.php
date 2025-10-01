<?php $roleLabels = ['superadmin' => 'Admin', 'pengurus' => 'Perangkat Kelas', 'anggota' => 'Anggota']; ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="h4 fw-semibold mb-0">Manajemen Pengguna</h2>
        <p class="text-muted mb-0">Kelola akun admin, perangkat kelas, dan anggota.</p>
    </div>
    <div class="d-flex gap-2">
        <form action="/dashboard/users" method="get" class="d-flex gap-2">
            <input type="search" name="q" class="form-control" placeholder="Cari nama atau username" value="<?= e($search) ?>">
            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        <a href="/dashboard/users/create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Pengguna</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <?php $roleLabel = $roleLabels[$user['role']] ?? ucfirst($user['role']); ?>
                        <tr>
                            <td class="fw-semibold"><?= e($user['nama_lengkap']) ?></td>
                            <td><?= e($user['username']) ?></td>
                            <td><?= e($user['email']) ?></td>
                            <td><span class="badge bg-primary-subtle text-primary text-capitalize"><?= e($roleLabel) ?></span></td>
                            <td class="text-end">
                                <a href="/dashboard/users/edit?id=<?= e($user['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="/dashboard/users/delete" method="post" class="d-inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                    <?= csrf_input() ?>
                                    <input type="hidden" name="id" value="<?= e($user['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" <?= $user['id'] == auth_id() ? 'disabled' : '' ?>><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data pengguna.</td>
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

