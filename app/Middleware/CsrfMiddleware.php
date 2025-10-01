<?php

namespace App\Middleware;

class CsrfMiddleware
{
    public function handle(array $params = []): bool
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return true;
        }

        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!verify_csrf_token($token)) {
            app_log('warning', 'CSRF token mismatch', [
                'uri' => $_SERVER['REQUEST_URI'] ?? '',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            ]);
            flash('error', 'Permintaan tidak sah. Silakan coba lagi.');
            $referer = $_SERVER['HTTP_REFERER'] ?? '/';
            redirect($referer);
            return false;
        }

        return true;
    }
}
