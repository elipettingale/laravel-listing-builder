<?php

namespace EliPett\ListingBuilder\Services;

use EliPett\ListingBuilder\Services\Eloquent\EloquentListingBuilder;
use EliPett\ListingBuilder\Services\Mixed\MixedListingBuilder;

class BuildListing
{
    public static function forQuery($query)
    {
        return new EloquentListingBuilder($query);
    }

    public static function forCollection($collection)
    {
        return new MixedListingBuilder($collection);
    }
}
