<?php

namespace App\Middleware;

class RoleMiddleware
{
    public function handle(array $params = []): bool
    {
        if (!auth_check()) {
            flash('error', 'Silakan login terlebih dahulu.');
            redirect('/login');
            return false;
        }

        $allowedRoles = $params;
        if (empty($allowedRoles)) {
            return true;
        }

        if (!auth_has_role($allowedRoles)) {
            if (auth_role() === 'anggota') {
                flash('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                redirect('/');
                return false;
            }

            http_response_code(401);
            echo view('pages/401', ['title' => 'Tidak Berhak']);
            return false;
        }

        return true;
    }
}
