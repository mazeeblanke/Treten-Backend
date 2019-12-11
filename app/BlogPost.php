<?php

namespace App;

use App\Tag;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{

    protected $appends = [
        'blog_slug',
        'content_summary',
        'friendly_published_at',
    ];

    protected $fillable = [
        'blog_image',
        'body',
        'title',
        'published_at',
        'author_id',
    ];

    protected $dates = [
        'published_at',
    ];

    public static $rules = [
        'blog_image' => 'required|file',
        'body' => 'required',
        'title' => 'required',
        'author_id' => 'required|numeric',
    ];

    public function getBlogImageAttribute($value)
    {
        return \Storage::url($value);
    }

    public function getFriendlyPublishedAtAttribute()
    {
        return $this->published_at->format('d M Y');
    }

    public function getContentSummaryAttribute()
    {
        // dd($this->body);
        // return 'der';
        return substr($this->body, 0, 150);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getBlogSlugAttribute()
    {
        return \Str::slug("{$this->title} {$this->id}", '_');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}