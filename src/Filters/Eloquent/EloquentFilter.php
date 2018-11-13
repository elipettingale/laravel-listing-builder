<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use EliPett\ListingBuilder\Filters\Filter;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;

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
     * @param Builder $query
     * @param string $key
     */
    public function filterWhereEqual($query, string $key): void
    {
        $query->where($key, '=', $this->request->get($key));
    }

    /**
     * @param Builder $query
     * @param string $key
     */
    public function filterWhereLike($query, string $key): void
    {
        $query->where($key, 'LIKE', "%{$this->request->get($key)}%");
    }

    /**
     * @param Builder $query
     * @param mixed $arg
     * @param null $value
     */
    public function filter($query, $arg, $value = null): void
    {
        if (\is_callable($arg)) {
            $this->filterByCallable($query, $arg, $value);
            return;
        }

        if (\is_string($arg)) {
            if (strpos($arg, 'scope') === 0) {
                $this->filterByScope($query, $arg, $value);
                return;
            }
        }

        throw new \InvalidArgumentException("Unknown Filter Argument: $arg");
    }

    /**
     * @param Builder $query
     * @param callable $function
     * @param mixed $value
     */
    private function filterByCallable($query, callable $function, $value): void
    {
        $function($query, $value);
    }

    /**
     * @param Builder $query
     * @param string $scope
     * @param mixed $value
     */
    private function filterByScope($query, string $scope, $value): void
    {
        $method = substr($scope, 5);

        $query->$method($value);
    }

    /**
     * @param Builder $query
     * @return Collection
     */
    public function get($query): Collection
    {
        return $query->get();
    }

    /**
     * @param Builder $query
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate($query, int $perPage): LengthAwarePaginator
    {
        return $query->paginate($perPage);
    }
}
