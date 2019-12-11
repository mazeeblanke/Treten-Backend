<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    /**
     * The mass assiganable fileds
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'profile_pic',
        'role',
        'name'
    ];
}
