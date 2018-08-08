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
            return self::fromQuery($data, $request ?? request());
        }

        if ($data instanceof Collection) {
            return self::fromCollection($data, $request ?? request());
        }

        if ($data instanceof Model) {
            return self::fromQuery($data::query(), $request ?? request());
        }

        throw new \InvalidArgumentException('Unable to process data of type: ' . \get_class($data));
    }

    private static function fromQuery(Builder $query, Request $request): ListingBuilder
    {
        return new ListingBuilder($request, new EloquentFilter($request), $query);
    }

    private static function fromCollection(Collection $collection, Request $request): ListingBuilder
    {
        return new ListingBuilder($request, new CollectionFilter($request), $collection);
    }
}
