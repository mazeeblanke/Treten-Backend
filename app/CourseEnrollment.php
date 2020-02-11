<?php

namespace App;

use App\Http\Resources\Enrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEnrollment extends Model
{


    use SoftDeletes;

  protected $fillable = [
    'user_id',
    'active',
    'course_id',
    'expires_at',
    'course_batch_id',
  ];

  static $rules = [
    'courseId' => 'required',
    'courseBatchId' => 'required',
  ];

  public function batch()
  {
    return $this->belongsTo(CourseBatch::class, 'course_batch_id');
  }

  public function course()
  {
    return $this->belongsTo(Course::class, 'course_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public static function learnersCountFor($courseId)
  {
    return cache()->remember("learners-count-{$courseId}", now()->addMinutes(15), function () use ($courseId) {
      return static::whereCourseId($courseId)->whereActive(1)->count();
    });
  }

  public static function activeClassesCount()
  {
    return cache()->remember("active-courses-count", now()->addMinutes(15), function () {
      return static::whereActive(1)->groupBy('course_id')->count();
    });
  }

  public static function lastFour()
  {
    return cache()->remember("last-four-enrollments", now()->addMinutes(15), function () {
      return Enrollment::collection(static::with(['course', 'user'])
      ->take(4)->get());
    });
  }
}
