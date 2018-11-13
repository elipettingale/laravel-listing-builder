<?php

namespace EliPett\ListingBuilder\Structs;

use Illuminate\Http\Request;

class ListingSpecification
{
    private $currentPage;
    private $url;

    public function __construct(Request $request)
    {
        $this->currentPage = $request->get('page', 1);
        $this->url = $request->url();
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
