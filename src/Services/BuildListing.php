<?php

namespace EliPett\ListingBuilder\Services;

use EliPett\ListingBuilder\Filters\Collection\CollectionFilter;
use EliPett\ListingBuilder\Filters\Eloquent\EloquentFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BuildListing
{
    public static function fromQuery(Builder $query, Request $request = null): ListingBuilder
    {
        return new ListingBuilder($request ?? request(), new EloquentFilter($request ?? request()), $query);
    }

    public static function fromCollection(Collection $collection, Request $request = null): ListingBuilder
    {
        return new ListingBuilder($request ?? request(), new CollectionFilter($request ?? request()), $collection);
    }
}
