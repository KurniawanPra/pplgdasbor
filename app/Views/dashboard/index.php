<div class="row g-4">
    <?php $statIcons = [
        'total_anggota' => ['label' => 'Total Anggota', 'icon' => 'bi-people-fill', 'variant' => 'primary'],
        'anggota_aktif' => ['label' => 'Anggota Aktif', 'icon' => 'bi-person-check', 'variant' => 'success'],
        'total_pengurus' => ['label' => 'Akun Perangkat Kelas', 'icon' => 'bi-person-gear', 'variant' => 'info'],
        'total_gallery' => ['label' => 'Foto Galeri', 'icon' => 'bi-images', 'variant' => 'warning'],
        'total_prestasi' => ['label' => 'Prestasi', 'icon' => 'bi-trophy', 'variant' => 'secondary'],
        'pesan_masuk' => ['label' => 'Pesan Masuk', 'icon' => 'bi-envelope', 'variant' => 'danger'],
    ]; ?>
    <?php foreach ($statIcons as $key => $config): ?>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon text-<?= e($config['variant']) ?> bg-<?= e($config['variant']) ?>-subtle">
                        <i class="bi <?= e($config['icon']) ?>"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-1"><?= e($config['label']) ?></div>
                        <div class="h4 fw-bold mb-0"><?= e($stats[$key] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5 class="fw-semibold">Selamat datang, <?= e(auth_name() ?? 'Admin') ?>!</h5>
        <p class="text-muted mb-0">Kelola data kelas XI PPLG melalui menu di sidebar. Pastikan selalu memperbarui data agar informasi tetap akurat dan up to date.</p>
    </div>
</div>


