<?php

namespace EliPett\ListingBuilder\Filters\Eloquent;

use EliPett\ListingBuilder\Filters\Filter;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Database\Eloquent\Builder;
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
     * @param Builder $query
     * @param string $method
     * @param array $arguments
     */
    public function run($query, string $method, array $arguments): void
    {
        \call_user_func_array([$query, $method], $arguments);
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

        if (\class_exists($arg)) {
            $this->filterByGlobalScope($query, $arg, $value);
            return;
        }

        if (\is_string($arg)) {
            if (strpos($arg, 'scope') === 0) {
                $this->filterByLocalScope($query, $arg, $value);
                return;
            }
        }

        throw new \InvalidArgumentException("Unknown Filter Argument: $arg");
    }

    /**
     * @param Builder $query
     * @param callable $function
     * @param mixed $arguments
     */
    private function filterByCallable($query, callable $function, $arguments): void
    {
        $function($query, $arguments);
    }

    /**
     * @param Builder $query
     * @param string $scope
     * @param mixed $arguments
     */
    private function filterByLocalScope($query, string $scope, $arguments): void
    {
        $method = substr($scope, 5);

        $query->$method($arguments);
    }

    /**
     * @param Builder $query
     * @param string $scope
     * @param mixed $arguments
     */
    private function filterByGlobalScope($query, string $scope, $arguments): void
    {
        $query->withGlobalScope($scope, new $scope($arguments));
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
