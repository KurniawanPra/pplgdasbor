<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Atur Jadwal Piket</h2>
                <form action="/dashboard/piket/store" method="post" class="d-grid gap-3">
                    <?= csrf_input() ?>
                    <div>
                        <label for="hari" class="form-label">Pilih Hari</label>
                        <select name="hari" id="hari" class="form-select" required>
                            <?php $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; ?>
                            <?php foreach ($days as $day): ?>
                                <option value="<?= e($day) ?>"><?= e($day) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="anggota_ids" class="form-label">Pilih Anggota</label>
                        <select name="anggota_ids[]" id="anggota_ids" class="form-select" multiple size="8" required>
                            <?php foreach ($anggotaOptions as $member): ?>
                                <option value="<?= e($member['id_absen']) ?>"><?= e($member['nama_lengkap']) ?> (<?= e($member['jabatan']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Tahan tombol CTRL (atau Cmd di Mac) untuk memilih lebih dari satu anggota.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5 fw-semibold mb-3">Daftar Piket</h2>
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Anggota Piket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($days as $day): ?>
                                <?php $list = $piket[$day] ?? []; ?>
                                <tr>
                                    <td class="fw-semibold"><?= e($day) ?></td>
                                    <td>
                                        <?php if ($list): ?>
                                            <ul class="list-unstyled mb-0 d-flex flex-wrap gap-2">
                                                <?php foreach ($list as $item): ?>
                                                    <li class="badge bg-primary-subtle text-primary d-inline-flex align-items-center gap-2">
                                                        <?= e($item['nama_lengkap'] ?? '-') ?>
                                                        <form action="/dashboard/piket/delete" method="post" onsubmit="return confirm('Hapus anggota dari jadwal ini?');">
                                                            <?= csrf_input() ?>
                                                            <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-x-circle"></i></button>
                                                        </form>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <span class="text-muted">Belum dijadwalkan.</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
