<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroupAllocation extends Model
{
    protected $fillable = [
        'user_id',
        'user_group_id'
    ];
}
