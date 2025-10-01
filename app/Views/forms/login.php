<?php $errors = flash('errors') ?? []; ?>
<form action="/login" method="post" class="d-grid gap-3">
    <?= csrf_input() ?>
    <div>
        <label for="email_or_username" class="form-label">Email atau Username</label>
        <input type="text" name="email_or_username" id="email_or_username" class="form-control <?= isset($errors['email_or_username']) ? 'is-invalid' : '' ?>" value="<?= e(old('email_or_username')) ?>" required autocomplete="username">
        <?php if (isset($errors['email_or_username'])): ?><div class="invalid-feedback"><?= e($errors['email_or_username'][0]) ?></div><?php endif; ?>
    </div>
    <div>
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required autocomplete="current-password">
        <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password'][0]) ?></div><?php endif; ?>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
        <label class="form-check-label" for="remember">Ingat saya</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Masuk</button>
    <div class="text-center">
        <small class="text-muted">Admin? <a href="/admin/login">Login di sini</a></small>
    </div>
</form>

