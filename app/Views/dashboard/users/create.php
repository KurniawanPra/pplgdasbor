<?php
$errors = flash('errors') ?? [];
$roleOptions = [
    'administrator' => 'Administrator',
    'superadmin' => 'Admin',
    'wali_kelas' => 'Wali Kelas',
    'ketua' => 'Ketua Kelas',
    'wakil_ketua' => 'Wakil Ketua',
    'bendahara' => 'Bendahara',
    'sekretaris' => 'Sekretaris',
    'pengurus' => 'Perangkat Kelas',
    'anggota' => 'Anggota',
];
?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h5 fw-semibold mb-3">Tambah Pengguna</h2>
        <form action="/dashboard/users/store" method="post" class="row g-3">
            <?= csrf_input() ?>
            <div class="col-md-6">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" value="<?= e(old('nama_lengkap')) ?>" required>
                <?php if (isset($errors['nama_lengkap'])): ?><div class="invalid-feedback"><?= e($errors['nama_lengkap'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" value="<?= e(old('username')) ?>" required>
                <?php if (isset($errors['username'])): ?><div class="invalid-feedback"><?= e($errors['username'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= e(old('email')) ?>" required>
                <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= e($errors['email'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" required>
                    <?php foreach ($roleOptions as $value => $label): ?>
                        <option value="<?= e($value) ?>" <?= old('role', 'pengurus') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['role'])): ?><div class="invalid-feedback"><?= e($errors['role'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required>
                <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password'][0]) ?></div><?php endif; ?>
                <small class="text-muted">Minimal 8 karakter kombinasi huruf dan angka.</small>
            </div>
            <div class="col-12 text-end">
                <a href="/dashboard/users" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>


