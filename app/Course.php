<?php

namespace App;

use Exception;
use App\CourseBatchAuthor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_path_position',
        'certification_by',
        'course_path_id',
        'banner_image',
        'is_published',
        'published_at',
        'institution',
        'description',
        'author_id',
        'created_at',
        'duration',
        'video_id',
        'modules',
        'title',
        'faqs',
        'price',
    ];

    protected $appends = [
        'certification_by',
        'instructor',
        'category',
        'slug'
    ];

    public static $rules = [
        'course_path_position' => 'nullable',
        'certification_by' => 'nullable',
        'course_path_id' => 'nullable',
        'is_published' => 'nullable',
        'published_at' => 'nullable',
        'institution' => 'nullable',
        'banner_image' => 'nullable',
        'description' => 'required',
        'video_id' => 'nullable',
        'duration' => 'nullable',
        'modules' => 'nullable',
        'title' => 'required',
        'faqs' => 'nullable',
        'price' => 'nullable',
    ];

    public static $getRules = [
        "notassigned" => ""
        //validate notassigned, must be 1 or 0
        //validate withBatches, must be 1 or 0
        //validate ninstructor, must be numeric and also check that the requester has the right permission to access the information
        //if instructor id then use permissins,
        // isPublished must be 0 or 1
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'course_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function timetable()
    {
        return $this->hasMany(CourseBatchAuthor::class, 'course_id');
    }

    public function getFaqsAttribute($value)
    {
        return unserialize($value)
            ? unserialize($value)
            : [];
    }

    public function getModulesAttribute($value)
    {
        return unserialize($value)
            ? unserialize($value)
            : [];
    }

    public function getCertificationByAttribute($value)
    {
        $certBy = (array) json_decode(unserialize($value));

        return isset($certBy['value']) && isset($certBy['label'])
            ? json_decode(unserialize($value))
            : [
                'value' => '',
                'label' => ''
            ];
    }

    public function getBannerImageAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }

    public function setFaqsAttribute($value)
    {
        $this->attributes['faqs'] = serialize(json_decode($value));
    }

    public function setCertificationByAttribute($value)
    {
        $this->attributes['certification_by'] = serialize($value);
    }

    public function setModulesAttribute($value)
    {
        $this->attributes['modules'] = serialize(json_decode($value));
    }

    public function getCategoryAttribute()
    {
        return $this->categories->first();
    }

    public function getInstructorAttribute()
    {
        return $this->instructors->first();
    }

    public function getSlugAttribute()
    {
        $slug = \Str::slug($this->title, '_');
        return "{$slug}_{$this->id}";
    }

    public function categories()
    {
        return $this
            ->belongsToMany(
                CourseCategory::class,
                'course_categories_allocation'
            );
    }

    public function courseReviews()
    {
        return $this->hasMany(CourseReview::class);
    }

    public function instructorReviews()
    {
        return $this->hasMany(InstructorReview::class);
    }

    public function category()
    {
        return $this->categories->first();
    }

    public function scopeFilterUsing($query, $filters)
    {
        $filters->apply($query);
    }

    public function scopeHasInstructors($query)
    {
        // done on purpose course only shows up when an insructor has created a timetable
        $query
            ->with(['instructors' => function ($query) {
                return $query->hasCourseTimetable();
            }])
            ->whereHas('instructors', function ($query) {
                return $query->hasCourseTimetable();
            });
    }

    public function scopeMainCategories($query)
    {
        $query->with('categories')
            ->whereHas('categories', function ($query) {
                return $query->where(function ($query) {
                    return $query
                        ->orWhere('name', 'associate')
                        ->orWhere('name', 'expert')
                        ->orWhere('name', 'professional');
                });
            });
    }

    public function scopeOrderByLatest($query)
    {
        $query->orderBy('courses.created_at', request()->sort ?? 'desc');
    }

    public function scopeUniqueCoursesWithBatches($query)
    {
        $query->select(
            \DB::raw('max(course_batch_author.id) as cba_id'),
            'courses.id',
            'courses.title',
            'courses.created_at',
            'courses.banner_image',
            'course_batch_author.course_id'
        )
            ->join(
                'course_batch_author',
                'course_batch_author.course_id',
                'courses.id'
            );
    }

    public function instructors()
    {
        return $this
            ->belongsToMany(
                User::class,
                'course_batch_author',
                'course_id',
                'author_id'
            )
            ->hasCourseTimetable();
    }

    public function batches()
    {
        return $this
            ->belongsToMany(
                CourseBatch::class,
                'course_batch_author',
                'course_id',
                'course_batch_id'
            )
            ->withPivot([
                'id',
                'author_id',
                'timetable'
            ]);
    }

    public function coursePath()
    {
        return $this->belongsTo(CoursePath::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    private function suggestPosition($coursePath)
    {
        $offset = $this->id ? 0 : 1;
        return CoursePath::find($coursePath)->courses()->count() + $offset;
    }

    private function noSpecifiedPositionForExistingPath($coursePath, $coursePathPosition)
    {
        return \is_numeric($coursePath) && is_null($coursePathPosition);
    }

    private function specifiedPositionForExistingPath($coursePath, $coursePathPosition)
    {
        return is_numeric($coursePath) && !is_null($coursePathPosition);
    }

    private function coursePathIsString($coursePath)
    {
        return !\is_numeric($coursePath) && is_string($coursePath);
    }

    private function checkIfCoursePathExists($coursePath)
    {
        if (!CoursePath::whereId($coursePath)->exists()) {
            throw new Exception('Course path with id ' . $coursePath . ' does not exist');
        }
    }

    protected function createCourse($request, $coursePath, $coursePathPosition)
    {
        return static::create([
            'faqs' => $request->faqs,
            'title' => $request->title,
            'price' => $request->price,
            'video_id' => $request->videoId,
            'modules' => $request->modules,
            'course_path_id' => $coursePath,
            'duration' => $request->duration,
            'author_id' => request()->user()->id,
            'institution' => $request->institution,
            'description' => $request->description,
            'is_published' => $request->isPublished,
            'certification_by' => $request->certificationBy,
            'course_path_position' => $coursePathPosition,
            'published_at' => $request->isPublished == 1 ? now() : null,
        ]);
    }

    protected function saveCourse($request, $coursePath, $coursePathPosition)
    {
        $this->update([
            'author_id' => request()->user()->id,
            'faqs' => $request->faqs ?? $this->faqs,
            'title' => $request->title ?? $this->title,
            'price' => $request->price ?? $this->price,
            'course_path_position' => $coursePathPosition,
            'modules' => $request->modules ?? $this->modules,
            'duration' => $request->duration ?? $this->duration,
            'video_id' => $request->videoId,
            'course_path_id' => $coursePath ?? $this->course_path_id,
            'published_at' => $request->isPublished == 1 ? now() : null,
            'institution' => $request->institution ?? $this->institution,
            'description' => $request->description ?? $this->description,
            'is_published' => $request->isPublished ?? $this->is_published,
            'certification_by' => $request->certificationBy ?? $this->certification_by,
        ]);
        return $this;
    }

    private function storeImage()
    {
        if (request()->hasFile('bannerImage')) {
            $extension = request()->file('bannerImage')->extension();
            Storage::delete($this->banner_image);
            $banner_image = request()
                ->file('bannerImage')
                ->storeAs(
                    'courses',
                    "{$this->id}.{$extension}"
                );
            $this->update([
                'banner_image' => $banner_image
            ]);
        }
        return $this;
    }

    private function calculateCoursePathPosition($coursePath, $coursePathPosition)
    {
        $suggestedPosition = $this->suggestPosition($coursePath);

        if ($coursePathPosition >= $suggestedPosition) {
            $coursePathPosition = $suggestedPosition;
        }

        $rearrangedCourses = CoursePath::find($coursePath)
            ->courses()
            ->where('courses.id', '!=', $this->id)
            ->get()
            ->sortBy('course_path_position');

        $rearrangedCourses->splice($coursePathPosition - 1, 0, false);

        $rearrangedCourses = $rearrangedCourses
            ->map(function ($course, $key) {
                if (!$course) {
                    $course = Course::whereId($this->id)->first();
                }
                if ($course) {
                    $course->course_path_position = $key + 1;
                    return $course;
                }
                return null;
            })
            ->filter(function ($course) {
                return !is_null($course);
            });

        CoursePath::find($coursePath)
            ->courses()
            ->saveMany($rearrangedCourses->all());

        return $coursePathPosition;
    }

    private function saveCategories()
    {
        $category = request()->category;

        if (is_string($category) && !is_numeric($category)) {
            $savedCategory = CourseCategory::whereName($category)->first();
            if (!is_null($savedCategory)) {
                $category = $savedCategory->id;
            } elseif (is_null($savedCategory)) {
                $this->clearCategories();
                $this->categories()->create([
                    'name' => $category,
                ]);
            }
        }

        if (is_numeric($category)) {
            if (request()->id && optional($this->category())->exists) {
                $this->clearCategories();
            }
            $this->categories()->attach([$category]);
        }

        return $this;
    }

    protected function clearCategories ()
    {
        \DB::table('course_categories_allocation')->where('course_id', $this->id)->delete();
    }

    public static function store($request)
    {
        // everything here should be in a transaction
        $instance = new static;
        $coursePath = $request->coursePath;
        $coursePathPosition = $request->coursePathPosition;

        if ($instance->noSpecifiedPositionForExistingPath($coursePath, $coursePathPosition)) {
            $instance->checkIfCoursePathExists($coursePath);
            $coursePathPosition = $instance->suggestPosition($coursePath);
        }

        //NB validate that coutsepathposition is numeric
        if ($instance->specifiedPositionForExistingPath($coursePath, $coursePathPosition)) {
            $instance->checkIfCoursePathExists($coursePath);
            $coursePathPosition = $instance->calculateCoursePathPosition($coursePath, $coursePathPosition);
        }

        if ($instance->coursePathIsString($coursePath)) {
            if (!$coursePath = CoursePath::whereName($coursePath)->first()) {
                $coursePath = CoursePath::create([
                    'name' => $request->coursePath,
                    'banner_image' => collect([
                        'courses/course1.png',
                        'courses/course2.png',
                        'courses/course3.png',
                        'courses/course4.png',
                        'courses/course5.png',
                    ])
                        ->random(1)
                        ->first()
                ])->id;
                $coursePathPosition = 1;
            } else {
                //handle
                $coursePath = $coursePath->id;

                if ($coursePathPosition !== $instance->coursePathPosition) {
                    $coursePathPosition = $instance->calculateCoursePathPosition($coursePath, $coursePathPosition);
                }
            }
        }

        return $instance
            ->createCourse($request, $coursePath, $coursePathPosition)
            ->storeImage()
            ->saveCategories()
            ->fresh()
            ->load(['coursePath', 'author']);
    }

    public function modify($request)
    {
        $instance = $this;
        $coursePath = $request->coursePath;
        $coursePathPosition = $request->coursePathPosition;

        // if ($instance->noSpecifiedPositionForExistingPath($coursePath, $coursePathPosition)) {
        //     $instance->checkIfCoursePathExists($coursePath);
        //     $coursePathPosition = $instance->suggestPosition($coursePath);
        // }

        // //NB validate that coutsepathposition is numeric
        // if ($instance->specifiedPositionForExistingPath($coursePath, $coursePathPosition)) {
        //     $instance->checkIfCoursePathExists($coursePath);
        //     $suggestedPosition = $instance->suggestPosition($coursePath);
        //     if ($coursePathPosition >= $suggestedPosition) {
        //         $coursePathPosition = $suggestedPosition;
        //     }
        //     if ($coursePathPosition < $suggestedPosition) {
        //         $rearrangedCourses = CoursePath::find($coursePath)
        //             ->courses
        //             ->sortBy('course_path_position');

        //         $rearrangedCourses->splice($coursePathPosition - 1, 0, false);

        //         $rearrangedCourses = $rearrangedCourses
        //             ->map(function ($course, $key) {
        //                 if ($course) {
        //                     $course->course_path_position = $key + 1;
        //                     return $course;
        //                 }
        //                 return null;
        //             })
        //             ->filter(function ($course) {
        //                 return !is_null($course);
        //             });

        //         CoursePath::find($coursePath)->courses()->saveMany($rearrangedCourses->all());
        //     }
        // }

        if ($instance->coursePathIsString($coursePath)) {
            if (!$coursePath = CoursePath::whereName($coursePath)->first()) {
                $coursePath = CoursePath::create([
                    'name' => $request->coursePath,
                ])->id;
                $coursePathPosition = 1;
            } else {
                //handle
                $coursePath = $coursePath->id;

                if ($coursePathPosition !== $this->coursePathPosition) {
                    $coursePathPosition = $instance->calculateCoursePathPosition($coursePath, $coursePathPosition);
                }
            }
        }

        return $instance
            ->saveCourse($request, $coursePath, $coursePathPosition)
            ->storeImage()
            ->saveCategories()
            ->fresh()
            ->load([
                'coursePath',
                'author',
                'batches' => function ($query) {
                    return $query
                        ->where('course_batch_author.author_id', auth()->user()->id)
                        ->orderBy('course_batches.id', 'desc');
                }
            ]);
    }
}
