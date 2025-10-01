<?php

function validate(array $data, array $rules, array $messages = []): array
{
    $errors = [];

    foreach ($rules as $field => $ruleString) {
        $value = $data[$field] ?? null;
        $rulesList = is_array($ruleString) ? $ruleString : explode('|', (string) $ruleString);

        foreach ($rulesList as $rule) {
            if ($rule === 'nullable') {
                if ($value === null || $value === '') {
                    continue 2;
                }
                continue;
            }

            [$name, $params] = parse_rule($rule);
            $handler = 'validate_' . $name;

            if (!function_exists($handler)) {
                continue;
            }

            $result = $handler($field, $value, $params, $data);
            if ($result !== true) {
                $errors[$field][] = $messages[$field . '.' . $name] ?? $result;
                break;
            }
        }
    }

    return $errors;
}

function parse_rule(string $rule): array
{
    if (str_contains($rule, ':')) {
        [$name, $paramString] = explode(':', $rule, 2);
        $params = array_map('trim', explode(',', $paramString));
        return [$name, $params];
    }

    return [$rule, []];
}

function validate_required(string $field, $value, array $params, array $data): bool|string
{
    if ($value === null || $value === '') {
        return ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi.';
    }
    return true;
}

function validate_email(string $field, $value, array $params, array $data): bool|string
{
    if ($value === null || $value === '') {
        return true;
    }

    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return 'Format email tidak valid.';
    }

    return true;
}

function validate_min(string $field, $value, array $params, array $data): bool|string
{
    $min = (int) ($params[0] ?? 0);
    if (is_string($value) && mb_strlen($value) < $min) {
        return ucfirst(str_replace('_', ' ', $field)) . " minimal {$min} karakter.";
    }

    if (!is_string($value) && is_numeric($value) && $value < $min) {
        return ucfirst(str_replace('_', ' ', $field)) . " minimal {$min}.";
    }

    return true;
}

function validate_max(string $field, $value, array $params, array $data): bool|string
{
    $max = (int) ($params[0] ?? PHP_INT_MAX);
    if (is_string($value) && mb_strlen($value) > $max) {
        return ucfirst(str_replace('_', ' ', $field)) . " maksimal {$max} karakter.";
    }

    if (!is_string($value) && is_numeric($value) && $value > $max) {
        return ucfirst(str_replace('_', ' ', $field)) . " maksimal {$max}.";
    }

    return true;
}

function validate_alpha_dash(string $field, $value, array $params, array $data): bool|string
{
    if ($value === null || $value === '') {
        return true;
    }

    if (!preg_match('/^[A-Za-z0-9_]+$/', (string) $value)) {
        return ucfirst(str_replace('_', ' ', $field)) . ' hanya boleh berisi huruf, angka, dan underscore.';
    }

    return true;
}

function validate_numeric(string $field, $value, array $params, array $data): bool|string
{
    if ($value === null || $value === '') {
        return true;
    }

    if (!preg_match('/^[0-9+]+$/', (string) $value)) {
        return ucfirst(str_replace('_', ' ', $field)) . ' hanya boleh berisi angka dan tanda plus.';
    }

    return true;
}


function validate_phone(string $field, $value, array $params, array $data): bool|string
{
    if ($value === null || $value === '') {
        return true;
    }

    $normalized = preg_replace('/[\s\-()\.]/', '', (string) $value);
    if ($normalized === null || $normalized === '') {
        return ucfirst(str_replace('_', ' ', $field)) . ' tidak valid.';
    }

    if (!preg_match('/^\+?\d+$/', $normalized)) {
        return ucfirst(str_replace('_', ' ', $field)) . ' tidak valid.';
    }

    return true;
}

function validate_in(string $field, $value, array $params, array $data): bool|string
{
    if ($value === null || $value === '') {
        return true;
    }

    if (!in_array($value, $params, true)) {
        return ucfirst(str_replace('_', ' ', $field)) . ' tidak valid.';
    }

    return true;
}

function validate_unique(string $field, $value, array $params, array $data): bool|string
{
    if ($value === null || $value === '') {
        return true;
    }

    [$table, $column, $ignoreId, $ignoreColumn] = array_pad($params, 4, null);
    if (!$table || !$column) {
        return true;
    }

    $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = :value";
    $bindings = [':value' => $value];

    if ($ignoreId && $ignoreColumn) {
        $sql .= " AND {$ignoreColumn} <> :ignore_id";
        $bindings[':ignore_id'] = $ignoreId;
    }

    $stmt = db()->prepare($sql);
    $stmt->execute($bindings);
    $exists = (int) $stmt->fetchColumn();

    if ($exists > 0) {
        return ucfirst(str_replace('_', ' ', $field)) . ' sudah digunakan.';
    }

    return true;
}

function validate_password(string $field, $value, array $params, array $data): bool|string
{
    if ($value === null || $value === '') {
        return true;
    }

    if (strlen((string) $value) < 8) {
        return 'Password minimal 8 karakter dengan kombinasi huruf dan angka.';
    }

    if (!preg_match('/[A-Za-z]/', (string) $value) || !preg_match('/\d/', (string) $value)) {
        return 'Password minimal 8 karakter dengan kombinasi huruf dan angka.';
    }

    return true;
}

function validate_confirmed(string $field, $value, array $params, array $data): bool|string
{
    $other = $params[0] ?? $field . '_confirmation';
    if (($data[$other] ?? null) !== $value) {
        return ucfirst(str_replace('_', ' ', $field)) . ' tidak sama.';
    }
    return true;
}

function first_error(array $errors, string $field): ?string
{
    return $errors[$field][0] ?? null;
}
