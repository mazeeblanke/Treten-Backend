<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroupAllocation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'user_group_id'
    ];
}
