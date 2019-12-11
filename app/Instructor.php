<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{

    protected $fillable = [
        'consideration',
        'qualifications',
        'social_links',
        'title',
        'work_experience',
        'education',
        'certifications',
        'bio',
    ];

    protected $appends = [
        'instructor_slug',
        'social_links',
    ];

    public function getBioAttribute($value)
    {
        return $value ?? '';
    }

    public function getSocialLinksAttribute($value)
    {
        return $value
        ?  unserialize($value)
        : [
            'facebook' => '',
            'linkedin' => '',
            'twitter' => ''
        ];
    }

    public function getCertificationsAttribute($value)
    {
        return $value
        ? unserialize($value)
        : [];
    }

    public function getEducationAttribute($value)
    {
        return $value
        ? unserialize($value)
        : [];
    }

    public function getWorkExperienceAttribute($value)
    {
        return $value
        ? unserialize($value)
        : [];
    }

    public function setCertificationsAttribute($value)
    {
        $this->attributes['certifications'] = serialize($value);
    }

    public function setSocialLinksAttribute($value)
    {
        $this->attributes['social_links'] = serialize($value);
    }

    public function setEducationAttribute($value)
    {
        $this->attributes['education'] = serialize($value);
    }

    public function setWorkExperienceAttribute($value)
    {
        $this->attributes['work_experience'] = serialize($value);
    }

    public static $rules = [
        'phone_number' => ['required', 'string'],
        'consideration' => ['required', 'string'],
        'qualifications' => ['required', 'string'],
        'password' => ['required', 'string', 'min:8'],
        'last_name' => ['required', 'string', 'max:255'],
        'first_name' => ['required', 'string', 'max:255'],
        'as' => ['required', 'string', 'in:student,instructor'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,provider,provider_id'],
    ];

    public static function register($data)
    {
        $instructor = static::create($data);
        return $instructor->details;
    }

    public function getInstructorSlugAttribute()
    {
        return \Str::slug("{$this->details->first_name} {$this->details->last_name} {$this->id}", '_');
    }

    public function details()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public static function studentsCount ($instructorId)
    {
        return cache()->remember("students-count-{$instructorId}", now()->addMinutes(15), function () use ($instructorId) {
            return CourseBatchAuthor::whereAuthorId($instructorId)
                ->join(
                    'course_enrollments',
                    'course_enrollments.course_batch_id',
                    'course_batch_author.course_batch_id'
                )
                ->where('course_enrollments.active', 1)
                ->count();
        });
    }
}