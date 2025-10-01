<?php $errors = flash('errors') ?? []; ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Tambah Jadwal</h2>
                <form action="/dashboard/roster/store" method="post" class="d-grid gap-3">
                    <?= csrf_input() ?>
                    <div>
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari" class="form-select <?= isset($errors['hari']) ? 'is-invalid' : '' ?>" required>
                            <?php $days = ['Senin','Selasa','Rabu','Kamis','Jumat']; ?>
                            <?php foreach ($days as $day): ?>
                                <option value="<?= e($day) ?>"><?= e($day) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['hari'])): ?><div class="invalid-feedback"><?= e($errors['hari'][0]) ?></div><?php endif; ?>
                    </div>
                    <div>
                        <label for="nama_mapel" class="form-label">Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" id="nama_mapel" class="form-control <?= isset($errors['nama_mapel']) ? 'is-invalid' : '' ?>" required>
                        <?php if (isset($errors['nama_mapel'])): ?><div class="invalid-feedback"><?= e($errors['nama_mapel'][0]) ?></div><?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Roster Pelajaran</h2>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($roster)): ?>
                                <?php foreach ($days as $day): ?>
                                    <?php $items = array_filter($roster, fn($row) => $row['hari'] === $day); ?>
                                    <?php if ($items): ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?= e($day) ?></td>
                                                <td><?= e($item['nama_mapel']) ?></td>
                                                <td class="text-end">
                                                    <form action="/dashboard/roster/delete" method="post" class="d-inline" onsubmit="return confirm('Hapus mata pelajaran ini?');">
                                                        <?= csrf_input() ?>
                                                        <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td><?= e($day) ?></td>
                                            <td colspan="2" class="text-muted">Belum ada jadwal.</td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada data roster.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
