<?php

$rootPath = dirname(__DIR__);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', $rootPath);
}

if (file_exists($rootPath . '/.env')) {
    $lines = file($rootPath . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"' ");
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}

$config = require $rootPath . '/config/config.php';
$GLOBALS['app_config'] = $config;

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        $config = $GLOBALS['app_config'] ?? [];
        $segments = explode('.', $key);
        foreach ($segments as $segment) {
            if (is_array($config) && array_key_exists($segment, $config)) {
                $config = $config[$segment];
            } else {
                return $default;
            }
        }
        return $config;
    }
}

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (strpos($class, $prefix) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

require_once __DIR__ . '/Helpers/logger.php';
require_once __DIR__ . '/Helpers/db.php';
require_once __DIR__ . '/Helpers/session.php';
require_once __DIR__ . '/Helpers/auth.php';
require_once __DIR__ . '/Helpers/csrf.php';
require_once __DIR__ . '/Helpers/validator.php';
require_once __DIR__ . '/Helpers/pagination.php';
require_once __DIR__ . '/Helpers/upload.php';
require_once __DIR__ . '/Helpers/router.php';
require_once __DIR__ . '/Helpers/view.php';

$directories = [
    config('paths.storage'),
    config('paths.uploads'),
    config('paths.uploads') . '/siswa',
    config('paths.uploads') . '/gallery',
    dirname(config('paths.logs') ?? ''),
];

foreach ($directories as $dir) {
    if (is_string($dir) && $dir !== '' && !is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}

$logFile = config('paths.logs');
if (is_string($logFile) && $logFile !== '' && !file_exists($logFile)) {
    @touch($logFile);
}

$timezone = getenv('APP_TIMEZONE') ?: 'Asia/Jakarta';
date_default_timezone_set($timezone);

$sessionName = config('app.session_name');
if (session_status() === PHP_SESSION_NONE) {
    session_name($sessionName);
    session_start();
}

