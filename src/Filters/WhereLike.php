<?php

namespace EliPett\ListingBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class WhereLike
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function filter(Builder $query, string $key): void
    {
        if ($value = $this->request->get($key)) {
            $query->where($key, 'LIKE', "%$value%");
        }
    }
}
