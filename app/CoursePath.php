<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoursePath extends Model
{
    protected $fillable = [
        'name',
        'description',
        'banner_image',
    ];

    public function courses () {
        return $this->hasMany(Course::class);
    }

    public function scopeWithOrderedCourses ($query)
    {
        $query
            ->with(['courses' => function ($q) {
                $q->whereIsPublished(1)->whereHas('instructors', function ($q) {
                    return $q->hasCourseTimetable();
                })
                ->orderBy('course_path_position');
            }])
            ->whereHas('courses', function ($q) {
                $q->whereIsPublished(1)->whereHas('instructors', function ($q) {
                    return $q->hasCourseTimetable();
                })
                ->where('courses.is_published', 1);
            });
    }

    public function scopeFilterUsing ($query, $filters)
    {
        $filters->apply($query);
    }

    public function getBannerImageAttribute($value)
    {
        return $value ? \Storage::url($value) : null;
        // return $value ? \Storage::url($value).'?='.now() : null;
    }
}
