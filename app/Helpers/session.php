<?php

function session_get(string $key, $default = null)
{
    return $_SESSION[$key] ?? $default;
}

function session_put(string $key, $value): void
{
    $_SESSION[$key] = $value;
}

function session_forget(string $key): void
{
    unset($_SESSION[$key]);
}

function session_regenerate(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

function flash(string $key, $value = null)
{
    $flashKey = '_flash_' . $key;
    if ($value === null) {
        if (!isset($_SESSION[$flashKey])) {
            return null;
        }
        $data = $_SESSION[$flashKey];
        unset($_SESSION[$flashKey]);
        return $data;
    }

    $_SESSION[$flashKey] = $value;
}

function old(string $key, $default = '')
{
    $oldInput = $_SESSION['_old_input'] ?? [];
    return $oldInput[$key] ?? $default;
}

function store_old_input(array $input): void
{
    $_SESSION['_old_input'] = $input;
}

function clear_old_input(): void
{
    unset($_SESSION['_old_input']);
}

function remember_user(string $token): void
{
    if (!config('security.remember_me.enabled')) {
        return;
    }

    $cookieName = config('security.remember_me.cookie_name');
    $lifetime = (int) config('security.remember_me.lifetime_days', 30) * 24 * 60 * 60;
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443;

    setcookie($cookieName, $token, [
        'expires' => time() + $lifetime,
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function forget_remembered_user(): void
{
    $cookieName = config('security.remember_me.cookie_name');
    if (isset($_COOKIE[$cookieName])) {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? 80) == 443;
        setcookie($cookieName, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        unset($_COOKIE[$cookieName]);
    }
}

function record_login_attempt(string $key): void
{
    $attempts = $_SESSION['_login_attempts'][$key] ?? [];
    $attempts[] = time();
    $_SESSION['_login_attempts'][$key] = $attempts;
}

function too_many_attempts(string $key, int $maxAttempts, int $decayMinutes): bool
{
    $attempts = $_SESSION['_login_attempts'][$key] ?? [];
    $window = time() - ($decayMinutes * 60);
    $attempts = array_filter($attempts, fn ($timestamp) => $timestamp >= $window);
    $_SESSION['_login_attempts'][$key] = $attempts;

    return count($attempts) >= $maxAttempts;
}

function clear_login_attempts(string $key): void
{
    unset($_SESSION['_login_attempts'][$key]);
}
