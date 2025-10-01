<?php

namespace App\Controllers;

class Controller
{
    protected function render(string $view, array $data = [], ?string $layout = null): string
    {
        return view($view, $data, $layout);
    }

    protected function request(): array
    {
        $input = $_POST ?? [];
        $sanitized = [];
        foreach ($input as $key => $value) {
            if ($key === 'csrf_token') {
                continue;
            }
            $sanitized[$key] = is_string($value) ? trim($value) : $value;
        }
        return $sanitized;
    }

    protected function query(): array
    {
        return $_GET ?? [];
    }
}
