<?php

function handle_upload(array $file, string $folder, array $options = []): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Gagal mengunggah file.'];
    }

    $maxSize = $options['max_size'] ?? 2 * 1024 * 1024; // 2 MB
    if (($file['size'] ?? 0) > $maxSize) {
        return ['path' => null, 'error' => 'Ukuran file maksimal 1 MB.'];
    }

    $allowedMime = $options['allowed_mime'] ?? ['image/jpeg', 'image/png', 'image/webp'];
    $mime = null;
    if (class_exists('\\finfo')) {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: null;
    }
    if (!$mime && function_exists('mime_content_type')) {
        $mime = @mime_content_type($file['tmp_name']);
    }

    if (!$mime || !in_array($mime, $allowedMime, true)) {
        return ['path' => null, 'error' => 'Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.'];
    }

    $extensionMap = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $extension = $extensionMap[$mime] ?? strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $basePath = rtrim(config('paths.uploads'), '/');
    $dateSegment = date('Y/m/d');
    $folder = trim($folder, '/');
    $targetDir = $basePath . '/' . $folder . '/' . $dateSegment;

    if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
        return ['path' => null, 'error' => 'Tidak dapat membuat folder upload.'];
    }

    $filename = $folder . '_' . uniqid('', true) . '.' . $extension;
    $destination = $targetDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['path' => null, 'error' => 'Gagal menyimpan file.'];
    }

    if (!empty($options['resize'])) {
        resize_image($destination, $mime, (int) ($options['max_dimension'] ?? 512));
    }

    $relativePath = $folder . '/' . $dateSegment . '/' . $filename;
    return ['path' => $relativePath, 'error' => null];
}

function resize_image(string $path, string $mime, int $maxSize = 512): void
{
    if (!function_exists('imagecreatetruecolor')) {
        return;
    }

    [$width, $height] = getimagesize($path);
    if ($width <= $maxSize && $height <= $maxSize) {
        return;
    }

    $ratio = $width / $height;
    if ($ratio > 1) {
        $newWidth = $maxSize;
        $newHeight = (int) round($maxSize / $ratio);
    } else {
        $newHeight = $maxSize;
        $newWidth = (int) round($maxSize * $ratio);
    }

    $src = null;
    switch ($mime) {
        case 'image/jpeg':
            if (!function_exists('imagecreatefromjpeg')) {
                return;
            }
            $src = imagecreatefromjpeg($path);
            break;
        case 'image/png':
            if (!function_exists('imagecreatefrompng')) {
                return;
            }
            $src = imagecreatefrompng($path);
            break;
        case 'image/webp':
            if (!function_exists('imagecreatefromwebp')) {
                return;
            }
            $src = imagecreatefromwebp($path);
            break;
        default:
            return;
    }

    if (!$src) {
        return;
    }

    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($dst, $path, 85);
            break;
        case 'image/png':
            imagepng($dst, $path, 8);
            break;
        case 'image/webp':
            imagewebp($dst, $path, 85);
            break;
    }

    imagedestroy($src);
    imagedestroy($dst);
}

function delete_uploaded_file(?string $relativePath): void
{
    if (!$relativePath) {
        return;
    }

    $fullPath = rtrim(config('paths.uploads'), '/') . '/' . ltrim($relativePath, '/');
    if (is_file($fullPath)) {
        unlink($fullPath);
    }
}
