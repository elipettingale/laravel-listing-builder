<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use EliPett\ListingBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class EloquentCallableFilter extends Filter
{
    public function filter(Builder $query, callable $function): void
    {
        $function($query);
    }
}
