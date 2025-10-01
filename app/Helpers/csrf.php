<?php

function csrf_token(): string
{
    $key = config('security.csrf_token_key', 'csrf_token');
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = bin2hex(random_bytes(32));
    }
    return $_SESSION[$key];
}

function regenerate_csrf_token(): string
{
    $key = config('security.csrf_token_key', 'csrf_token');
    $_SESSION[$key] = bin2hex(random_bytes(32));
    return $_SESSION[$key];
}

function verify_csrf_token(?string $token): bool
{
    $key = config('security.csrf_token_key', 'csrf_token');
    $stored = $_SESSION[$key] ?? null;
    return is_string($token) && is_string($stored) && hash_equals($stored, $token);
}

function csrf_input(): string
{
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

