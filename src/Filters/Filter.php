<?php

namespace EliPett\ListingBuilder\Filters;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface Filter
{
    public function filterWhereEqual($data, string $key): void;
    public function filterWhereLike($data, string $key): void;
    public function filter($data, $arg): void;
    public function get($data): Collection;
    public function paginate($data): LengthAwarePaginator;
}
