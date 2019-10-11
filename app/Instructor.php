<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{

    protected $fillable = [
        'consideration',
        'qualifications',
        'social_links',
        'title'
    ];

    public static $rules = [
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'consideration' => ['required', 'string'],
        'qualifications' => ['required', 'string'],
        'as' => ['required', 'string', 'in:student,instructor'],
        'phone_number' => ['required', 'string'],
        'other_name' => ['string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,provider,provider_id'],
        'password' => ['required', 'string', 'min:8'],
    ];
    //
    public static function register ($data)
    {
        $instructor = static::create($data);
        return $instructor->details;
    }

    public function details ()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
