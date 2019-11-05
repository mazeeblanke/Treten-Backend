<?php

namespace App;

use App\BlogPost;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = [
        'name',
        'description'
    ];

    public function blogPosts ()
    {
        return $this->belongsToMany(BlogPost::class);
    }
}
