<?php

namespace App;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use SoftDeletes;
    use Filterable;

    protected $fillable = [
        'group_name',
        'group_description'
    ];
}
