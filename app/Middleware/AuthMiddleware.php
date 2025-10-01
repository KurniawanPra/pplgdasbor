<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle(array $params = []): bool
    {
        if (auth_check()) {
            return true;
        }

        $redirect = $params[0] ?? '/login';
        if ($redirect === 'admin') {
            $redirect = '/admin/login';
        }

        flash('error', 'Silakan login terlebih dahulu.');
        redirect($redirect);
        return false;
    }
}
