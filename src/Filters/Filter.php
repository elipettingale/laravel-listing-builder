<?php

namespace EliPett\ListingBuilder\Filters;

use Illuminate\Http\Request;

class Filter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
