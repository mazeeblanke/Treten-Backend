<?php

namespace App;

use Exception;
use App\CourseBatchAuthor;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_path_position',
        'course_path_id',
        'is_published',
        'published_at',
        'institution',
        'certification_by',
        'banner_image',
        'description',
        'author_id',
        'duration',
        'title',
        'faqs',
        'modules',
        'price',
        'created_at',
    ];

    protected $appends = [
        'category',
        'instructor',
        'certification_by',
        'slug'
    ];

    public static $rules = [
        'course_path_position' => 'nullable',
        'course_path_id' => 'nullable',
        'is_published' => 'nullable',
        'published_at' => 'nullable',
        'institution' => 'nullable',
        'certification_by' => 'nullable',
        'banner_image' => 'nullable',
        'description' => 'required',
        'duration' => 'nullable',
        'title' => 'required',
        'faqs' => 'nullable',
        'modules' => 'nullable',
        'price' => 'nullable',
    ];

    public function transactions () {
        return $this->hasMany(Transaction::class, 'course_id');
    }

    // public function transaction () {
    //     return $this->transactions();
    // }

    public function enrollments () {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function timetable () {
        return $this->hasMany(CourseBatchAuthor::class, 'course_id');
    }

    // public function enrollment () {
    //     return $this->enrollments();
    // }

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
        return is_array(unserialize($value))
        ? json_decode(unserialize($value))
        : [
            'value' => '',
            'label' => ''
        ];
    }

    public function getBannerImageAttribute($value)
    {
        return $value ? \Storage::url($value) : null;
        // return $value ? \Storage::url($value).'?='.now() : null;
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
        return $this->belongsToMany(CourseCategory::class, 'course_categories_allocation');
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

    public function scopeFilter ($query, $filters)
    {
        $filters->apply($query);
    }

    public function scopeUniqueCoursesWithBatches ($query)
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
    
    // public function batches()
    // {
    //     return $this->hasMany(CourseBatch::class);
    // }
    
    public function instructors()
    {
        return $this->belongsToMany(User::class, 'course_batch_author', 'course_id', 'author_id');
    }

    // public function instructor()
    // {
    //     return $this->instructors->first();
    //     // return $this->belongsTo(CourseBatchAuthor::class, 'author_id', 'course_id');
    // }

    public function batches()
    {
        return $this->belongsToMany(CourseBatch::class, 'course_batch_author', 'course_id', 'course_batch_id')
        // ->as('batch')
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
            'modules' => $request->modules,
            'course_path_id' => $coursePath,
            'duration' => $request->duration,
            'author_id' => request()->user()->id,
            'institution' => $request->institution,
            'description' => $request->description,
            'is_published' => $request->isPublished,
            'certification_by' => $request->certificationBy,
            'published_at' => $request->isPublished == 1 ? now() : null,
            'course_path_position' => $coursePathPosition,
        ]);
        // return $this;
    }
    
    protected function saveCourse($request, $coursePath, $coursePathPosition)
    {
        $this->update([
            'faqs' => $request->faqs ?? $this->faqs,
            'title' => $request->title ?? $this->title,
            'price' => $request->price ?? $this->price,
            'modules' => $request->modules ?? $this->modules,
            'course_path_id' => $coursePath ?? $this->course_path_id,
            'certification_by' => $request->certificationBy ?? $this->certification_by,
            'author_id' => request()->user()->id,
            'institution' => $request->institution ?? $this->institution,
            'duration' => $request->duration ?? $this->duration,
            'description' => $request->description ?? $this->description,
            'is_published' => $request->isPublished ?? $this->is_published,
            'published_at' => $request->isPublished == 1 ? now() : null,
            'course_path_position' => $coursePathPosition,
        ]);
        return $this;
    }

    private function storeImage()
    {
        if (request()->hasFile('bannerImage')) {
            $extension = request()->file('bannerImage')->extension();
            \Storage::delete($this->banner_image);
            $banner_image = request()->file('bannerImage')->storeAs('courses', "{$this->id}.{$extension}");
            // dd($banner_image);
            $this->update([
                'banner_image' => $banner_image,
                // 'slug' => "{$slug}_{$this->id}",
            ]);
        }
        return $this;
    }

    private function calculateCoursePathPosition ($coursePath, $coursePathPosition) 
    {
        $suggestedPosition = $this->suggestPosition($coursePath);
        // dd($suggestedPosition);
        if ($coursePathPosition >= $suggestedPosition) {
            $coursePathPosition = $suggestedPosition;
        }
        // if ($coursePathPosition < $suggestedPosition) {
            $rearrangedCourses = CoursePath::find($coursePath)
                ->courses()
                ->where('courses.id', '!=', $this->id)
                ->get()
                ->sortBy('course_path_position');
            // $courseToBeUpdated = Course::whereId($this->id)->first(); 
            // dd($courseToBeUpdated);   
            $rearrangedCourses->splice($coursePathPosition - 1, 0, false);
            // dd($rearrangedCourses);
            $rearrangedCourses = $rearrangedCourses
                ->map(function ($course, $key) {
                    // $course = $course ??  Course::whereId($this->id)->first();
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
                // dd($rearrangedCourses);  
                
            CoursePath::find($coursePath)->courses()->saveMany($rearrangedCourses->all());
        // }
        return $coursePathPosition;
    }

    private function saveCategories()
    {
        $category = request()->category;
        // $categoryIds = array_filter(request()->categories, function ($category) {
        //     return is_numeric($category);
        // });

        // $categories = array_filter(request()->categories, function ($category) {
        //     return is_string($category) && !is_numeric($category);
        // });

        // $categories = array_map(function ($category) {
        //     return [
        //         'name' => $category,
        //     ];
        // }, $categories);

        if (is_string($category) && !is_numeric($category)) {
            $savedCategory = CourseCategory::whereName($category)->first();
            if (!is_null($savedCategory)) {
                $category = $savedCategory->id;
            } elseif (is_null($savedCategory)) {
                $this->categories()->create([
                    'name' => $category,
                ]);
            }
        }

        if (is_numeric($category)) {
            // dd($this->category->id);
            // dd($this->category()->exists);
            if (request()->id && optional($this->category())->exists) {
                // dd('lkj');
                \DB::table('course_categories_allocation')->where('course_id', $this->id)->delete();
            }
            $this->categories()->attach([$category]);
        }

        return $this;
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
            // dd(CoursePath::whereName($coursePath)->exists());
            if (!$coursePath = CoursePath::whereName($coursePath)->first()) {
                $coursePath = CoursePath::create([
                        'name' => $request->coursePath,
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