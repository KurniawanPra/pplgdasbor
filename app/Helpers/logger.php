<?php

function app_log(string $level, string $message, array $context = []): void
{
    $logPath = config('paths.logs');
    $directory = dirname($logPath);

    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }

    $timestamp = (new \DateTime())->format('Y-m-d H:i:s');
    $contextString = $context ? json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';
    $line = sprintf('[%s] %s: %s %s%s', $timestamp, strtoupper($level), $message, $contextString, PHP_EOL);

    file_put_contents($logPath, $line, FILE_APPEND | LOCK_EX);
}
