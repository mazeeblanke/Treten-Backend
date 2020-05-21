<?php

namespace App;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use Filterable;
    use SoftDeletes;
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
