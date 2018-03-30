<?php

namespace EliPett\ListingBuilder\Services\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use EliPett\ListingBuilder\Services\ListingBuilder;
use EliPett\ListingBuilder\Structs\ListingSpecification;

class EloquentListingBuilder implements ListingBuilder
{
    private $query;

    private $column;
    private $direction;
    private $perPage;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function fromListingSpecification(ListingSpecification $listingSpecification): EloquentListingBuilder
    {
        $this->column = $listingSpecification->getColumn();
        $this->direction = $listingSpecification->getDirection();
        $this->perPage = $listingSpecification->getPerPage();

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
        if (!$column = $this->column) {
            $column = $defaultColumn;
        }

        if (!$direction = $this->direction) {
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
            ->paginate($this->perPage);
    }
}
