<?php

namespace App;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certification extends Model
{
    use Filterable;

    // protected $appends = [
    //     'banner_image'
    // ];

    protected $fillable = [
        'company',
        'banner_image'
    ];

    // public function getBannerImageAttribute($value)
    // {
    //     return $this->banner_image ?? null;
    //     // return $value ? Storage::url($value) : null;
    // }
}
