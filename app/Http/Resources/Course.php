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
        // dd($this->price);

        // $instructor = method_exists($this->whenLoaded('instructors'), 'first')
        //     ? new UserResource($this->instructors->first())
        //     : null;

        $courseReview = method_exists($this->whenLoaded('courseReviews'), 'first')
            ? new CourseReview($this->courseReviews->first())
            : [];

        $instructorReviews = $this->relationLoaded('instructorReviews')
            ? (InstructorReview::collection($this->instructorReviews))->groupBy('author_id')
            : [];

        if ($request->enrolled != 1) {
            $relatedCourses = \App\Course::where('id', '!=', $this->id)->whereHas('categories', function ($query) {
                $query->where('course_categories.name', optional($this->category)->name);
            })->whereIsPublished(1)->whereHas('instructors')->inRandomOrder()->limit(5)->get();
        }

        return [
            'faqs' => $this->faqs,
            'modules' => $this->modules,
            'courseReview' => $courseReview,
            'videoId' => $this->video_id,
            'avgRating' => $this->avg_rating,
            'enrollment' => $this->enrollment,
            'enrollUrl' => $this->enroll_url,
            'transaction' => $this->transaction,
            'publishedAt' => $this->published_at,
            'bannerImage' => $this->banner_image,
            'excerpt' => $this->excerpt,
            'id' => $this->when($this->id, $this->id),
            'relatedCourses' => $relatedCourses ?? [],
            'instructorReviews' => $instructorReviews,
            'certificationBy' => $this->certification_by,
            'slug' => $this->when($this->slug, $this->slug),
            'title' => $this->when($this->title, $this->title),
            'price' => $this->when($this->price, $this->price),
            'author' => new UserResource($this->whenLoaded('author')),
            'duration' => $this->when($this->duration, $this->duration),
            'category' => $this->when($this->category, $this->category),
            'authorId' => $this->when($this->author_id, $this->author_id),
            'courseId' => $this->when($this->course_id, $this->course_id),
            'learnersCount' => CourseEnrollment::learnersCountFor($this->id),
            'batchName' => $this->when($this->batch_name, $this->batch_name),
            'batches' => CourseBatch::collection($this->whenLoaded('batches')),
            'resources' => Resource::collection($this->whenLoaded('resources')),
            'courseReviews' => CourseReview::collection(($this->courseReviews)),
            'description' => $this->when($this->description, $this->description),
            'institution' => $this->when($this->institution, $this->institution),
            'timetable' => Timetable::collection($this->whenLoaded('timetable')),
            'coursePath' => new CoursePathResource($this->whenLoaded('coursePath')),
            'coursePathId' => $this->when($this->course_path_id, $this->course_path_id),
            'instructors' => UserResource::collection($this->whenLoaded('instructors')),
            'isPublished' => $this->when($this->is_published >= 0, $this->is_published),
            'modeOfDelivery' => $this->when($this->mode_of_delivery, $this->mode_of_delivery),
            'instructor' => new UserResource($this->when($this->instructor, $this->instructor)),
            'commencementDates' => $this->when($this->commencementDates, $this->commencementDates),
            'coursePathPosition' => $this->when($this->course_path_position, $this->course_path_position),
            'availableModesOfDelivery' => $this->when($this->availableModesOfDelivery, $this->availableModesOfDelivery),
        ];
    }
}
