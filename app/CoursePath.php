<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoursePath extends Model
{
    use SoftDeletes;

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
        if (!request()->page) {
            $query
                ->with(['courses' => function ($q) {
                    $q->whereHas('instructors', function ($q) {
                        return $q->hasCourseTimetable();
                    })
                    ->orderBy('course_path_position');
                }])
                ->whereHas('courses', function ($q) {
                    $q->whereHas('instructors', function ($q) {
                        return $q->hasCourseTimetable();
                    })
                    ->where('courses.is_published', 1);
                });
        }

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
