<?php
$errors = flash('errors') ?? [];
$activeTab = $activeTab ?? 'anggota';
?>
<div class="login-tabs">
    <ul class="nav nav-pills nav-fill mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= $activeTab === 'anggota' ? 'active' : '' ?>" href="/login?mode=anggota">Anggota</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?= $activeTab === 'pengurus' ? 'active' : '' ?>" href="/login?mode=pengurus">Perangkat Kelas</a>
        </li>
    </ul>
    <div class="tab-content">
        <?php if ($activeTab === 'pengurus'): ?>
            <form action="/login/pengurus" method="post" class="d-grid gap-3">
                <?= csrf_input() ?>
                <div>
                    <label for="pengurus_identifier" class="form-label">Email atau Username</label>
                    <input type="text" name="email_or_username" id="pengurus_identifier" class="form-control <?= isset($errors['email_or_username']) ? 'is-invalid' : '' ?>" value="<?= e(old('email_or_username')) ?>" required autocomplete="username">
                    <?php if (isset($errors['email_or_username'])): ?><div class="invalid-feedback"><?= e($errors['email_or_username'][0]) ?></div><?php endif; ?>
                </div>
                <div>
                    <label for="pengurus_password" class="form-label">Password</label>
                    <input type="password" name="password" id="pengurus_password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required autocomplete="current-password">
                    <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password'][0]) ?></div><?php endif; ?>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="pengurusRemember" name="remember">
                    <label class="form-check-label" for="pengurusRemember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk sebagai Perangkat</button>
            </form>
        <?php else: ?>
            <form action="/login/anggota" method="post" class="d-grid gap-3">
                <?= csrf_input() ?>
                <div>
                    <label for="anggota_identifier" class="form-label">Email atau Username</label>
                    <input type="text" name="email_or_username" id="anggota_identifier" class="form-control <?= isset($errors['email_or_username']) ? 'is-invalid' : '' ?>" value="<?= e(old('email_or_username')) ?>" required autocomplete="username">
                    <?php if (isset($errors['email_or_username'])): ?><div class="invalid-feedback"><?= e($errors['email_or_username'][0]) ?></div><?php endif; ?>
                </div>
                <div>
                    <label for="anggota_password" class="form-label">Password</label>
                    <input type="password" name="password" id="anggota_password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required autocomplete="current-password">
                    <?php if (isset($errors['password'])): ?><div class="invalid-feedback"><?= e($errors['password'][0]) ?></div><?php endif; ?>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="anggotaRemember" name="remember">
                    <label class="form-check-label" for="anggotaRemember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk sebagai Anggota</button>
            </form>
        <?php endif; ?>
    </div>
</div>