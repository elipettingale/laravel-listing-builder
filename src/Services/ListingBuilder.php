<?php

namespace EliPett\ListingBuilder\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use EliPett\ListingBuilder\Structs\ListingSpecification;
use Illuminate\Support\Collection;

interface ListingBuilder
{
    public function fromListingSpecification(ListingSpecification $listingSpecification);

    public function orderResults(string $defaultColumn, string $defaultDirection);

    public function filterResults(callable $function);
    public function filterResultsIfTrue(string $key, callable $function);
    public function filterResultsIfNotTrue(string $key, callable $function);
    public function filterResultsIfFalse(string $key, callable $function);
    public function filterResultsIfNotFalse(string $key, callable $function);
    public function filterResultsWhereLike(array $keys);
    public function filterResultsWhereEqual(array $keys);
    public function filterResultsWhereConcatLike(string $key, string $firstColumn, string $secondColumn);

    public function getResults(): Collection;
    public function getPaginatedResults(): LengthAwarePaginator;
}
