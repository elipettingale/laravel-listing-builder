<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use EliPett\ListingBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class EloquentWhereLikeFilter extends Filter
{
    public function filter(Builder $query, string $key): void
    {
        if ($value = $this->request->get($key)) {
            $query->where($key, 'LIKE', "%$value%");
        }
    }
}