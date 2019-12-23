<?php

namespace App\Filters;

use App\User;
use App\Course;
use App\CourseBatch;
use App\CourseBatchAuthor;

class CourseCollectionFilters extends Filters {
 protected $filters = [
	 'q',
	 'category',
	 'enrolled',
	 'authorId',
	 'notAssigned',
	 'categoryId',
	 'isPublished',
	 'hasInstructor',
 ];

 public function getFilters (): array
 {
	 return $this->filters;
 }

 protected function filterByCategory ()
 {
    if ($this->request->category !== 'all') {
        $this->builder = $this
            ->builder
            ->whereHas('categories', function($query) {
                return $query->where(
                    'course_categories.name',
                    $this->request->category
                );
            });
    }
 }

 protected function filterByCategoryId ()
 {
		$this->builder = $this
			->builder
			->whereHas('categories', function($query) {
				return $query
					->where(
						'course_categories.id',
						$this->request->categoryId
					);
			});
 }

 protected function filterByIsPublished ()
 {
		$isPublished = $this->request->isPublished;
		// if (!in_array($isPublished, [0, 1])) throw new \Exception('');
		$this->builder = $this
			->builder
			->whereIsPublished($isPublished);
 }

 protected function filterByQ ()
 {
		$this->builder = $this
			->builder
			->where(
				'courses.title',
				'like',
				"%{$this->request->q}%"
			);
 }

 protected function filterByEnrolled ()
 {
	  if (!auth()->check()) return;

	  if ((int) $this->request->enrolled === 0)
	  {
			$this->builder = $this
				->builder
				->whereDoesntHave('enrollments');
			return;
	  }

		if ((int) $this->request->enrolled === 1)
	  {
			$this->builder = $this
				->builder
				->whereHas('enrollments', function ($query) {
					$query->whereUserId(auth()->user()->id)->whereActive(1);
				})
				->join(
					'course_enrollments',
					'course_enrollments.course_id',
					'courses.id'
				)
				->join(
					'course_batches',
					'course_batches.id',
					'course_enrollments.course_batch_id'
				)
				->select(
					'courses.*',
					'course_batches.start_date',
					'course_batches.batch_name',
					'course_batches.mode_of_delivery',
					'course_batches.end_date',
					'course_batches.price',
					'course_batches.course_id',
					'course_batches.class_is_full'
				);
		}
 }

 protected function filterByHasInstructor ()
 {

	if ((int) $this->request->hasInstructor === 1) {
		$this->builder = $this
			->builder
			->hasInstructors();
		return;
	}

	if ((int) $this->request->hasInstructor === 0) {
		$this->builder = $this
			->builder
			->with(['instructors' => function($query) {
				return $query->doesntHaveCourseTimetable();
			}])
			->orWhereHas('instructors', function ($query) {
				return $query->doesntHaveCourseTimetable();
			})
			->orWhereDoesntHave('instructors');
	}
 }

 protected function filterByAuthorId ()
 {

		if (isset($this->request->notAssigned)) return;

		$authorId = $this->processAuthorId($this->request->authorId);

		$courseIds = Course::uniqueCoursesWithBatches()
			->groupBy('course_batch_author.course_id')
			->where(
				'course_batch_author.author_id',
				$authorId
			)
			->get()
			->pluck('id')
			->toArray();

			$this->builder = $this
				->builder
				->whereIn('id', $courseIds);

 }

 protected function filterByNotAssigned ()
 {
		if (!isset($this->request->authorId)) return;

		$authorId = $this->processAuthorId($this->request->authorId);

		$builder = Course::uniqueCoursesWithBatches();

		if ((int) $this->request->notAssigned === 1) {

			$builder = $this->applyNotAssignedFilters($authorId, $builder);

		}

		if ((int) $this->request->notAssigned === 0) {

			$builder = $this->applyAssignedFilters($authorId, $builder);

		}

		$courseIds = $builder->get()->pluck('id')->toArray();

		$this->builder = $this->builder->whereIn('id', $courseIds);
 }

 private function applyNotAssignedFilters ($authorId, $builder)
 {

		$courseIds = CourseBatchAuthor::getAuthorCourseAllocationIds($authorId);

		return $builder
			->groupBy('course_batch_author.course_id')
			->whereNotIn('course_batch_author.course_id', $courseIds);

 }

 private function applyAssignedFilters ($authorId, $builder)
 {
		$sort = $this->request->sort ?? 'desc';

		return $builder
			->where(
				'course_batch_author.author_id',
				$authorId
			)
			->groupBy(
				'course_batch_author.author_id',
				'course_batch_author.course_id'
			)
			->orderBy('cba_id', $sort);
 }

 private function processAuthorId ($authorId)
 {

	if (!is_numeric($authorId)) {
		$names = collect(explode(' ', $authorId))
			->filter(function ($q) {
				return $q;
			})
			->values()
			->toArray();

		$authorId = User::where(function ($q) use ($names) {
			$q->orWhere('first_name', $names[0] ?? null)
				->orWhere('last_name', $names[1] ?? null)
				->orWhere('first_name', $names[1] ?? null)
				->orWhere('last_name', $names[0] ?? null);
		})
		->first();
	}

	if (!$authorId) {
		return response()->json([
			'message' => 'Unable to find user specified',
			'data' => []
		]);
	}

	if ($authorId instanceof User) {
		$authorId = $authorId->id;
	}

	return $authorId;
 }
}
