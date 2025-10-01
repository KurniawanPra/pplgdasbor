<?php $errors = flash('errors') ?? []; ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h5 fw-semibold mb-3">Pengaturan Situs</h2>
        <form action="/dashboard/settings/update" method="post" class="row g-3">
            <?= csrf_input() ?>
            <div class="col-md-6">
                <label for="motto" class="form-label">Motto Kelas</label>
                <input type="text" name="motto" id="motto" class="form-control <?= isset($errors['motto']) ? 'is-invalid' : '' ?>" value="<?= e(old('motto', $settings['motto'] ?? '')) ?>" required>
                <?php if (isset($errors['motto'])): ?><div class="invalid-feedback"><?= e($errors['motto'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="instagram" class="form-label">Instagram</label>
                <input type="text" name="instagram" id="instagram" class="form-control" value="<?= e(old('instagram', $settings['instagram'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label for="about" class="form-label">Deskripsi Kelas</label>
                <textarea name="about" id="about" rows="4" class="form-control <?= isset($errors['about']) ? 'is-invalid' : '' ?>" required><?= e(old('about', $settings['about'] ?? '')) ?></textarea>
                <?php if (isset($errors['about'])): ?><div class="invalid-feedback"><?= e($errors['about'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="contact_email" class="form-label">Email Kontak</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control <?= isset($errors['contact_email']) ? 'is-invalid' : '' ?>" value="<?= e(old('contact_email', $settings['contact_email'] ?? '')) ?>" required>
                <?php if (isset($errors['contact_email'])): ?><div class="invalid-feedback"><?= e($errors['contact_email'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="contact_phone" class="form-label">Nomor Kontak</label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control <?= isset($errors['contact_phone']) ? 'is-invalid' : '' ?>" value="<?= e(old('contact_phone', $settings['contact_phone'] ?? '')) ?>" required>
                <?php if (isset($errors['contact_phone'])): ?><div class="invalid-feedback"><?= e($errors['contact_phone'][0]) ?></div><?php endif; ?>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>
