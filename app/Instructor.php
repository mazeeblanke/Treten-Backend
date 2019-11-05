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
        'bio'
    ];

    protected $appends = [
        'instructor_slug'
    ];

    public function getBioAttribute ($value)
    {
        return $value ?? '';
    }

    public function getSocialLinksAttribute ($value)
    {
        return $value 
            ? unserialize($value)
            : [];
    }

    public function getCertificationsAttribute ($value)
    {
        return $value
            ? unserialize($value)
            : [];
    }

    public function getEducationAttribute ($value)
    {
        return $value
            ? unserialize($value)
            : [];
    }

    public function getWorkExperienceAttribute ($value)
    {
        return $value
            ? unserialize($value)
            : [];
    }

    public function setCertificationsAttribute ($value)
    {
        $this->attributes['certifications'] = serialize($value);
    }

    public function setSocialLinksAttribute ($value)
    {
        $this->attributes['social_links'] = serialize($value);
    }

    public function setEducationAttribute ($value)
    {
        $this->attributes['education'] = serialize($value);
    }

    public function setWorkExperienceAttribute ($value)
    {
        $this->attributes['work_experience'] = serialize($value);
    }

    public static $rules = [
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'consideration' => ['required', 'string'],
        'qualifications' => ['required', 'string'],
        'as' => ['required', 'string', 'in:student,instructor'],
        'phone_number' => ['required', 'string'],
        // 'other_name' => ['string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,provider,provider_id'],
        'password' => ['required', 'string', 'min:8'],
    ];

    public static function register ($data)
    {
        $instructor = static::create($data);
        return $instructor->details;
    }

    public function getInstructorSlugAttribute ()
    {
        return \Str::slug("{$this->details->first_name} {$this->details->last_name} {$this->id}", '_');
    }

    public function details ()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
