<?php

namespace App;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCategory extends Model
{

    use Filterable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

}
