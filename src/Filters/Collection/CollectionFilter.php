<?php

namespace EliPett\ListingBuilder\Filters\Collection;

use EliPett\ListingBuilder\Filters\Filter;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
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

    public function filterWhereEqual($data, string $key): void
    {
        // TODO: Implement filterWhereEqual() method.
    }

    public function filterWhereLike($data, string $key): void
    {
        // TODO: Implement filterWhereLike() method.
    }

    public function filter($data, $arg): void
    {
        // TODO: Implement filter() method.
    }

    public function get($data): Collection
    {
        return $data;
    }

    public function paginate($data): LengthAwarePaginator
    {
        // TODO: Implement paginate() method.
    }
}
