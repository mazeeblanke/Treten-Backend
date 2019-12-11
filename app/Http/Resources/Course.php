<?php

namespace App\Http\Resources;

use App\CourseEnrollment;
use App\Http\Resources\CoursePath as CoursePathResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Course extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $instructor = method_exists($this->whenLoaded('instructors'), 'first')
            ? new UserResource($this->instructors->first())
            : null;

        $courseReview = method_exists($this->whenLoaded('courseReviews'), 'first')
            ? new CourseReview($this->courseReviews->first())
            : [];

        // $instructorReview = method_exists($this->whenLoaded('instructorReviews'), 'first') && $this->instructorReviews->first()
        //     ? new InstructorReview($this->instructorReviews->first())
        //     : [
        //         'rating' => 0,
        //         'reviewText' => ''
        //     ];
        $instructorReviews = $this->relationLoaded('instructorReviews')
            ? (InstructorReview::collection($this->instructorReviews))->groupBy('author_id')
            : [];

        if ($request->enrolled != 1) {
            $relatedCourses = \App\Course::where('id', '!=', $this->id)->whereHas('categories', function ($query) {
                $query->where('course_categories.name', optional($this->category)->name);
            })->whereIsPublished(1)->whereHas('instructors')->inRandomOrder()->limit(5)->get();
            // if (count($relatedCourses) === 1) {
            //     $relatedCourses = [$relatedCourses];
            // }
        }    

        return [
            'id' => $this->when($this->id, $this->id),
            'slug' => $this->when($this->slug, $this->slug),
            'coursePathPosition' => $this->when($this->course_path_position, $this->course_path_position),
            'coursePathId' => $this->when($this->course_path_id, $this->course_path_id),
            'isPublished' => $this->when($this->is_published >= 0, $this->is_published),
            'publishedAt' => $this->published_at,
            'institution' => $this->when($this->institution, $this->institution),
            'certificationBy' => $this->certification_by,
            'bannerImage' => $this->banner_image,
            'description' => $this->when($this->description, $this->description),
            'authorId' => $this->when($this->author_id, $this->author_id),
            'duration' => $this->when($this->duration, $this->duration),
            'title' => $this->when($this->title, $this->title),
            'faqs' => $this->faqs,
            'modules' => $this->modules,
            'enrollment' => $this->enrollment,
            'avgRating' => $this->avg_rating,
            'courseReview' => $courseReview,
            'relatedCourses' => $relatedCourses ?? [] ,
            'courseReviews' => CourseReview::collection(($this->courseReviews)),
            'instructorReviews' => $instructorReviews,
            // 'reviews' => new CourseReview($this->whenLoaded('reviews')),
            'transaction' => $this->transaction,
            'learnersCount' => CourseEnrollment::learnersCountFor($this->id),
            'courseId' => $this->when($this->course_id, $this->course_id),
            'price' => $this->when($this->price, $this->price),
            'author' => new UserResource($this->whenLoaded('author')),
            'resources' => Resource::collection($this->whenLoaded('resources')),
            'batches' => CourseBatch::collection($this->whenLoaded('batches')),
            'timetable' => Timetable::collection($this->whenLoaded('timetable')),
            'instructor' => new UserResource($this->when($this->instructor, $this->instructor)),
            // 'instructor' => $this->when($instructor, $instructor),
            'instructors' => UserResource::collection($this->whenLoaded('instructors')),
            'coursePath' => new CoursePathResource($this->whenLoaded('coursePath')),
            'category' => $this->when($this->category, $this->category),
            'batchName' => $this->when($this->batch_name, $this->batch_name),
            'modeOfDelivery' => $this->when($this->mode_of_delivery, $this->mode_of_delivery),
            'commencementDates' => $this->when($this->commencementDates, $this->commencementDates),
            'availableModesOfDelivery' => $this->when($this->availableModesOfDelivery, $this->availableModesOfDelivery),
        ];
    }
}