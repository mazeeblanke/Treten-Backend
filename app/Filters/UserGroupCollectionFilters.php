<?php

namespace App\Filters;

use App\User;
use App\Course;
use App\CourseBatch;
use App\CourseBatchAuthor;

class UserGroupCollectionFilters extends Filters {

 protected $filters = [
	 'q'
 ];

 public function getFilters (): array
 {
	 return $this->filters;
 }

 protected function filterByQ ()
 {
    $this->builder = $this
        ->builder
        ->where(function ($builder) {
            return $builder
            ->where(
                'group_name',
                'like',
                '%' . $this->request->q . '%'
            );
        });
 }

}
