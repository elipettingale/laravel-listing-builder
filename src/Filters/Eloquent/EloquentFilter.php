<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use EliPett\ListingBuilder\Filters\Filter;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EloquentFilter implements Filter
{
    private $request;
    private $listingSpecification;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->listingSpecification = new ListingSpecification($request);
    }

    public function filterWhereEqual($query, string $key): void
    {
        if ($value = $this->request->get($key)) {
            $query->where($key, '=', $value);
        }
    }

    public function filterWhereLike($query, string $key): void
    {
        if ($value = $this->request->get($key)) {
            $query->where($key, 'LIKE', "%$value%");
        }
    }

    public function filter($query, $arg): void
    {
        if (\is_callable($arg)) {
            $this->filterByCallable($query, $arg);
            return;
        }

        if (\is_string($arg)) {
            if (strpos($arg, 'scope') === 0) {
                $this->filterByScope($query, $arg);
                return;
            }
        }

        throw new \InvalidArgumentException("Unknown Filter Argument: $arg");
    }

    private function filterByCallable($query, callable $function): void
    {
        $function($query);
    }

    private function filterByScope($query, string $scope): void
    {
        $method = substr($scope, 5);

        $query->$method();
    }

    public function get($query): Collection
    {
        return $query->get();
    }

    public function paginate($query): LengthAwarePaginator
    {
        return $query->paginate($this->listingSpecification->getPerPage());
    }
}
