<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EloquentCallableFilter
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function filter(Builder $query, callable $function): void
    {
        $function($query);
    }
}
