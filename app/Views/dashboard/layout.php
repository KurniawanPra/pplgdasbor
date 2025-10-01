<?php $pageTitle = $title ?? 'Dashboard'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - <?= e(config('app.name')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>
    <div class="d-flex" id="admin-wrapper">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        <div class="flex-grow-1 d-flex flex-column min-vh-100 bg-light">
            <?php include __DIR__ . '/partials/topbar.php'; ?>
            <main class="flex-grow-1 p-3 p-md-4">
                <?php include __DIR__ . '/../partials/flash.php'; ?>
                <?= $content ?>
            </main>
            <footer class="py-3 border-top bg-white text-center small text-muted">
                &copy; <?= date('Y') ?> <?= e(config('app.name')) ?>. All rights reserved.
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
