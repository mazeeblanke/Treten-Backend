<?php

namespace App\Filters;
/**
 * Filter post collection
 */
class BlogPostCollectionFilters extends Filters {

/**
 * The supported filters
 *
 * @var array
 */
 protected $filters = [
    'q',
    'isPublished',
 ];

 /**
  * Get the supported filters
  *
  * @return array
  */
 public function getFilters (): array
 {
	 return $this->filters;
 }

 protected function filterByIsPublished ()
 {
		$isPublished = $this->request->isPublished ?? 1;
		$this->builder = $this
            ->builder
            ->wherePublished($isPublished);
 }

 protected function filterByQ ()
 {
		$this->builder = $this
			->builder
			->where(
				'blog_posts.title',
				'like',
				"%{$this->request->q}%"
			);
 }

}
