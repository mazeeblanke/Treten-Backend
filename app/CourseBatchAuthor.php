<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseBatchAuthor extends Model
// class CourseBatchInstructor extends Pivot
{
    protected $table = "course_batch_author";
    protected $fillable = [
        'author_id',
        'course_id',
        'course_batch_id',
        'active',
        'timetable'
    ];

    protected $appends = [
        'timetable'
    ];
    
    public function author()
    {
        return $this->belongsTo(Instructor::class, 'author_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function batch()
    {
        return $this->belongsTo(CourseBatch::class, 'course_batch_id');
    }

    public function getTimetableAttribute($value)
    {
        // return $this->attributes['timetable'];
        return $this->attributes['timetable']
        ? unserialize($this->attributes['timetable'])
        : [];
        // return $value
        // ? unserialize($value)
        // : [];
    }

    public function setTimetableAttribute($value)
    {
        $this->attributes['timetable'] = serialize($value);
    }
}