<?php

namespace App\Filters;

class CoursePathCollectionFilters extends Filters {
 protected $filters = [
     'q',
 ];

 public function getFilters (): array
 {
	 return $this->filters;
 }

 protected function filterByQ ()
 {
    $this->builder = $this
        ->builder
        ->where(function ($q) {
            $q->orWhere(
                'name',
                'like',
                "%{$this->request->q}%"
            )
            ->orWhere(
                'description',
                'like',
                "%{$this->request->q}%"
            );
        });
 }
}
