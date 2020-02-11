<?php

namespace App\Filters;

use App\Course;
use App\CourseBatchAuthor;

class CourseBatchCollectionFilters extends Filters {

 protected $filters = [
	'q',
	'authorId',
 ];

 public function getFilters (): array
 {
	return $this->filters;
 }

 protected function filterByQ ()
 {
    $this->builder = $this
        ->builder
        ->where('course_batches.course_id', $this->request->courseId)
        ->where(function ($query) {
            if ($this->request->q) {
                return $query->where(
                    'batch_name',
                    'like',
                    '%' . $this->request->q . '%'
                );
            }
            return $query;
        });
 }

 protected function filterByAuthorId ()
 {

    if (isset($this->request->authorId)) return;

    $assignedBatchIds = $this->processAssignedBatchIds();

    $this->builder = $this->builder
        ->join(
            'course_batch_author',
            'course_batches.id',
            '=',
            'course_batch_author.course_batch_id'
        )
        ->where(function ($q) use ($assignedBatchIds) {
            if (count($assignedBatchIds) > 0 ) {
                // $q = $q->where('course_batch_instructor.course_batch_id', '!=', $courseBatchInstructor->course_batch_id);
                $q = $q->whereNotIn(
                    'course_batch_author.course_batch_id',
                    $assignedBatchIds
                );
            }
            return $q
                ->where(
                    'course_batch_author.course_id',
                    $this->request->courseId
                )
                ->where(
                    'course_batch_author.author_id',
                    '!=',
                    $this->request->authorId
                );
        })
        ->groupBy(
            'course_batch_author.course_batch_id',
            'course_batches.batch_name',
            'course_batches.id'
        )
        ->select(
            'course_batch_author.course_batch_id',
            'course_batches.batch_name',
            'course_batches.id'
        );
 }

 private function processAssignedBatchIds ()
 {
    return CourseBatchAuthor::whereCourseId(
            $this->request->courseId
        )
        ->whereAuthorId($this->request->authorId)
        ->get()
        ->map(function ($batch) {
            return $batch->course_batch_id;
        })
        ->filter(function ($courseBatchId) {
            return $courseBatchId;
        })
        ->toArray();
 }

}
