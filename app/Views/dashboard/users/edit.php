<?php $errors = flash('errors') ?? []; ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h5 fw-semibold mb-3">Edit Pengguna</h2>
        <form action="/dashboard/users/update" method="post" class="row g-3">
            <?= csrf_input() ?>
            <input type="hidden" name="id" value="<?= e($user['id']) ?>">
            <div class="col-md-6">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" value="<?= e(old('nama_lengkap', $user['nama_lengkap'])) ?>" required>
                <?php if (isset($errors['nama_lengkap'])): ?><div class="invalid-feedback"><?= e($errors['nama_lengkap'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" value="<?= e(old('username', $user['username'])) ?>" required>
                <?php if (isset($errors['username'])): ?><div class="invalid-feedback"><?= e($errors['username'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= e(old('email', $user['email'])) ?>" required>
                <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?= e($errors['email'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" required>
                    <option value="superadmin" <?= old('role', $user['role']) === 'superadmin' ? 'selected' : '' ?>>Admin</option>
                    <option value="pengurus" <?= old('role', $user['role']) === 'pengurus' ? 'selected' : '' ?>>Perangkat Kelas</option>
                    <option value="anggota" <?= old('role', $user['role']) === 'anggota' ? 'selected' : '' ?>>Anggota</option>
                </select>
                <?php if (isset($errors['role'])): ?><div class="invalid-feedback"><?= e($errors['role'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password'][0]) ?></div><?php endif; ?>
                <small class="text-muted">Kosongkan jika tidak mengganti.</small>
            </div>
            <div class="col-12 text-end">
                <a href="/dashboard/users" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>


