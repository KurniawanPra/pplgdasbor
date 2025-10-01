<?php $errors = flash('errors') ?? []; ?>
<form action="/admin/login" method="post" class="d-grid gap-3">
    <?= csrf_input() ?>
    <div>
        <label for="username" class="form-label">Username Admin</label>
        <input type="text" name="username" id="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" value="<?= e(old('username')) ?>" required autocomplete="username">
        <?php if (isset($errors['username'])): ?><div class="invalid-feedback"><?= e($errors['username'][0]) ?></div><?php endif; ?>
    </div>
    <div>
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required autocomplete="current-password">
        <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password'][0]) ?></div><?php endif; ?>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
        <label class="form-check-label" for="remember">Tetap masuk</label>
    </div>
    <button type="submit" class="btn btn-warning w-100">Masuk sebagai Admin</button>
    <div class="text-center">
        <small class="text-white-50">Perangkat kelas atau anggota? <a href="/login">Login di sini</a></small>
    </div>
</form>

