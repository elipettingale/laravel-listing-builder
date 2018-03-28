<?php

namespace Ejp\ListingBuilder\Services\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use Ejp\ListingBuilder\Services\ListingBuilder;
use Ejp\ListingBuilder\Structs\ListingSpecification;

class EloquentListingBuilder implements ListingBuilder
{
    /**
     * @var \Ejp\ListingBuilder\Structs\ListingSpecification $listingSpecification
     */
    private $listingSpecification;

    private $query;

    public function fromListingSpecification(ListingSpecification $listingSpecification): EloquentListingBuilder
    {
        $this->listingSpecification = $listingSpecification;

        return $this;
    }

    public function setQueryObject($query): EloquentListingBuilder
    {
        $this->query = $query;

        return $this;
    }

    public function getQueryObject()
    {
        return $this->query;
    }

    public function orderResults(string $defaultColumn, string $defaultDirection): EloquentListingBuilder
    {
        if (!$column = $this->listingSpecification->getColumn()) {
            $column = $defaultColumn;
        }

        if (!$direction = $this->listingSpecification->getDirection()) {
            $direction = $defaultDirection;
        }

        $this->query = $this->query
            ->orderBy($column, $direction);

        return $this;
    }

    public function filterResultsWhereLike(array $keys): EloquentListingBuilder
    {
        foreach ($keys as $key) {
            if ($value = request()->get($key)) {
                $this->query = $this->query
                    ->where($key, 'like', "%$value%");
            }
        }

        return $this;
    }

    public function filterResultsWhereEqual(array $keys): EloquentListingBuilder
    {
        foreach ($keys as $key) {
            if ($value = request()->get($key)) {
                $this->query = $this->query
                    ->where($key, '=', $value);
            }
        }

        return $this;
    }

    public function filterResultsWhereConcatLike(string $key, string $firstColumn, string $secondColumn): EloquentListingBuilder
    {
        if ($value = request()->get($key)) {
            $this->query = $this->query
                ->whereRaw('Concat(' . $firstColumn . ', " ", ' . $secondColumn . ') LIKE ? ', "%$value%");
        }

        return $this;
    }

    public function getPaginatedResults(): LengthAwarePaginator
    {
        return $this->query
            ->paginate($this->listingSpecification->getPerPage());
    }
}
