<?php

namespace App;

use App\Tag;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use Filterable;
    use SoftDeletes;

    protected $appends = [
        'blog_slug',
        'content_summary',
        'friendly_published_at',
    ];

    protected $fillable = [
        'published',
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
        'blogImage' => 'required|file',
        'body' => 'required',
        'title' => 'required'
        // 'author_id' => 'required|numeric',
    ];

    public function getBlogImageAttribute($value)
    {
        return $value ? \Storage::url($value) : null;
    }

    public function getFriendlyPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('d M Y') : null;
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

    public function scopePublished($query)
    {
        $query->where('published', 1);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
