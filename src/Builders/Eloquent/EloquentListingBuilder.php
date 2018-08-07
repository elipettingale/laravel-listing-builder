<?php

namespace EliPett\ListingBuilder\Builders\Eloquent;

use EliPett\ListingBuilder\Builders\ListingBuilder;
use EliPett\ListingBuilder\Filters\WhereEqual;
use EliPett\ListingBuilder\Filters\WhereLike;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EloquentListingBuilder implements ListingBuilder
{
    private $query;
    private $request;
    private $listingSpecification;

    public function __construct(Builder $query, Request $request)
    {
        $this->query = $query;
        $this->request = $request;
        $this->listingSpecification = new ListingSpecification($request);
    }

    public function whereEqual(array $items): EloquentListingBuilder
    {
        $filter = new WhereEqual($this->request);

        foreach ($items as $item) {
            $filter->filter($this->query, $item);
        }

        return $this;
    }

    public function whereLike(array $items): EloquentListingBuilder
    {
        $filter = new WhereLike($this->request);

        foreach ($items as $item) {
            $filter->filter($this->query, $item);
        }

        return $this;
    }

    public function get(): Collection
    {
        return $this->query->get();
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->query
            ->paginate($this->listingSpecification->getPerPage());
    }
}
