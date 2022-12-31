<?php namespace App\Http\Helpers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Helper
{
    public static function paginate($items, $perPage , $page = null, $options = [])
    {
        $page  = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $currentPageResults = $items->slice(($page - 1) * $perPage, $perPage)->values();
        return new LengthAwarePaginator($currentPageResults, $items->count(), $perPage, $page, $options);
    }
}