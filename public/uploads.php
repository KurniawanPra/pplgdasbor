<?php
require_once __DIR__ . '/../app/bootstrap.php';

$relativePath = $_GET['path'] ?? '';
$relativePath = str_replace(['..', '\\'], ['', '/'], $relativePath);
$relativePath = ltrim($relativePath, '/');
$fullPath = rtrim(config('paths.uploads'), '/') . '/' . $relativePath;

if (!is_file($fullPath)) {
    http_response_code(404);
    exit('File not found');
}

$mime = mime_content_type($fullPath) ?: 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($fullPath));
header('Cache-Control: public, max-age=86400');
readfile($fullPath);
exit;
