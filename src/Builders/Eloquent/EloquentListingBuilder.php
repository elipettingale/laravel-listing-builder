<?php

namespace EliPett\ListingBuilder\Builders\Eloquent;

use EliPett\ListingBuilder\Builders\ListingBuilder;
use EliPett\ListingBuilder\Filters\Eloquent\EloquentCallableFilter;
use EliPett\ListingBuilder\Filters\Eloquent\EloquentScopeFilter;
use EliPett\ListingBuilder\Filters\Eloquent\EloquentWhereEqualFilter;
use EliPett\ListingBuilder\Filters\Eloquent\EloquentWhereLikeFilter;
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

    public function whereEqual(array $args): EloquentListingBuilder
    {
        $filter = new EloquentWhereEqualFilter($this->request);

        foreach ($args as $arg) {
            $filter->filter($this->query, $arg);
        }

        return $this;
    }

    public function whereLike(array $args): EloquentListingBuilder
    {
        $filter = new EloquentWhereLikeFilter($this->request);

        foreach ($args as $arg) {
            $filter->filter($this->query, $arg);
        }

        return $this;
    }

    public function if(string $key, string $value, $arg): EloquentListingBuilder
    {
        if ($this->request->get($key) === $value) {
            if (\is_callable($arg)) {
                $filter = new EloquentCallableFilter($this->request);
                $filter->filter($this->query, $arg);
            }

            if (\is_string($arg)) {
                if (strpos($arg, 'scope') === 0) {
                    $filter = new EloquentScopeFilter($this->request);
                    $filter->filter($this->query, $arg);
                }
            }
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
