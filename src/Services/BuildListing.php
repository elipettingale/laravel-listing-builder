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
            return self::fromQuery($data, $request);
        }

        if ($data instanceof Collection) {
            return self::fromCollection($data, $request);
        }

        if ($data instanceof Model) {
            return self::fromQuery($data::query(), $request);
        }

        throw new \InvalidArgumentException('Unable to process data of type: ' . \get_class($data));
    }

    public static function fromQuery(Builder $query, Request $request = null): ListingBuilder
    {
        return new ListingBuilder($request ?? request(), new EloquentFilter($request ?? request()), $query);
    }

    public static function fromCollection(Collection $collection, Request $request = null): ListingBuilder
    {
        return new ListingBuilder($request ?? request(), new CollectionFilter($request ?? request()), $collection);
    }
}
