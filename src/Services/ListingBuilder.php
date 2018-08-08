<?php

namespace EliPett\ListingBuilder\Services;

use EliPett\ListingBuilder\Filters\Eloquent\EloquentFilter;
use EliPett\ListingBuilder\Filters\Filter;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ListingBuilder
{
    private $request;
    private $filter;
    private $data;

    public function __construct(Request $request, Filter $filter, $data)
    {
        $this->request = $request;
        $this->filter = $filter;
        $this->data = $data;
    }

    public function whereEqual(array $args): ListingBuilder
    {
        foreach ($args as $arg) {
            if ($value = $this->request->get($arg)) {
                $this->filter->filterWhereEqual($this->data, $arg);
            }
        }

        return $this;
    }

    public function whereLike(array $args): ListingBuilder
    {
        foreach ($args as $arg) {
            if ($value = $this->request->get($arg)) {
                $this->filter->filterWhereLike($this->data, $arg);
            }
        }

        return $this;
    }

    public function ifEqual(string $value, array $args): ListingBuilder
    {
        foreach ($args as $key => $arg) {
            if ($this->request->get($key) === $value) {
                $this->filter->filter($this->data, $arg);
            }
        }

        return $this;
    }

    public function unlessEqual(string $value, array $args): ListingBuilder
    {
        foreach ($args as $key => $arg) {
            if ($this->request->get($key) !== $value) {
                $this->filter->filter($this->data, $arg);
            }
        }

        return $this;
    }

    public function ifSet(array $args): ListingBuilder
    {
        foreach ($args as $key => $arg) {
            if ($value = $this->request->get($key)) {
                $this->filter->filterByCallable($this->data, $arg, $value);
            }
        }

        return $this;
    }

    public function get(): Collection
    {
        return $this->filter->get($this->data);
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->filter
            ->paginate($this->data);
    }
}
