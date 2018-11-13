<?php

namespace EliPett\ListingBuilder\Filters\Collection;

use EliPett\ListingBuilder\Filters\Filter;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CollectionFilter implements Filter
{
    private $request;
    private $listingSpecification;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->listingSpecification = new ListingSpecification($request);
    }

    /**
     * @param Collection $collection
     * @param string $key
     */
    public function filterWhereEqual($collection, string $key): void
    {
        $collection->where($key, '=', $this->request->get($key));
    }

    /**
     * @param Collection $collection
     * @param string $key
     */
    public function filterWhereLike($collection, string $key): void
    {
        $collection->where($key, 'LIKE', $this->request->get($key));
    }

    /**
     * @param Collection $collection
     * @param mixed $arg
     * @param null $value
     */
    public function filter($collection, $arg, $value = null): void
    {
        if (\is_callable($arg)) {
            $this->filterByCallable($collection, $arg, $value);
            return;
        }

        throw new \InvalidArgumentException("Unknown Filter Argument: $arg");
    }

    /**
     * @param Collection $collection
     * @param callable $function
     * @param mixed $value
     */
    private function filterByCallable($collection, callable $function, $value): void
    {
        $function($collection, $value);
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function get($collection): Collection
    {
        return $collection;
    }

    /**
     * @param Collection $collection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate($collection, int $perPage): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $collection->forPage($this->listingSpecification->getCurrentPage(), $perPage),
            $collection->count(),
            $perPage,
            $this->listingSpecification->getCurrentPage(),
            ['path' => $this->listingSpecification->getUrl()]
        );
    }
}
