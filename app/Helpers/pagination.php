<?php

function current_page(): int
{
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
    return max(1, (int) $page);
}

function build_pagination(int $total, int $perPage, int $currentPage): array
{
    $totalPages = (int) max(1, ceil($total / $perPage));
    $currentPage = max(1, min($currentPage, $totalPages));

    return [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'prev_page' => max(1, $currentPage - 1),
        'next_page' => min($totalPages, $currentPage + 1),
    ];
}

function pagination_query(array $pagination, array $extraParams = []): string
{
    $params = array_merge($extraParams, ['page' => $pagination['current_page']]);
    return http_build_query($params);
}

