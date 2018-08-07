<?php

namespace EliPett\ListingBuilder\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ListingBuilder
{
    public function whereEqual(array $args);
    public function whereLike(array $args);
    public function if(string $key, string $value, $arg);

    public function get(): Collection;
    public function paginate(): LengthAwarePaginator;
}
