<?php

function auth_user(): ?array
{
    return session_get('user');
}

function auth_check(): bool
{
    return auth_user() !== null;
}

function auth_id(): ?int
{
    return auth_user()['id'] ?? null;
}

function auth_role(): ?string
{
    return auth_user()['role'] ?? null;
}

function auth_name(): ?string
{
    return auth_user()['nama_lengkap'] ?? null;
}

function login_user(array $user, bool $remember = false): void
{
    session_regenerate();
    session_put('user', [
        'id' => (int) $user['id'],
        'nama_lengkap' => $user['nama_lengkap'],
        'role' => $user['role'],
        'email' => $user['email'] ?? null,
        'username' => $user['username'] ?? null,
    ]);

    if ($remember) {
        $token = bin2hex(random_bytes(32));
        remember_user($token);
        session_put('remember_token', $token);
    }
}

function logout_user(): void
{
    forget_remembered_user();
    session_forget('remember_token');
    session_forget('user');
    clear_old_input();
}

function auth_has_role($roles): bool
{
    $role = auth_role();
    $roles = is_array($roles) ? $roles : [$roles];
    return $role !== null && in_array($role, $roles, true);
}

