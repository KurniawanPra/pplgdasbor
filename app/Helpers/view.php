<?php

function view(string $template, array $data = [], ?string $layout = null): string
{
    $viewsDir = rtrim(config('paths.views'), '/');
    $file = $viewsDir . '/' . ltrim($template, '/');
    if (!str_ends_with($file, '.php')) {
        $file .= '.php';
    }

    if (!file_exists($file)) {
        throw new \RuntimeException("View not found: {$file}");
    }

    extract($data);
    ob_start();
    include $file;
    $content = ob_get_clean();

    if ($layout) {
        $layoutFile = $viewsDir . '/' . ltrim($layout, '/');
        if (!str_ends_with($layoutFile, '.php')) {
            $layoutFile .= '.php';
        }
        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Layout not found: {$layoutFile}");
        }
        $data['content'] = $content;
        extract($data);
        ob_start();
        include $layoutFile;
        return ob_get_clean();
    }

    return $content;
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
