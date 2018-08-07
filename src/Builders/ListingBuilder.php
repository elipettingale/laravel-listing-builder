<?php

namespace EliPett\ListingBuilder\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ListingBuilder
{
    public function whereEqual(array $items);

    public function get(): Collection;
    public function paginate(): LengthAwarePaginator;
}
