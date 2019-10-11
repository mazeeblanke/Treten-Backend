<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsLetterSubscription extends Model
{
    public static $rules = [
        'email' => 'required|email',
    ];
}
