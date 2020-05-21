<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsLetterSubscription extends Model
{
    use SoftDeletes;
    
    public static $rules = [
        'email' => 'required|email',
    ];
}
