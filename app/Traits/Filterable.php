<?php

namespace App\Traits;

trait Filterable {
    public function scopeFilterUsing ($query, $filters)
    {
        $filters->apply($query);
    }
}
