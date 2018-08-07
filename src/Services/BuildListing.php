<?php

namespace EliPett\ListingBuilder\Services;

use EliPett\ListingBuilder\Builders\Eloquent\EloquentListingBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BuildListing
{
    public static function fromQuery(Builder $query, Request $request = null): EloquentListingBuilder
    {
        return new EloquentListingBuilder($query, $request ?? request());
    }
}
