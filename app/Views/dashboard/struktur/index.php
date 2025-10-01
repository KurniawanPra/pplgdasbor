<?php $errors = flash('errors') ?? []; ?>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Atur Struktur Kelas</h2>
                <form action="/dashboard/struktur/store" method="post" class="row g-3">
                    <?= csrf_input() ?>
                    <div class="col-12">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select name="jabatan" id="jabatan" class="form-select <?= isset($errors['jabatan']) ? 'is-invalid' : '' ?>" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <?php $jabatanOptions = ['wali_kelas' => 'Wali Kelas', 'ketua' => 'Ketua', 'wakil' => 'Wakil', 'sekretaris' => 'Sekretaris', 'bendahara' => 'Bendahara']; ?>
                            <?php foreach ($jabatanOptions as $value => $label): ?>
                                <option value="<?= e($value) ?>" <?= old('jabatan') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['jabatan'])): ?><div class="invalid-feedback"><?= e($errors['jabatan'][0]) ?></div><?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label for="anggota_id" class="form-label">Pilih Anggota</label>
                        <select name="anggota_id" id="anggota_id" class="form-select <?= isset($errors['anggota_id']) ? 'is-invalid' : '' ?>" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($anggotaOptions as $member): ?>
                                <option value="<?= e($member['id_absen']) ?>" <?= old('anggota_id') == $member['id_absen'] ? 'selected' : '' ?>><?= e($member['nama_lengkap']) ?> (<?= e($member['jabatan']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['anggota_id'])): ?><div class="invalid-feedback"><?= e($errors['anggota_id'][0]) ?></div><?php endif; ?>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Daftar Struktur</h2>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Jabatan</th>
                                <th>Nama</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($struktur)): ?>
                                <?php foreach ($struktur as $item): ?>
                                    <tr>
                                        <td class="text-capitalize"><?= e(str_replace('_', ' ', $item['jabatan'])) ?></td>
                                        <td><?= e($item['nama_lengkap'] ?? '-') ?></td>
                                        <td class="text-end">
                                            <form action="/dashboard/struktur/delete" method="post" onsubmit="return confirm('Hapus jabatan ini?');" class="d-inline">
                                                <?= csrf_input() ?>
                                                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada struktur ditetapkan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
