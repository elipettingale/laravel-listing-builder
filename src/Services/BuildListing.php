<?php

namespace EliPett\ListingBuilder\Services;

use EliPett\ListingBuilder\Filters\Collection\CollectionFilter;
use EliPett\ListingBuilder\Filters\Eloquent\EloquentFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BuildListing
{
    public static function from($data, Request $request = null): ListingBuilder
    {
        if ($data instanceof Builder) {
            return self::eloquentListingBuilder($data, $request ?? request());
        }

        if ($data instanceof Collection) {
            return self::collectionListingBuilder($data, $request ?? request());
        }

        if ($data instanceof Model) {
            return self::eloquentListingBuilder($data::query(), $request ?? request());
        }

        throw new \InvalidArgumentException('Unable to process data of type: ' . \get_class($data));
    }

    private static function eloquentListingBuilder(Builder $query, Request $request): ListingBuilder
    {
        return new ListingBuilder($request, new EloquentFilter($request), $query);
    }

    private static function collectionListingBuilder(Collection $collection, Request $request): ListingBuilder
    {
        return new ListingBuilder($request, new CollectionFilter($request), $collection);
    }
}
