<?php

namespace Ejp\ListingBuilder\Services\Mixed;

use Illuminate\Pagination\LengthAwarePaginator;
use Ejp\ListingBuilder\Services\ListingBuilder;
use Ejp\ListingBuilder\Structs\ListingSpecification;

class MixedListingBuilder implements ListingBuilder
{
    /**
     * @var \Ejp\ListingBuilder\Structs\ListingSpecification $listingSpecification
     */
    private $listingSpecification;

    private $collection;

    public function fromListingSpecification(ListingSpecification $listingSpecification): MixedListingBuilder
    {
        $this->listingSpecification = $listingSpecification;

        return $this;
    }

    public function setCollection($collection): MixedListingBuilder
    {
        $this->collection = $collection;

        return $this;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function orderResults(string $defaultColumn, string $defaultDirection): MixedListingBuilder
    {
        if (!$column = $this->listingSpecification->getColumn()) {
            $column = $defaultColumn;
        }

        if (!$direction = $this->listingSpecification->getDirection()) {
            $direction = $defaultDirection;
        }

        if ($direction === 'asc') {
            $this->collection = $this->collection->sortBy($column);
        }

        if ($direction === 'desc') {
            $this->collection = $this->collection->sortByDesc($column);
        }

        return $this;
    }

    public function filterResultsWhereLike(array $keys): MixedListingBuilder
    {
        foreach ($keys as $key) {
            if ($value = request()->get($key)) {
                $this->collection = $this->collection->filter(function ($event) use ($value, $key) {
                    return strpos($event->$key, $value) !== false;
                });
            }
        }

        return $this;
    }

    public function filterResultsWhereEqual(array $keys): MixedListingBuilder
    {
        foreach ($keys as $key) {
            if ($value = request()->get($key)) {
                $this->collection = $this->collection->filter(function ($event) use ($value, $key) {
                    return $event->$key === $value;
                });
            }
        }

        return $this;
    }

    public function filterResultsWhereConcatLike(string $key, string $firstColumn, string $secondColumn): MixedListingBuilder
    {
        if ($value = request()->get($key)) {
            $this->collection = $this->collection->filter(function ($event) use ($value, $firstColumn, $secondColumn) {
                return strpos($event->$firstColumn . ' ' . $event->$secondColumn, $value) !== false;
            });
        }

        return $this;
    }

    public function getPaginatedResults(): LengthAwarePaginator
    {
        $perPage = $this->listingSpecification->getPerPage();
        $page = $this->listingSpecification->getCurrentPage();
        $url = $this->listingSpecification->getUrl();

        return new LengthAwarePaginator(
            $this->collection->forPage($page, $perPage),
            $this->collection->count(),
            $perPage,
            $page,
            ['path' => $url]);
    }
}
