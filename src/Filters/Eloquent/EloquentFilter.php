<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use EliPett\ListingBuilder\Filters\Filter;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Pagination\LengthAwarePaginator;
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

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $key
     */
    public function filterWhereEqual($query, string $key): void
    {
        $query->where($key, '=', $this->request->get($key));
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $key
     */
    public function filterWhereLike($query, string $key): void
    {
        $query->where($key, 'LIKE', "%{$this->request->get($key)}%");
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param $arg
     */
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

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param callable $function
     */
    public function filterByCallable($query, callable $function): void
    {
        $function($query);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $scope
     */
    private function filterByScope($query, string $scope): void
    {
        $method = substr($scope, 5);

        $query->$method();
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Support\Collection
     */
    public function get($query): Collection
    {
        return $query->get();
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($query): LengthAwarePaginator
    {
        return $query->paginate($this->listingSpecification->getPerPage());
    }
}
