<?php

namespace EliPett\ListingBuilder\Services\Mixed;

use Illuminate\Pagination\LengthAwarePaginator;
use EliPett\ListingBuilder\Services\ListingBuilder;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Support\Collection;

class MixedListingBuilder implements ListingBuilder
{
    private $collection;

    private $column;
    private $direction;
    private $perPage;
    private $page;
    private $url;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function fromListingSpecification(ListingSpecification $listingSpecification): MixedListingBuilder
    {
        $this->column = $listingSpecification->getColumn();
        $this->direction = $listingSpecification->getDirection();
        $this->perPage = $listingSpecification->getPerPage();
        $this->page = $listingSpecification->getCurrentPage();
        $this->url = $listingSpecification->getUrl();

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
        if (!$column = $this->column) {
            $column = $defaultColumn;
        }

        if (!$direction = $this->direction) {
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

    public function filterResults(callable $function): MixedListingBuilder
    {
        \call_user_func($function, [&$this->collection]);

        return $this;
    }

    public function filterResultsIfTrue(string $key, callable $function): MixedListingBuilder
    {
        if (request()->get($key) === 'true') {
            \call_user_func($function, [&$this->collection]);
        }

        return $this;
    }

    public function filterResultsIfNotTrue(string $key, callable $function): MixedListingBuilder
    {
        if (request()->get($key) !== 'true') {
            \call_user_func($function, [&$this->collection]);
        }

        return $this;
    }

    public function filterResultsIfFalse(string $key, callable $function): MixedListingBuilder
    {
        if (request()->get($key) === 'false') {
            \call_user_func($function, [&$this->collection]);
        }

        return $this;
    }

    public function filterResultsIfNotFalse(string $key, callable $function): MixedListingBuilder
    {
        if (request()->get($key) !== 'false') {
            \call_user_func($function, [&$this->collection]);
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

    public function getResults(): Collection
    {
        return $this->collection;
    }

    public function getPaginatedResults(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $this->collection->forPage($this->page, $this->perPage),
            $this->collection->count(),
            $this->perPage,
            $this->page,
            ['path' => $this->url]
        );
    }
}
