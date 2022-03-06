<?php
/**
 * 自定义分液器
 */

namespace App\Models;

use Illuminate\Pagination\Paginator as SimplePaginator;

class Paginator extends SimplePaginator
{
    public function toArray(): array
    {
        return [
            'current_page' => $this->currentPage(),
            'per_page' => $this->perPage(),
            'items' => $this->items->toArray(),
        ];
    }
}
