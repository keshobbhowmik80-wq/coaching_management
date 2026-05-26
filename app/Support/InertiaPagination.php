<?php

namespace App\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InertiaPagination
{
    /**
     * @return array{data: mixed, links: mixed, meta: array<string, mixed>}
     */
    public static function format(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => $paginator->items(),
            'links' => $paginator->linkCollection(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'path' => $paginator->path(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ],
        ];
    }
}
