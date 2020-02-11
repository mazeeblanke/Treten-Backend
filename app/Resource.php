<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    protected $fillable = [
      'file',
      'course_id',
      'author_id',
      'title',
      'summary'
    ];

    protected $appends = [
      'download_link'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getDownloadLinkAttribute ()
    {
        return \Storage::disk()->url($this->file);
    }

    // /**
    //  * Get the post that owns the comment.
    // */
    // public function course()
    // {
    //     return $this->hasMany(Course::class, 'course_id');
    // }

    private function storeImage()
    {
        if (request()->has('file')) {
            $extension = request()->file('file')->extension();
            $fileSize = request()->file('file')->getSize();
            $fileSize = number_format($fileSize/1000000, 2);
            $filePath = request()->file('file')->storeAs('resources', "{$this->id}.{$extension}");
            $im = new \Imagick();
            // $im->pingImage(base_path().'/storage/app/public/'.$filePath);
            $im->pingImage(\Storage::disk()->path($filePath));
            $this->update([
                'file' => $filePath,
                'summary' => strtoupper($extension). ", {$im->getNumberImages()} Page(s) {$fileSize} MB"
            ]);
        }
        return $this;
    }

    public static function store ($request)
    {
        $instance = Resource::create([
            'title' => $request->title,
            'author_id' => auth()->user()->id,
            'file' => '',
            // 'author_id' => $request->authorId,
            'course_id' => $request->courseId,
        ]);
        return $instance->storeImage();
    }
}
