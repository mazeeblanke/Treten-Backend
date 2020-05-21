<?php

namespace App\Filters;

class TestimonialCollectionFilters extends Filters {

    protected $filters = [
        'q'
    ];

    protected function filterByQ ()
    {
        $q = $this->request->q;
        $this->builder = $this
            ->builder
            ->where(function ($query) use ($q) {
                if ($q) {
                    return $query
                        ->orWhere('name', 'like', '%' . $q . '%')
                        ->orWhere('text', 'like', '%' . $q . '%');
                }
                return $query;
            });
    }

}
