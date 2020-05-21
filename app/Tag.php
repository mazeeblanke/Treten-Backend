<?php

namespace App;

use App\BlogPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

    public function blogPosts ()
    {
        return $this->belongsToMany(BlogPost::class);
    }
}
