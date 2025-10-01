<?php $errors = flash('errors') ?? []; ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h5 fw-semibold mb-3">Tambah Anggota</h2>
        <form action="/dashboard/anggota/store" method="post" enctype="multipart/form-data" class="row g-3">
            <?= csrf_input() ?>
            <div class="col-md-4">
                <label for="id_absen" class="form-label">Nomor Absen</label>
                <input type="number" name="id_absen" id="id_absen" class="form-control <?= isset($errors['id_absen']) ? 'is-invalid' : '' ?>" value="<?= e(old('id_absen')) ?>" required>
                <?php if (isset($errors['id_absen'])): ?><div class="invalid-feedback"><?= e($errors['id_absen'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label for="nis" class="form-label">NIS</label>
                <input type="text" name="nis" id="nis" class="form-control <?= isset($errors['nis']) ? 'is-invalid' : '' ?>" value="<?= e(old('nis')) ?>" required>
                <?php if (isset($errors['nis'])): ?><div class="invalid-feedback"><?= e($errors['nis'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label for="user_id" class="form-label">Akun Pengguna (Opsional)</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">-- Pilih Akun --</option>
                    <?php foreach ($userOptions as $user): ?>
                        <option value="<?= e($user['id']) ?>" <?= old('user_id') == $user['id'] ? 'selected' : '' ?>><?= e($user['nama_lengkap']) ?> (<?= e($user['username']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" value="<?= e(old('nama_lengkap')) ?>" required>
                <?php if (isset($errors['nama_lengkap'])): ?><div class="invalid-feedback"><?= e($errors['nama_lengkap'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="panggilan" class="form-label">Nama Panggilan</label>
                <input type="text" name="panggilan" id="panggilan" class="form-control <?= isset($errors['panggilan']) ? 'is-invalid' : '' ?>" value="<?= e(old('panggilan')) ?>" required>
                <?php if (isset($errors['panggilan'])): ?><div class="invalid-feedback"><?= e($errors['panggilan'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-select <?= isset($errors['jenis_kelamin']) ? 'is-invalid' : '' ?>" required>
                    <option value="">-- Pilih --</option>
                    <option value="L" <?= old('jenis_kelamin') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= old('jenis_kelamin') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>
                <?php if (isset($errors['jenis_kelamin'])): ?><div class="invalid-feedback"><?= e($errors['jenis_kelamin'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label for="jabatan" class="form-label">Jabatan</label>
                <select name="jabatan" id="jabatan" class="form-select <?= isset($errors['jabatan']) ? 'is-invalid' : '' ?>" required>
                    <?php $jabatanList = ['anggota' => 'Anggota', 'ketua' => 'Ketua', 'wakil' => 'Wakil', 'sekretaris' => 'Sekretaris', 'bendahara' => 'Bendahara']; ?>
                    <?php foreach ($jabatanList as $value => $label): ?>
                        <option value="<?= e($value) ?>" <?= old('jabatan', 'anggota') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['jabatan'])): ?><div class="invalid-feedback"><?= e($errors['jabatan'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>" required>
                    <?php $statusList = ['aktif' => 'Aktif', 'alumni' => 'Alumni', 'nonaktif' => 'Nonaktif']; ?>
                    <?php foreach ($statusList as $value => $label): ?>
                        <option value="<?= e($value) ?>" <?= old('status', 'aktif') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['status'])): ?><div class="invalid-feedback"><?= e($errors['status'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="sosmed" class="form-label">Akun Media Sosial</label>
                <input type="text" name="sosmed" id="sosmed" class="form-control" value="<?= e(old('sosmed')) ?>" placeholder="@xi1pplg">
            </div>
            <div class="col-md-6">
                <label for="nomor_hp" class="form-label">Nomor HP</label>
                <input type="text" name="nomor_hp" id="nomor_hp" class="form-control <?= isset($errors['nomor_hp']) ? 'is-invalid' : '' ?>" value="<?= e(old('nomor_hp')) ?>" placeholder="08xxxxxxxxxx">
                <?php if (isset($errors['nomor_hp'])): ?><div class="invalid-feedback"><?= e($errors['nomor_hp'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= e(old('email')) ?>" placeholder="nama@email.com">
                <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= e($errors['email'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="hobi" class="form-label">Hobi</label>
                <input type="text" name="hobi" id="hobi" class="form-control" value="<?= e(old('hobi')) ?>">
            </div>
            <div class="col-md-6">
                <label for="cita_cita" class="form-label">Cita-cita</label>
                <input type="text" name="cita_cita" id="cita_cita" class="form-control" value="<?= e(old('cita_cita')) ?>">
            </div>
            <div class="col-md-6">
                <label for="tujuan_hidup" class="form-label">Tujuan Hidup</label>
                <input type="text" name="tujuan_hidup" id="tujuan_hidup" class="form-control" value="<?= e(old('tujuan_hidup')) ?>">
            </div>
            <div class="col-md-6">
                <label for="foto" class="form-label">Foto Profil</label>
                <input type="file" name="foto" id="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small class="text-muted">Format JPG/PNG/WEBP, maksimal 1 MB.</small>
            </div>
            <div class="col-12 d-flex gap-2 justify-content-end">
                <a href="/dashboard/anggota" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
