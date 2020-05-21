<?php

namespace App\Filters;

class ResourceCollectionFilters extends Filters {

 protected $filters = [
    'q',
    'withAuthor',
    'categoryId'
 ];

 protected function applyGlobalQueries ()
 {

    $this->processStudentRequest();

    $this->processAdminOrInstructorRequest();

 }

 protected function filterByWithAuthor ()
 {
    $withAuthor = $this->request->withAuthor;

    if (\is_null($withAuthor) && $withAuthor === 1)
    {
        $this->builder = $this->builder->with(['author']);
    }

 }

 protected function filterByCategoryId ()
 {
    $this->builder = $this->builder
        ->whereHas('course.categories', function ($query) {
            $query->where('course_categories.id', $this->request->categoryId);
        });
 }

 protected function filterByQ ()
 {
    $this->builder = $this
        ->builder
        ->where(function ($query) {
            if ($this->request->q) {
                return $query
                    ->orWhere(
                        'title',
                        'like',
                        '%' . $this->request->q . '%'
                    )
                    ->orWhere(
                        'summary',
                        'like',
                        '%' . $this->request->q . '%'
                    );
            }
            return $query;
        });
 }

 private function processStudentRequest ()
 {
    if (
        auth()->check() &&
        auth()->user()->isAStudent()
    ) {
        $this->builder = $this->builder->whereHas('course', function ($query) {
            return $query
                ->join(
                    'course_enrollments',
                    'course_enrollments.course_id',
                    'courses.id'
                )
                ->where('course_enrollments.user_id', auth()->user()->id);
        });
    }
 }

 private function processAdminOrInstructorRequest ()
 {
    if (
        auth()->check() &&
        (auth()->user()->isAnAdmin() || auth()->user()->isAnInstructor())
    ) {
        $this->builder = $this->builder->whereAuthorId(auth()->user()->id);
    }
 }

}
