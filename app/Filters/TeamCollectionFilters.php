<?php

namespace App\Filters;

class TeamCollectionFilters extends Filters {

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
                        ->orWhere('role', 'like', '%' . $q . '%');
                }
                return $query;
            });
    }

}
