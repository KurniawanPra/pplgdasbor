<?php
$errors = flash('errors') ?? [];
$item = $item ?? [];
$tingkatOptions = ['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'];
?>
<form action="<?= e($action) ?>" method="post" class="row g-3">
    <?= csrf_input() ?>
    <?php if (!empty($item['id'])): ?>
        <input type="hidden" name="id" value="<?= e($item['id']) ?>">
    <?php endif; ?>
    <div class="col-12">
        <label for="judul" class="form-label">Judul Prestasi</label>
        <input type="text" name="judul" id="judul" class="form-control <?= isset($errors['judul']) ? 'is-invalid' : '' ?>" value="<?= e(old('judul', $item['judul'] ?? '')) ?>" maxlength="150" required>
        <?php if (isset($errors['judul'])): ?><div class="invalid-feedback"><?= e($errors['judul'][0]) ?></div><?php endif; ?>
    </div>
    <div class="col-md-4">
        <label for="tanggal" class="form-label">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" class="form-control <?= isset($errors['tanggal']) ? 'is-invalid' : '' ?>" value="<?= e(old('tanggal', $item['tanggal'] ?? '')) ?>">
        <?php if (isset($errors['tanggal'])): ?><div class="invalid-feedback"><?= e($errors['tanggal'][0]) ?></div><?php endif; ?>
    </div>
    <div class="col-md-4">
        <label for="tingkat" class="form-label">Tingkat</label>
        <select name="tingkat" id="tingkat" class="form-select">
            <option value="">-- Pilih Tingkat --</option>
            <?php $selectedTingkat = old('tingkat', $item['tingkat'] ?? ''); ?>
            <?php foreach ($tingkatOptions as $option): ?>
                <option value="<?= e($option) ?>" <?= $selectedTingkat === $option ? 'selected' : '' ?>><?= e($option) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label for="penyelenggara" class="form-label">Penyelenggara</label>
        <input type="text" name="penyelenggara" id="penyelenggara" class="form-control <?= isset($errors['penyelenggara']) ? 'is-invalid' : '' ?>" value="<?= e(old('penyelenggara', $item['penyelenggara'] ?? '')) ?>" maxlength="120">
        <?php if (isset($errors['penyelenggara'])): ?><div class="invalid-feedback"><?= e($errors['penyelenggara'][0]) ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
        <label for="lokasi" class="form-label">Lokasi</label>
        <input type="text" name="lokasi" id="lokasi" class="form-control <?= isset($errors['lokasi']) ? 'is-invalid' : '' ?>" value="<?= e(old('lokasi', $item['lokasi'] ?? '')) ?>" maxlength="120">
        <?php if (isset($errors['lokasi'])): ?><div class="invalid-feedback"><?= e($errors['lokasi'][0]) ?></div><?php endif; ?>
    </div>
    <div class="col-12">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control <?= isset($errors['deskripsi']) ? 'is-invalid' : '' ?>" maxlength="2000"><?= e(old('deskripsi', $item['deskripsi'] ?? '')) ?></textarea>
        <?php if (isset($errors['deskripsi'])): ?><div class="invalid-feedback"><?= e($errors['deskripsi'][0]) ?></div><?php endif; ?>
    </div>
    <div class="col-12 d-flex justify-content-end gap-2">
        <a href="/dashboard/prestasi" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
