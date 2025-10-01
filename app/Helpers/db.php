<?php

function db(): \PDO
{
    static $connection = null;

    $config = config('database');
    if (!is_array($config)) {
        throw new \RuntimeException('Database configuration is invalid.');
    }

    $handleFailure = static function (\PDOException $exception): void {
        app_log('error', 'Database connection failed', ['error' => $exception->getMessage()]);
        http_response_code(500);
        if (config('app.debug')) {
            die('Database connection failed: ' . $exception->getMessage());
        }
        die('Database connection failed.');
    };

    $shouldReconnect = static function (\PDOException $exception): bool {
        $code = (int) $exception->getCode();
        $message = strtolower($exception->getMessage());
        if ($code === 2006 || $code === 2013) {
            return true;
        }

        return str_contains($message, 'server has gone away')
            || str_contains($message, 'lost connection')
            || str_contains($message, 'no connection to the server');
    };

    $connect = static function () use (&$connection, $config, $handleFailure): \PDO {
        $host = $config['host'] ?? 'localhost';
        $name = $config['name'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';
        $port = $config['port'] ?? null;
        $socket = $config['unix_socket'] ?? null;

        $dsnParts = ["host={$host}", "dbname={$name}", "charset={$charset}"];
        if (!empty($port)) {
            $dsnParts[] = 'port=' . (int) $port;
        }
        if (!empty($socket)) {
            $dsnParts[] = 'unix_socket=' . $socket;
        }

        $dsn = 'mysql:' . implode(';', $dsnParts);

        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        if (!empty($config['timeout'])) {
            $options[\PDO::ATTR_TIMEOUT] = (int) $config['timeout'];
        }

        try {
            $connection = new \PDO(
                $dsn,
                $config['user'] ?? null,
                $config['pass'] ?? null,
                $options
            );
        } catch (\PDOException $exception) {
            $handleFailure($exception);
        }

        return $connection;
    };

    if ($connection instanceof \PDO) {
        try {
            $connection->query('SELECT 1');
        } catch (\PDOException $exception) {
            if ($shouldReconnect($exception)) {
                app_log('warning', 'Database connection lost. Attempting to reconnect.', ['error' => $exception->getMessage()]);
                $connection = null;
            } else {
                $handleFailure($exception);
            }
        }
    }

    if (!$connection instanceof \PDO) {
        $connect();
    }

    return $connection;
}