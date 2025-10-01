<?php
return [
    'app' => [
        'name' => 'Website Kelas XI PPLG SMK Swasta Al Washliyah 2 Perdagangan',
        'url' => getenv('APP_URL') ?: 'http://localhost',
        'env' => getenv('APP_ENV') ?: 'development',
        'session_name' => getenv('SESSION_NAME') ?: 'XI1PPLGSESS',
        'cookie_prefix' => getenv('COOKIE_PREFIX') ?: 'xi1pplg_',
        'debug' => getenv('APP_ENV') === 'production' ? false : true,
    ],
    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ? (int) getenv('DB_PORT') : 3306,
        'name' => getenv('DB_NAME') ?: 'rpltest',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
        'unix_socket' => getenv('DB_SOCKET') ?: null,
        'timeout' => getenv('DB_TIMEOUT') ? (int) getenv('DB_TIMEOUT') : 10,
    ],
    'security' => [
        'csrf_token_key' => 'csrf_token',
        'login_rate_limit' => [
            'max_attempts' => 5,
            'decay_minutes' => 10,
        ],
        'remember_me' => [
            'enabled' => true,
            'cookie_name' => 'xi1pplg_remember',
            'lifetime_days' => 30,
        ],
    ],
    'paths' => [
        'storage' => __DIR__ . '/../storage',
        'views' => __DIR__ . '/../app/Views',
        'uploads' => __DIR__ . '/../storage/uploads',
        'logs' => __DIR__ . '/../storage/logs/app.log',
    ],
];