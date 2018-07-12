<?php

namespace EliPett\ListingBuilder\Services\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use EliPett\ListingBuilder\Services\ListingBuilder;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Support\Collection;

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

    public function filterResults(callable $function): EloquentListingBuilder
    {
        \call_user_func_array($function, [&$this->query]);

        return $this;
    }

    public function filterResultsIfTrue(string $key, callable $function): EloquentListingBuilder
    {
        if (request()->get($key) === 'true') {
            \call_user_func_array($function, [&$this->query]);
        }

        return $this;
    }

    public function filterResultsIfNotTrue(string $key, callable $function): EloquentListingBuilder
    {
        if (request()->get($key) !== 'true') {
            \call_user_func_array($function, [&$this->query]);
        }

        return $this;
    }

    public function filterResultsIfFalse(string $key, callable $function): EloquentListingBuilder
    {
        if (request()->get($key) === 'false') {
            \call_user_func_array($function, [&$this->query]);
        }

        return $this;
    }

    public function filterResultsIfNotFalse(string $key, callable $function): EloquentListingBuilder
    {
        if (request()->get($key) !== 'false') {
            \call_user_func_array($function, [&$this->query]);
        }

        return $this;
    }

    public function filterResultsIfTrueByScope(array $filters): EloquentListingBuilder
    {
        foreach ($filters as $key => $scope) {
            if (request()->get($key) === 'true') {
                $this->query = $this->query->$scope();
            }
        }

        return $this;
    }

    public function filterResultsIfNotTrueByScope(array $filters): EloquentListingBuilder
    {
        foreach ($filters as $key => $scope) {
            if (request()->get($key) !== 'true') {
                $this->query = $this->query->$scope();
            }
        }

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

    public function getResults(): Collection
    {
        return $this->query
            ->get();
    }

    public function getPaginatedResults(): LengthAwarePaginator
    {
        return $this->query
            ->paginate($this->perPage);
    }
}
