<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use EliPett\ListingBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class EloquentScopeFilter extends Filter
{
    public function filter(Builder $query, string $scope): void
    {
        $method = substr($scope, 5);

        $query->$method();
    }
}
