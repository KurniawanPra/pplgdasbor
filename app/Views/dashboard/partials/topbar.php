<?php
$userName = auth_name() ?? 'Pengguna';
$role = auth_role();
$roleLabel = match ($role) {
    'superadmin' => 'Admin',
    'pengurus' => 'Perangkat Kelas',
    'anggota' => 'Anggota',
    default => 'Pengguna',
};
?>
<header class="topbar bg-white border-bottom px-3 px-md-4 py-2 d-flex align-items-center justify-content-between gap-3">
    <button class="btn btn-outline-primary d-md-none" id="sidebarToggle" type="button">
        <i class="bi bi-list"></i>
    </button>
    <div class="fw-semibold"><?= e($title ?? 'Dashboard') ?></div>
    <div class="d-flex align-items-center gap-2">
        <div class="text-end d-none d-sm-block">
            <div class="fw-semibold small mb-0"><?= e($userName) ?></div>
            <div class="text-muted small"><?= e($roleLabel) ?></div>
        </div>
        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
            <?= e(strtoupper(substr($userName, 0, 1))) ?>
        </div>
        <div>
            <a href="/logout" class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </div>
</header>

