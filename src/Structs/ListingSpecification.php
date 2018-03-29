<?php

namespace EliPett\ListingBuilder\Structs;

use Illuminate\Http\Request;

class ListingSpecification
{
    private $column;
    private $direction;

    private $currentPage = 1;
    private $perPage = 10;
    private $url;

    public function fromRequest(Request $request): void
    {
        if ( $column = $request->get('column') ) {
            $this->column = $column;
        }

        if ( $direction = $request->get('direction') ) {
            $this->direction = $direction;
        }

        if ( $page = $request->get('page') ) {
            $this->currentPage = $page;
        }

        if ( $perPage = $request->get('per_page') ) {
            $this->perPage = $perPage;
        }

        if ( $url = $request->url() ) {
            $this->url = $url;
        }
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function getDirection()
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

    public function getUrl()
    {
        return $this->url;
    }
}
