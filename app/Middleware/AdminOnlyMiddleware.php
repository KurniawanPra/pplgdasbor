<?php

namespace App\Middleware;

class AdminOnlyMiddleware
{
    public function handle(array $params = []): bool
    {
        if (!auth_check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('/admin/login');
            return false;
        }

        if (auth_role() !== 'superadmin') {
            http_response_code(403);
            echo view('pages/403', ['title' => 'Tidak Diizinkan']);
            return false;
        }

        return true;
    }
}
