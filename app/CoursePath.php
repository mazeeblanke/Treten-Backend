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

    public function getBannerImageAttribute($value)
    {
        return $value ? \Storage::url($value) : null;
        // return $value ? \Storage::url($value).'?='.now() : null;
    }
}