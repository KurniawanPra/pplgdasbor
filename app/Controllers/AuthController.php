<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
{
    private User $users;

    private const PENGURUS_ROLES = ['ketua', 'wakil_ketua', 'bendahara', 'sekretaris', 'wali_kelas', 'pengurus'];
    private const ADMIN_ROLES = ['administrator', 'superadmin'];

    public function __construct()
    {
        $this->users = new User();
    }

    public function showLogin(): string
    {
        $query = $this->query();
        $mode = $query['mode'] ?? 'anggota';
        if (!in_array($mode, ['anggota', 'pengurus'], true)) {
            $mode = 'anggota';
        }

        return $this->render('forms/login-portal', [
            'title' => 'Portal Login Kelas',
            'subtitle' => 'Pilih akses sesuai peran Anda',
            'activeTab' => $mode,
        ], 'partials/auth-layout');
    }

    public function showHiddenAdminLogin(): string
    {
        return $this->render('forms/admin-login', [
            'title' => 'Login Administrator',
            'subtitle' => 'Hanya untuk pengelola utama sistem',
        ], 'partials/auth-layout');
    }

    public function loginAnggota(): void
    {
        $data = $this->request();
        $this->validateLoginInput($data, '/login?mode=anggota');

        $user = $this->users->findByIdentifier($data['email_or_username']);
        if (!$user || $user['role'] !== 'anggota' || !password_verify($data['password'], $user['password'])) {
            $this->rejectAttempt('member_login', '/login?mode=anggota', $data['email_or_username']);
        }

        $this->completeLogin($user, $data, '/dashboard');
    }

    public function loginPengurus(): void
    {
        $data = $this->request();
        $this->validateLoginInput($data, '/login?mode=pengurus');

        $user = $this->users->findByIdentifier($data['email_or_username']);
        if (!$user || !in_array($user['role'], self::PENGURUS_ROLES, true) || !password_verify($data['password'], $user['password'])) {
            $this->rejectAttempt('pengurus_login', '/login?mode=pengurus', $data['email_or_username']);
        }

        $this->completeLogin($user, $data, '/dashboard');
    }

    public function adminLogin(): void
    {
        $data = $this->request();
        $oldInput = $data;
        unset($oldInput['password']);
        store_old_input($oldInput);

        $errors = validate($data, [
            'username' => 'required|min:4|max:50',
            'password' => 'required|min:8',
        ]);

        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali username dan password.');
            redirect('/system/super/login');
        }

        $key = 'admin_login:' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $this->guardRateLimit($key, '/system/super/login');

        $user = $this->users->findByUsername($data['username']);
        if (!$user || !in_array($user['role'], self::ADMIN_ROLES, true) || !password_verify($data['password'], $user['password'])) {
            record_login_attempt($key);
            flash('error', 'Username atau password salah.');
            app_log('warning', 'Login admin gagal', [
                'username' => $data['username'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            ]);
            redirect('/system/super/login');
        }

        clear_login_attempts($key);
        clear_old_input();

        login_user($user, !empty($data['remember']));
        app_log('info', 'Login admin berhasil', ['user_id' => $user['id']]);
        redirect('/dashboard');
    }

    public function logout(): void
    {
        $role = auth_role();
        logout_user();
        flash('success', 'Anda telah logout.');
        if ($role && in_array($role, self::ADMIN_ROLES, true)) {
            redirect('/system/super/login');
        }
        redirect('/login');
    }

    private function validateLoginInput(array $data, string $redirectPath): void
    {
        $oldInput = $data;
        unset($oldInput['password']);
        store_old_input($oldInput);

        $rules = [
            'email_or_username' => 'required|min:3|max:100',
            'password' => 'required|min:8',
        ];
        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data login Anda.');
            redirect($redirectPath);
        }
    }

    private function rejectAttempt(string $keyPrefix, string $redirectPath, string $identifier): void
    {
        $key = $keyPrefix . ':' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $this->guardRateLimit($key, $redirectPath);

        record_login_attempt($key);
        flash('error', 'Kombinasi kredensial tidak ditemukan.');
        app_log('warning', 'Login gagal', [
            'identifier' => $identifier,
            'route' => $keyPrefix,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        ]);
        redirect($redirectPath);
    }

    private function guardRateLimit(string $key, string $redirectPath): void
    {
        $maxAttempts = (int) config('security.login_rate_limit.max_attempts', 5);
        $decay = (int) config('security.login_rate_limit.decay_minutes', 10);
        if (too_many_attempts($key, $maxAttempts, $decay)) {
            flash('error', 'Terlalu banyak percobaan login. Coba lagi beberapa menit lagi.');
            redirect($redirectPath);
        }
    }

    private function completeLogin(array $user, array $payload, string $redirectPath): void
    {
        clear_old_input();
        $remember = !empty($payload['remember']);
        login_user($user, $remember);

        app_log('info', 'Login berhasil', [
            'user_id' => $user['id'],
            'role' => $user['role'],
        ]);

        redirect($redirectPath);
    }
}