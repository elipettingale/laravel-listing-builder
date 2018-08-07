<?php

namespace EliPett\ListingBuilder\Structs;

use Illuminate\Http\Request;

class ListingSpecification
{
    private $column;
    private $direction;

    private $currentPage;
    private $perPage;
    private $url;

    public function __construct(Request $request)
    {
        $this->column = $request->get('column');
        $this->direction = $request->get('direction');
        $this->currentPage = $request->get('page', 1);
        $this->perPage = $request->get('per_page', 10);
        $this->url = $request->url();
    }

    public function getColumn(): ?string
    {
        return $this->column;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
