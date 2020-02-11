<?php

namespace App\Filters;
;

class TransactionCollectionFilters extends Filters {

 protected $filters = [
     'q'
 ];

 protected function filterByQ ()
 {
    $q = $this->request->q;
    $this->builder = $this
        ->builder
        ->where(function ($builder) use ($q) {
            if ($q) {
                return $builder
                    ->orWhere('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhere('amount', 'like', '%' . $q . '%')
                    ->orWhere('transaction_id', 'like', '%' . $q . '%')
                    ->orWhere('status', 'like', '%' . $q . '%');
            }
            return $builder;
        });
 }
}
