<?php

namespace App\Filters;

class CertificationCollectionFilters extends Filters {

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
                        ->orWhere('company', 'like', '%' . $q . '%');
                }
                return $query;
            });
    }

}
