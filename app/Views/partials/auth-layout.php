<?php
$pageTitle = $title ?? 'Login';
$variant = $authVariant ?? 'perangkat';
$subtitleText = $subtitle ?? null;
$bodyClass = $variant === 'admin' ? 'auth-body auth-admin' : 'auth-body auth-perangkat';
$cardClasses = 'auth-card shadow-sm p-4 p-md-5 rounded-4 w-100';
if ($variant === 'admin') {
    $cardClasses .= ' auth-card-admin';
} else {
    $cardClasses .= ' bg-white auth-card-perangkat';
}
$linkClasses = $variant === 'admin'
    ? 'text-decoration-none text-white-50 d-inline-flex align-items-center gap-2'
    : 'text-decoration-none text-muted d-inline-flex align-items-center gap-2';
$textMutedClass = $variant === 'admin' ? 'text-white-50' : 'text-muted';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body.auth-body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        body.auth-perangkat {
            background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
        }
        body.auth-admin {
            background: radial-gradient(circle at 20% 20%, #1b2a4b 0%, #0b1526 100%);
            color: #fff;
        }
        .auth-card {
            transition: all .3s ease;
        }
        .auth-card-perangkat {
            border-top: 4px solid #0d6efd;
        }
        .auth-card-admin {
            background: rgba(17, 25, 40, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 4px solid #f8b84e;
            color: #f1f5f9;
            backdrop-filter: blur(12px);
        }
        .auth-card-admin .form-control {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        .auth-card-admin .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .auth-card-admin .form-control:focus {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: rgba(248, 184, 78, 0.8);
            color: #fff;
            box-shadow: 0 0 0 .25rem rgba(248, 184, 78, 0.25);
        }
        .auth-card-admin label,
        .auth-card-admin .form-check-label {
            color: #e2e8f0;
        }
        .auth-card-admin a {
            color: #f8b84e;
        }
        .auth-card-admin .btn-warning {
            color: #1f2933;
            font-weight: 600;
        }
        .auth-card-perangkat .btn-primary {
            box-shadow: 0 .5rem 1rem rgba(13, 110, 253, 0.15);
            font-weight: 600;
        }
    </style>
</head>
<body class="<?= e($bodyClass) ?>">
    <div class="auth-wrapper d-flex align-items-center justify-content-center min-vh-100">
        <div class="<?= e($cardClasses) ?>" style="max-width: 420px;">
            <div class="mb-3">
                <a href="/" class="<?= e($linkClasses) ?>">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke beranda</span>
                </a>
            </div>
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold mb-1"><?= e(config('app.name')) ?></h1>
                <?php if ($subtitleText): ?>
                    <p class="<?= e($textMutedClass) ?> mb-0"><?= e($subtitleText) ?></p>
                <?php else: ?>
                    <p class="<?= e($textMutedClass) ?> mb-0"><?= e($pageTitle) ?></p>
                <?php endif; ?>
            </div>
            <?php include __DIR__ . '/flash.php'; ?>
            <?= $content ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

