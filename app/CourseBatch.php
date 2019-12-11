<?php

namespace App;

use App\Course;
use App\CourseBatchInstructor;
use Illuminate\Database\Eloquent\Model;

class CourseBatch extends Model
{
    protected $fillable = [
        'batch_name',
        'price',
        'start_date',
        'mode_of_delivery',
        'end_date',
        'has_ended',
        'class_is_full',
        'instructor_id',
        'course_id',
        // 'timetable',
    ];

    protected $appends = [
        'friendly_start_date'
    ];

    public static $rules = [
        'batchName' => 'required',
        'commencementDate' => 'required',
        'modeOfDelivery' => 'required',
        'courseId' => 'required',
        // 'timetable' => '',
    ];
    
    public function getFriendlyStartDateAttribute()
    {
        return \Carbon\Carbon::parse($this->start_date)->format('D, d M Y');
    }

    public function instructors()
    {
        return $this->belongsToMany(User::class, 'course_batch_author', 'course_batch_id', 'author_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments ()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_batch_id');
    }

    public function isFull ()
    {
        return $this->enrollments()->count() > 10;
    }

    public function g () {
        return $this->courses()->first();
    }

    public function courses () 
    {
        return $this->belongsToMany(Course::class, 'course_batch_author', 'course_batch_id', 'course_id')
        // ->withPivot('id');
        ->withPivot([
            'author_id',
            'timetable'
        ]);
    }

    public static function store($request)
    {
        return static::create([
            'batch_name' => $request->batchName,
            'start_date' => $request->commencementDate,
            'mode_of_delivery' => $request->modeOfDelivery,
            'course_id' => $request->courseId,
            'price' => $request->price,
            // 'timetable' => $request->timetable,
            // 'instructor_id' => auth()->user()->userable_id,
        ]);
    }
}