<?php

namespace App\Filters;

use App\User;
use App\Course;
use App\CourseBatch;
use App\CourseBatchAuthor;

class CourseReviewCollectionFilters extends Filters {

 protected $filters = [
	 'q',
     'isApproved'
 ];

 public function getFilters (): array
 {
	 return $this->filters;
 }

 protected function filterByIsApproved ()
 {
    $this->builder = $this
        ->builder
        ->whereApproved((int) $this->request->isApproved);
 }

 protected function filterByQ ()
 {
    $this->builder = $this
        ->builder
        ->orWhereHas('enrollee', function ($query) {
            return $query->where(
                'users.first_name',
                'like',
                '%' . $this->request->q . '%'
            );
        })
        ->orWhereHas('course', function ($query) {
            return $query->where(
                'courses.title',
                'like',
                '%' . $this->request->q . '%'
            );
        })
        ->orWhere(function ($query) {
            return $query
                ->orWhere(
                    'review_text',
                    'like',
                    '%' .  $this->request->q . '%'
                )
                ->orWhere(
                    'course_reviews.created_at',
                    'like',
                    '%' .  $this->request->q . '%'
                );
        });
 }

}
