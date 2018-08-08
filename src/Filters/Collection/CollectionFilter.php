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
     * @param \Illuminate\Support\Collection $collection
     * @param string $key
     */
    public function filterWhereEqual($collection, string $key): void
    {
        $collection->where($key, '=', $this->request->get($key));
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     * @param string $key
     */
    public function filterWhereLike($collection, string $key): void
    {
        $collection->where($key, 'LIKE', $this->request->get($key));
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     * @param $arg
     */
    public function filter($collection, $arg): void
    {
        // TODO: Implement filter() method.
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     * @return \Illuminate\Support\Collection
     */
    public function get($collection): Collection
    {
        return $collection;
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($collection): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $collection->forPage($this->listingSpecification->getCurrentPage(), $this->listingSpecification->getPerPage()),
            $collection->count(),
            $this->listingSpecification->getPerPage(),
            $this->listingSpecification->getCurrentPage(),
            ['path' => $this->listingSpecification->getUrl()]
        );
    }
}
