<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EloquentScopeFilter
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function filter(Builder $query, string $scope): void
    {
        $method = substr($scope, 5);

        $query->$method();
    }
}
